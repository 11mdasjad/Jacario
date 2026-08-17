<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ProductVariant;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function createOrder(
        Cart $cart,
        array $customerData,
        array $shippingAddress,
        string $paymentMethod = 'razorpay',
        ?array $billingAddress = null,
        ?string $notes = null
    ): Order {
        if ($cart->items->isEmpty()) {
            throw new Exception('Cannot create order from an empty cart.');
        }

        return DB::transaction(function () use ($cart, $customerData, $shippingAddress, $paymentMethod, $billingAddress, $notes) {
            // 1. Re-validate stock for each variant with exclusive lock
            foreach ($cart->items as $item) {
                $variant = ProductVariant::lockForUpdate()->find($item->product_variant_id);
                if (!$variant || !$variant->is_active || $variant->stock_quantity < $item->quantity) {
                    throw new Exception("Product '{$variant->product->name}' in size {$variant->size->name} is no longer available in the requested quantity.");
                }
            }

            // 2. Generate unique human-readable order number: JAC-2026-XXXXX
            $orderNumber = $this->generateOrderNumber();

            // 3. Create order record
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $customerData['user_id'] ?? null,
                'subtotal' => $cart->subtotal,
                'discount_amount' => $cart->discount_amount,
                'shipping_amount' => $cart->shipping_amount,
                'tax_amount' => $cart->tax_amount,
                'total_amount' => $cart->total,
                'coupon_code' => $cart->coupon_code,
                'status' => 'pending',
                'payment_method' => $paymentMethod,
                'payment_status' => 'pending',
                'customer_name' => $customerData['name'],
                'customer_email' => $customerData['email'],
                'customer_phone' => $customerData['phone'],
                'shipping_address' => $shippingAddress,
                'billing_address' => $billingAddress ?: $shippingAddress,
                'notes' => $notes,
            ]);

            // 4. Create snapshot order items and decrement inventory
            foreach ($cart->items as $item) {
                $variant = $item->variant;
                $product = $variant->product;
                $primaryImg = $product->images->firstWhere('color_id', $variant->color_id) ?: $product->primaryImage;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_variant_id' => $variant->id,
                    'product_name' => $product->name,
                    'size_name' => $variant->size->name,
                    'color_name' => $variant->color->name,
                    'color_hex' => $variant->color->hex_code,
                    'sku' => $variant->sku,
                    'image_path' => $primaryImg ? $primaryImg->image_path : null,
                    'unit_price' => $item->unit_price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->subtotal,
                ]);

                // Decrement stock
                $variant->decrement('stock_quantity', $item->quantity);
            }

            // 5. Track coupon usage if applied
            if ($cart->coupon_code && $cart->discount_amount > 0) {
                $coupon = Coupon::where('code', $cart->coupon_code)->first();
                if ($coupon) {
                    CouponUsage::create([
                        'coupon_id' => $coupon->id,
                        'user_id' => $customerData['user_id'] ?? null,
                        'order_id' => $order->id,
                        'discount_amount' => $cart->discount_amount,
                        'created_at' => now(),
                    ]);
                    $coupon->increment('used_count');
                }
            }

            // 6. Create initial Payment record
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $paymentMethod,
                'currency' => 'INR',
                'amount' => $order->total_amount,
                'status' => 'pending',
            ]);

            return $order;
        });
    }

    public function cancelOrder(Order $order, string $reason = 'Cancelled by user'): void
    {
        if (!$order->can_be_cancelled) {
            throw new Exception("Order #{$order->order_number} cannot be cancelled in its current state ({$order->status}).");
        }

        DB::transaction(function () use ($order, $reason) {
            // Restore inventory
            foreach ($order->items as $item) {
                if ($item->product_variant_id) {
                    $variant = ProductVariant::find($item->product_variant_id);
                    if ($variant) {
                        $variant->increment('stock_quantity', $item->quantity);
                    }
                }
            }

            // Rollback coupon count if cancelled
            if ($order->coupon_code) {
                $coupon = Coupon::where('code', $order->coupon_code)->first();
                if ($coupon && $coupon->used_count > 0) {
                    $coupon->decrement('used_count');
                }
            }

            $order->update([
                'status' => 'cancelled',
                'cancelled_reason' => $reason,
            ]);
        });
    }

    public function updateOrderStatus(
        Order $order,
        string $status,
        ?string $trackingNumber = null,
        ?string $courierName = null
    ): Order {
        $data = ['status' => $status];

        if ($trackingNumber !== null) {
            $data['tracking_number'] = $trackingNumber;
        }
        if ($courierName !== null) {
            $data['courier_name'] = $courierName;
        }

        if ($status === 'shipped' && !$order->shipped_at) {
            $data['shipped_at'] = Carbon::now();
        }

        if ($status === 'delivered' && !$order->delivered_at) {
            $data['delivered_at'] = Carbon::now();
            if ($order->payment_method === 'cod') {
                $data['payment_status'] = 'captured';
            }
        }

        $order->update($data);
        return $order;
    }

    public function updatePaymentStatus(Order $order, string $paymentStatus, ?string $paymentId = null, ?array $payload = null): Order
    {
        $order->update(['payment_status' => $paymentStatus]);

        if (in_array($paymentStatus, ['captured', 'authorized', 'paid']) && $order->status === 'pending') {
            $order->update(['status' => 'confirmed']);
        }

        $payment = $order->latestPayment;
        if ($payment) {
            $payment->update([
                'status' => $paymentStatus,
                'payment_id' => $paymentId ?: $payment->payment_id,
                'payload' => $payload ?: $payment->payload,
            ]);
        }

        return $order;
    }

    private function generateOrderNumber(): string
    {
        $year = date('Y');
        $random = strtoupper(Str::random(6));
        $orderNumber = "JAC-{$year}-{$random}";

        while (Order::where('order_number', $orderNumber)->exists()) {
            $random = strtoupper(Str::random(6));
            $orderNumber = "JAC-{$year}-{$random}";
        }

        return $orderNumber;
    }
}
