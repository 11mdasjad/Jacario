<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\CustomizableProduct;
use App\Models\CustomizableProductVariant;
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
            // 1. Re-validate stock for each item with exclusive lock
            foreach ($cart->items as $item) {
                if ($item->is_customized) {
                    $customVariant = CustomizableProductVariant::lockForUpdate()
                        ->where('customizable_product_id', $item->customizable_product_id)
                        ->where('size_id', $item->custom_size_id)
                        ->where('color_id', $item->custom_color_id)
                        ->first();

                    if ($customVariant && (!$customVariant->is_active || $customVariant->stock_quantity < $item->quantity)) {
                        $prodName = $item->customizableProduct ? $item->customizableProduct->name : 'Custom Item';
                        throw new Exception("Custom item '{$prodName}' is no longer available in the requested quantity.");
                    }
                } else {
                    $variant = ProductVariant::lockForUpdate()->find($item->product_variant_id);
                    if (!$variant || !$variant->is_active || $variant->stock_quantity < $item->quantity) {
                        throw new Exception("Product '{$variant->product->name}' in size {$variant->size->name} is no longer available in the requested quantity.");
                    }
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
                if ($item->is_customized) {
                    $customProd = $item->customizableProduct;
                    $sizeName = $item->customSize ? $item->customSize->name : 'Custom Size';
                    $colorName = $item->customColor ? $item->customColor->name : 'Custom Color';
                    $colorHex = $item->customColor ? $item->customColor->hex_code : '#18181B';
                    $sku = ($customProd ? $customProd->sku : 'JAC-CUST') . '-' . strtoupper(substr($colorName, 0, 3));

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => null,
                        'product_variant_id' => null,
                        'is_customized' => true,
                        'customizable_product_id' => $item->customizable_product_id,
                        'customization_data' => $item->customization_data,
                        'customization_preview_path' => $item->customization_preview_path,
                        'artwork_file_path' => $item->artwork_file_path,
                        'artwork_files' => $item->artwork_files,
                        'customization_fee' => $item->customization_fee,
                        'custom_production_status' => 'pending',
                        'product_name' => 'Custom ' . ($customProd ? $customProd->name : 'Bespoke Item'),
                        'size_name' => $sizeName,
                        'color_name' => $colorName,
                        'color_hex' => $colorHex,
                        'sku' => $sku,
                        'image_path' => $item->customization_preview_path ?: ($customProd ? $customProd->featured_image : null),
                        'unit_price' => $item->unit_price,
                        'quantity' => $item->quantity,
                        'subtotal' => $item->subtotal,
                    ]);

                    // Decrement custom stock
                    $customVariant = CustomizableProductVariant::where('customizable_product_id', $item->customizable_product_id)
                        ->where('size_id', $item->custom_size_id)
                        ->where('color_id', $item->custom_color_id)
                        ->first();
                    if ($customVariant) {
                        $customVariant->decrement('stock_quantity', $item->quantity);
                    }
                } else {
                    $variant = $item->variant;
                    $product = $variant->product;
                    $primaryImg = $product->images->firstWhere('color_id', $variant->color_id) ?: $product->primaryImage;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_variant_id' => $variant->id,
                        'is_customized' => false,
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

                    // Decrement standard stock
                    $variant->decrement('stock_quantity', $item->quantity);
                }
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
            // Restore inventory for all items
            foreach ($order->items as $item) {
                if ($item->is_customized) {
                    $customData = $item->customization_data;
                    $sizeId = $customData['size_id'] ?? null;
                    $colorId = $customData['color_id'] ?? null;
                    if ($item->customizable_product_id && $sizeId && $colorId) {
                        $variant = CustomizableProductVariant::where('customizable_product_id', $item->customizable_product_id)
                            ->where('size_id', $sizeId)
                            ->where('color_id', $colorId)
                            ->first();
                        if ($variant) {
                            $variant->increment('stock_quantity', $item->quantity);
                        }
                    }
                } elseif ($item->product_variant_id) {
                    $variant = ProductVariant::find($item->product_variant_id);
                    if ($variant) {
                        $variant->increment('stock_quantity', $item->quantity);
                    }
                }
            }

            $order->update([
                'status' => 'cancelled',
                'cancelled_at' => Carbon::now(),
                'cancel_reason' => $reason,
            ]);

            if ($order->payment && $order->payment->status === 'successful') {
                $order->payment->update(['status' => 'refunded']);
            }
        });
    }

    public function updateOrderStatus(Order $order, string $status, ?string $trackingNumber = null, ?string $courierName = null): Order
    {
        $allowedStatuses = ['pending', 'confirmed', 'processing', 'packed', 'shipped', 'out_for_delivery', 'delivered', 'cancelled', 'returned', 'refunded'];

        $updateData = ['status' => $status];

        if ($trackingNumber) {
            $updateData['tracking_number'] = $trackingNumber;
        }
        if ($courierName) {
            $updateData['courier_name'] = $courierName;
        }

        if ($status === 'shipped' && !$order->shipped_at) {
            $updateData['shipped_at'] = Carbon::now();
        } elseif ($status === 'delivered' && !$order->delivered_at) {
            $updateData['delivered_at'] = Carbon::now();
        }

        $order->update($updateData);
        return $order->fresh();
    }

    public function updatePaymentStatus(Order $order, string $status, ?string $gatewayPaymentId = null, ?array $payload = null): Order
    {
        $order->update(['payment_status' => $status]);

        if ($order->payment) {
            $paymentData = ['status' => $status];
            if ($gatewayPaymentId) {
                $paymentData['gateway_payment_id'] = $gatewayPaymentId;
            }
            if ($payload) {
                $paymentData['gateway_payload'] = $payload;
            }
            if ($status === 'successful') {
                $paymentData['paid_at'] = Carbon::now();
            }
            $order->payment->update($paymentData);
        }

        // If payment is successful and order is still pending, move to processing
        if ($status === 'successful' && $order->status === 'pending') {
            $order->update(['status' => 'processing']);
        }

        return $order->fresh();
    }

    private function generateOrderNumber(): string
    {
        $year = date('Y');
        do {
            $random = strtoupper(Str::random(6));
            $number = "JAC-{$year}-{$random}";
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }
}
