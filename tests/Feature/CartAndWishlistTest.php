<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartAndWishlistTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_can_add_variant_to_shopping_cart_via_ajax(): void
    {
        $user = User::where('role', 'customer')->first();
        $product = Product::with('variants')->first();
        $variant = $product->variants->first();

        $response = $this->actingAs($user)->postJson(route('cart.add'), [
            'variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
        $this->assertDatabaseHas('cart_items', [
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);
    }

    public function test_can_update_cart_quantity_and_remove(): void
    {
        $user = User::where('role', 'customer')->first();
        $product = Product::with('variants')->first();
        $variant = $product->variants->first();

        $addResponse = $this->actingAs($user)->postJson(route('cart.add'), [
            'variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $itemId = $addResponse->json('cart.items.0.id');
        $this->assertNotNull($itemId);

        // Update quantity
        $updateResponse = $this->actingAs($user)->postJson(route('cart.update'), [
            'item_id' => $itemId,
            'quantity' => 3,
        ]);
        $updateResponse->assertStatus(200);
        $this->assertEquals(3, $updateResponse->json('cart.items.0.quantity'));

        // Remove item
        $removeResponse = $this->actingAs($user)->postJson(route('cart.remove'), [
            'item_id' => $itemId,
        ]);
        $removeResponse->assertStatus(200);
        $this->assertCount(0, $removeResponse->json('cart.items'));
    }

    public function test_can_apply_and_remove_coupon_code(): void
    {
        $user = User::where('role', 'customer')->first();
        $product = Product::with('variants')->first();
        $variant = $product->variants->first();

        $this->actingAs($user)->postJson(route('cart.add'), [
            'variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $couponResponse = $this->actingAs($user)->postJson(route('cart.coupon.apply'), [
            'code' => 'JACARIO10',
        ]);

        $couponResponse->assertStatus(200);
        $couponResponse->assertJson([
            'success' => true,
        ]);
        $this->assertGreaterThan(0, $couponResponse->json('cart.discount_amount'));

        // Remove coupon
        $removeCouponResponse = $this->actingAs($user)->postJson(route('cart.coupon.remove'));
        $removeCouponResponse->assertStatus(200);
        $this->assertEquals(0, $removeCouponResponse->json('cart.discount_amount'));
    }

    public function test_customer_can_toggle_wishlist_and_move_to_cart(): void
    {
        $user = User::where('role', 'customer')->first();
        $product = Product::first();

        // Toggle add to wishlist
        $response = $this->actingAs($user)->postJson(route('wishlist.toggle'), [
            'product_id' => $product->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'is_added' => true,
        ]);
        $this->assertDatabaseHas('wishlists', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        // Move to cart
        $wishlistItem = $user->wishlists()->first();
        $moveResponse = $this->actingAs($user)->post(route('wishlist.move-to-cart', $wishlistItem->id));
        $moveResponse->assertRedirect();
        $this->assertDatabaseMissing('wishlists', [
            'id' => $wishlistItem->id,
        ]);
    }
}
