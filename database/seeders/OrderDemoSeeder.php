<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OrderDemoSeeder extends Seeder
{
    public function run(): void
    {
        $customer = User::where('email', 'customer@jacario.com')->first();
        $otherCustomers = User::where('role', 'customer')->where('email', '!=', 'customer@jacario.com')->get();
        $products = Product::with(['variants.size', 'variants.color', 'images'])->get();

        if (!$customer || $products->isEmpty()) {
            return;
        }

        $allCustomers = collect([$customer])->concat($otherCustomers);

        $demoOrders = [
            [
                'order_number' => 'JAC-2026-89104A',
                'user' => $customer,
                'status' => 'delivered',
                'payment_status' => 'captured',
                'payment_method' => 'razorpay',
                'days_ago' => 8,
                'courier_name' => 'Blue Dart Express',
                'tracking_number' => 'BD-982147321',
                'items_count' => 2,
            ],
            [
                'order_number' => 'JAC-2026-77312B',
                'user' => $customer,
                'status' => 'shipped',
                'payment_status' => 'captured',
                'payment_method' => 'razorpay',
                'days_ago' => 2,
                'courier_name' => 'Delhivery Air',
                'tracking_number' => 'DEL-441209876',
                'items_count' => 1,
            ],
            [
                'order_number' => 'JAC-2026-66489C',
                'user' => $allCustomers[1] ?? $customer,
                'status' => 'processing',
                'payment_status' => 'captured',
                'payment_method' => 'razorpay',
                'days_ago' => 1,
                'courier_name' => null,
                'tracking_number' => null,
                'items_count' => 3,
            ],
            [
                'order_number' => 'JAC-2026-55120D',
                'user' => $allCustomers[2] ?? $customer,
                'status' => 'confirmed',
                'payment_status' => 'captured',
                'payment_method' => 'razorpay',
                'days_ago' => 0,
                'courier_name' => null,
                'tracking_number' => null,
                'items_count' => 2,
            ],
            [
                'order_number' => 'JAC-2026-44219E',
                'user' => $allCustomers[3] ?? $customer,
                'status' => 'delivered',
                'payment_status' => 'captured',
                'payment_method' => 'cod',
                'days_ago' => 14,
                'courier_name' => 'Blue Dart Express',
                'tracking_number' => 'BD-110293847',
                'items_count' => 2,
            ],
        ];

        foreach ($demoOrders as $data) {
            $user = $data['user'];
            $address = $user->defaultAddress ?: $user->addresses()->first();
            $addressData = $address ? [
                'full_name' => $address->full_name,
                'phone' => $address->phone,
                'address_line_1' => $address->address_line_1,
                'address_line_2' => $address->address_line_2,
                'city' => $address->city,
                'state' => $address->state,
                'postal_code' => $address->postal_code,
                'country' => $address->country,
            ] : [
                'full_name' => $user->name,
                'phone' => $user->phone ?: '+91 98200 12345',
                'address_line_1' => 'Flat 402, Sea View Residency',
                'address_line_2' => 'Carter Road, Bandra West',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'postal_code' => '400050',
                'country' => 'India',
            ];

            $orderDate = Carbon::now()->subDays($data['days_ago'])->subHours(rand(1, 10));

            $order = Order::updateOrCreate(
                ['order_number' => $data['order_number']],
                [
                    'user_id' => $user->id,
                    'customer_name' => $user->name,
                    'customer_email' => $user->email,
                    'customer_phone' => $user->phone ?: '+91 98200 12345',
                    'shipping_address' => $addressData,
                    'billing_address' => $addressData,
                    'status' => $data['status'],
                    'payment_method' => $data['payment_method'],
                    'payment_status' => $data['payment_status'],
                    'subtotal' => 0, // Will calculate below
                    'discount_amount' => 0,
                    'shipping_amount' => 0,
                    'tax_amount' => 0,
                    'total_amount' => 0,
                    'courier_name' => $data['courier_name'],
                    'tracking_number' => $data['tracking_number'],
                    'shipped_at' => in_array($data['status'], ['shipped', 'out_for_delivery', 'delivered']) ? $orderDate->copy()->addDay() : null,
                    'delivered_at' => $data['status'] === 'delivered' ? $orderDate->copy()->addDays(3) : null,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ]
            );

            // Add Order Items
            $subtotal = 0;
            for ($i = 0; $i < $data['items_count']; $i++) {
                $product = $products[($order->id + $i) % $products->count()];
                $variant = $product->variants->first();
                if (!$variant) continue;

                $primaryImg = $product->images->firstWhere('color_id', $variant->color_id) ?: $product->primaryImage;
                $unitPrice = $variant->effective_price;
                $quantity = 1;
                $itemSubtotal = $unitPrice * $quantity;
                $subtotal += $itemSubtotal;

                OrderItem::updateOrCreate(
                    ['order_id' => $order->id, 'product_id' => $product->id, 'product_variant_id' => $variant->id],
                    [
                        'product_name' => $product->name,
                        'size_name' => $variant->size ? $variant->size->name : 'L',
                        'color_name' => $variant->color ? $variant->color->name : 'Obsidian Black',
                        'color_hex' => $variant->color ? $variant->color->hex_code : '#18181B',
                        'sku' => $variant->sku,
                        'image_path' => $primaryImg ? $primaryImg->image_path : null,
                        'unit_price' => $unitPrice,
                        'quantity' => $quantity,
                        'subtotal' => $itemSubtotal,
                    ]
                );
            }

            $shipping = $subtotal >= 1999 ? 0 : 150;
            $total = $subtotal + $shipping;

            $order->update([
                'subtotal' => $subtotal,
                'shipping_amount' => $shipping,
                'total_amount' => $total,
            ]);

            // Add Payment Record
            Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'payment_id' => 'pay_mock_' . uniqid() . '_' . $order->id,
                    'razorpay_order_id' => 'order_mock_' . uniqid() . '_' . $order->id,
                    'razorpay_signature' => 'sig_mock_' . md5($order->order_number),
                    'payment_method' => $data['payment_method'],
                    'currency' => 'INR',
                    'amount' => $total,
                    'status' => $data['payment_status'],
                    'created_at' => $orderDate,
                ]
            );
        }
    }
}
