<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guest_is_redirected_when_accessing_admin_console(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_regular_customer_is_forbidden_from_admin_console(): void
    {
        $customer = User::where('role', 'customer')->first();

        $response = $this->actingAs($customer)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    public function test_super_admin_can_access_dashboard_and_manage_resources(): void
    {
        $admin = User::where('role', 'super_admin')->first();

        // Dashboard
        $this->actingAs($admin)->get(route('admin.dashboard'))->assertStatus(200)->assertSee('Performance Overview');

        // Products
        $this->actingAs($admin)->get(route('admin.products.index'))->assertStatus(200)->assertSee('Product Catalog');

        // Orders
        $this->actingAs($admin)->get(route('admin.orders.index'))->assertStatus(200)->assertSee('Order Management');

        // Customers
        $this->actingAs($admin)->get(route('admin.customers.index'))->assertStatus(200)->assertSee('Customer Accounts');

        // Coupons
        $this->actingAs($admin)->get(route('admin.coupons.index'))->assertStatus(200)->assertSee('Promotional Coupons');

        // Reviews
        $this->actingAs($admin)->get(route('admin.reviews.index'))->assertStatus(200)->assertSee('Review Moderation');

        // Settings
        $this->actingAs($admin)->get(route('admin.settings.index'))->assertStatus(200)->assertSee('Store Settings');
    }

    public function test_admin_can_update_order_status_and_courier(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $customer = User::where('role', 'customer')->first();
        $product = Product::with('variants')->first();
        $variant = $product->variants->first();

        $order = Order::create([
            'order_number' => 'JAC-2026-TEST01',
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
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.orders.status', $order->id), [
            'status' => 'shipped',
            'courier_name' => 'BlueDart Express',
            'tracking_number' => 'BD-TEST-990011',
        ]);

        $response->assertRedirect();
        $order->refresh();
        $this->assertEquals('shipped', $order->status);
        $this->assertEquals('BlueDart Express', $order->courier_name);
        $this->assertEquals('BD-TEST-990011', $order->tracking_number);
    }

    public function test_admin_can_approve_and_delete_reviews(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $customer = User::where('role', 'customer')->first();
        $product = Product::first();

        $review = Review::create([
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'rating' => 5,
            'title' => 'Exquisite Craftsmanship',
            'comment' => 'The fabric weight and collar stiffness are exceptional.',
            'is_verified_purchase' => true,
            'is_approved' => false,
            'is_featured' => false,
        ]);

        // Toggle approve
        $initialState = $review->is_approved;
        $response = $this->actingAs($admin)->post(route('admin.reviews.toggle-approved', $review->id));
        $response->assertRedirect();
        $this->assertEquals(!$initialState, $review->fresh()->is_approved);

        // Delete review
        $deleteResponse = $this->actingAs($admin)->delete(route('admin.reviews.destroy', $review->id));
        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }

    public function test_admin_can_create_product_with_multiple_images(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $category = \App\Models\Category::first();

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'name' => 'Royal Heritage Silk Polo',
            'sku' => 'JAC-ROYAL-999',
            'category_id' => $category->id,
            'base_price' => 2999,
            'sale_price' => 2499,
            'short_description' => 'Fine Mulberry Silk Blend',
            'description' => 'Hand-stitched luxury polo with mother-of-pearl buttons.',
            'fabric' => '85% Supima, 15% Silk',
            'fit' => 'Tailored Fit',
            'default_stock' => 30,
            'image_urls' => [
                'https://images.unsplash.com/photo-1586363104862-3a5e2ab60d99?w=800',
                'https://images.unsplash.com/photo-1581655353564-df123a1eb820?w=800',
                'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=800',
            ],
            'primary_image_index' => 0,
        ]);

        $response->assertRedirect(route('admin.products.index'));

        $product = Product::where('sku', 'JAC-ROYAL-999')->first();
        $this->assertNotNull($product);
        $this->assertCount(3, $product->images);
        $this->assertTrue($product->images->where('is_primary', true)->count() >= 1);
        $this->assertGreaterThanOrEqual(1, $product->variants->count());
    }
}
