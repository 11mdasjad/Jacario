<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutAndOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_can_place_cash_on_delivery_order_and_decrement_stock(): void
    {
        $user = User::where('role', 'customer')->first();
        $admin = User::where('role', 'super_admin')->first();
        $product = Product::with('variants')->first();
        $variant = $product->variants->first();
        $initialStock = $variant->stock_quantity;

        // Add to cart
        $this->actingAs($user)->postJson(route('cart.add'), [
            'variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        // Submit checkout with COD
        $response = $this->actingAs($user)->post(route('checkout.order'), [
            'customer_name' => 'Lord Archibald Sterling',
            'customer_email' => 'archibald@example.com',
            'customer_phone' => '+91 98200 99999',
            'address_line_1' => 'Penthouse 4B, Imperial Towers',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'postal_code' => '400034',
            'payment_method' => 'cod',
        ]);

        $order = Order::where('customer_email', 'archibald@example.com')->first();
        $this->assertNotNull($order);
        $response->assertRedirect(route('checkout.success', $order->order_number));

        // Verify Success Page shows Congratulations, COD Notice, My Orders link, and Invoice button
        $successResponse = $this->actingAs($user)->get(route('checkout.success', $order->order_number));
        $successResponse->assertStatus(200);
        $successResponse->assertSee('Congratulations! Order Confirmed');
        $successResponse->assertSee('Cash on Delivery Confirmed');
        $successResponse->assertSee(route('account.orders'));
        $successResponse->assertSee(route('orders.invoice', $order->order_number));

        // Verify Admin can view the order and invoice link
        $adminOrderResponse = $this->actingAs($admin)->get(route('admin.orders.show', $order->id));
        $adminOrderResponse->assertStatus(200);
        $adminOrderResponse->assertSee($order->order_number);
        $adminOrderResponse->assertSee(route('orders.invoice', $order->order_number));

        // Stock decreased by 2
        $variant->refresh();
        $this->assertEquals($initialStock - 2, $variant->stock_quantity);

        // Cart is cleared
        $cartData = $this->getJson(route('cart.summary.api'));
        $this->assertCount(0, $cartData->json('items'));
    }

    public function test_can_view_order_details_and_invoice(): void
    {
        $customer = User::where('role', 'customer')->first();
        $product = Product::with('variants')->first();
        $variant = $product->variants->first();

        $order = Order::create([
            'order_number' => 'JAC-2026-INV01',
            'user_id' => $customer->id,
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
            'customer_phone' => $customer->phone ?: '+91 98765 00000',
            'shipping_address' => 'Test Address, Bandra',
            'shipping_city' => 'Mumbai',
            'shipping_state' => 'Maharashtra',
            'shipping_pincode' => '400050',
            'shipping_country' => 'India',
            'subtotal' => 2499,
            'discount_amount' => 0,
            'shipping_amount' => 0,
            'total_amount' => 2499,
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'status' => 'confirmed',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'product_name' => $product->name,
            'size_name' => $variant->size->name,
            'color_name' => $variant->color->name,
            'sku' => $variant->sku,
            'unit_price' => 2499,
            'quantity' => 1,
            'subtotal' => 2499,
        ]);

        // Public track order page
        $trackResponse = $this->get(route('orders.track', [
            'order_number' => $order->order_number,
            'email' => $order->customer_email,
        ]));
        $trackResponse->assertStatus(200);
        $trackResponse->assertSee($order->order_number);

        // Customer account order detail page
        $accountOrderResponse = $this->actingAs($customer)->get(route('account.orders.show', $order->order_number));
        $accountOrderResponse->assertStatus(200);
        $accountOrderResponse->assertSee($order->order_number);

        // Printable invoice
        $invoiceResponse = $this->get(route('orders.invoice', $order->order_number));
        $invoiceResponse->assertStatus(200);
        $invoiceResponse->assertSee('TAX INVOICE');
        $invoiceResponse->assertSee($order->order_number);
    }

    public function test_customer_can_cancel_unfulfilled_order_and_restore_stock(): void
    {
        $user = User::where('role', 'customer')->first();
        $product = Product::with('variants')->first();
        $variant = $product->variants->first();

        // Create an unfulfilled order
        $order = Order::create([
            'order_number' => 'JAC-2026-CANCEL01',
            'user_id' => $user->id,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => '+91 98200 12345',
            'shipping_address' => 'Test Address',
            'shipping_city' => 'Mumbai',
            'shipping_state' => 'Maharashtra',
            'shipping_pincode' => '400034',
            'shipping_country' => 'India',
            'subtotal' => 1999,
            'discount_amount' => 0,
            'shipping_amount' => 0,
            'total_amount' => 1999,
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'status' => 'pending',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'product_name' => $product->name,
            'size_name' => $variant->size->name,
            'color_name' => $variant->color->name,
            'sku' => $variant->sku,
            'unit_price' => 1999,
            'quantity' => 1,
            'subtotal' => 1999,
        ]);

        $initialStock = $variant->stock_quantity;

        // Cancel order
        $response = $this->actingAs($user)->post(route('account.orders.cancel', $order->order_number));
        $response->assertRedirect();

        $order->refresh();
        $this->assertEquals('cancelled', $order->status);

        // Stock restored
        $variant->refresh();
        $this->assertEquals($initialStock + 1, $variant->stock_quantity);
    }
}
