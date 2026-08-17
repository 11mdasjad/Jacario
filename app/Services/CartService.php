<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\ProductVariant;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function getCart(): Cart
    {
        if (Auth::check()) {
            $user = Auth::user();
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);
            
            // If there's a guest session cart, merge it
            $sessionId = Session::getId();
            $sessionCart = Cart::where('session_id', $sessionId)->whereNull('user_id')->first();
            if ($sessionCart && $sessionCart->id !== $cart->id) {
                $this->mergeCarts($sessionCart, $cart);
            }
            return $cart->load(['items.variant.product.images', 'items.variant.size', 'items.variant.color']);
        }

        $sessionId = Session::getId();
        $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
        return $cart->load(['items.variant.product.images', 'items.variant.size', 'items.variant.color']);
    }

    public function addItem(int $variantId, int $quantity = 1): array
    {
        $variant = ProductVariant::with('product')->find($variantId);
        if (!$variant || !$variant->is_active || !$variant->product->is_active) {
            return ['success' => false, 'message' => 'Selected Polo variation is currently unavailable.'];
        }

        if ($variant->stock_quantity < $quantity) {
            return ['success' => false, 'message' => "Only {$variant->stock_quantity} units available in stock for this size/color."];
        }

        $cart = $this->getCart();
        $cartItem = $cart->items()->where('product_variant_id', $variantId)->first();

        $newQuantity = $cartItem ? ($cartItem->quantity + $quantity) : $quantity;

        if ($newQuantity > $variant->stock_quantity) {
            return [
                'success' => false,
                'message' => "You already have {$cartItem->quantity} in your cart. Only {$variant->stock_quantity} total available.",
            ];
        }

        $unitPrice = $variant->effective_price;

        if ($cartItem) {
            $cartItem->update([
                'quantity' => $newQuantity,
                'unit_price' => $unitPrice,
            ]);
        } else {
            $cart->items()->create([
                'product_variant_id' => $variantId,
                'quantity' => $newQuantity,
                'unit_price' => $unitPrice,
            ]);
        }

        return [
            'success' => true,
            'message' => "Added {$variant->product->name} ({$variant->size->name} / {$variant->color->name}) to your bag.",
            'cart' => $this->getSummary(),
        ];
    }

    public function updateQuantity(int $cartItemId, int $quantity): array
    {
        $cart = $this->getCart();
        $cartItem = $cart->items()->find($cartItemId);

        if (!$cartItem) {
            return ['success' => false, 'message' => 'Item not found in bag.'];
        }

        if ($quantity <= 0) {
            $cartItem->delete();
            return ['success' => true, 'message' => 'Item removed from bag.', 'cart' => $this->getSummary()];
        }

        $variant = $cartItem->variant;
        if ($quantity > $variant->stock_quantity) {
            return [
                'success' => false,
                'message' => "Only {$variant->stock_quantity} units available in stock.",
                'cart' => $this->getSummary(),
            ];
        }

        $cartItem->update(['quantity' => $quantity]);

        return [
            'success' => true,
            'message' => 'Bag updated successfully.',
            'cart' => $this->getSummary(),
        ];
    }

    public function removeItem(int $cartItemId): array
    {
        $cart = $this->getCart();
        $cartItem = $cart->items()->find($cartItemId);

        if ($cartItem) {
            $cartItem->delete();
        }

        return [
            'success' => true,
            'message' => 'Item removed from your shopping bag.',
            'cart' => $this->getSummary(),
        ];
    }

    public function applyCoupon(string $code): array
    {
        $code = strtoupper(trim($code));
        $cart = $this->getCart();

        if ($cart->subtotal <= 0) {
            return ['success' => false, 'message' => 'Your bag is empty.'];
        }

        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return ['success' => false, 'message' => 'Invalid promo code.'];
        }

        $userId = Auth::id();
        if (!$coupon->isValid($userId, $cart->subtotal)) {
            if (!$coupon->is_active) {
                return ['success' => false, 'message' => 'This promo code is no longer active.'];
            }
            if ($cart->subtotal < $coupon->min_order_value) {
                return ['success' => false, 'message' => "Minimum order value of ₹" . number_format($coupon->min_order_value) . " required for this coupon."];
            }
            if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
                return ['success' => false, 'message' => 'This coupon has reached its total usage limit.'];
            }
            return ['success' => false, 'message' => 'Promo code cannot be applied to this order.'];
        }

        $cart->update(['coupon_code' => $code]);

        $discount = $coupon->calculateDiscount($cart->subtotal);

        return [
            'success' => true,
            'message' => "Coupon '{$code}' applied! You saved ₹" . number_format($discount, 2),
            'cart' => $this->getSummary(),
        ];
    }

    public function removeCoupon(): array
    {
        $cart = $this->getCart();
        $cart->update(['coupon_code' => null]);

        return [
            'success' => true,
            'message' => 'Coupon removed.',
            'cart' => $this->getSummary(),
        ];
    }

    public function getSummary(): array
    {
        $cart = $this->getCart();
        $freeShippingThreshold = (float) Setting::get('free_shipping_threshold', 1999);
        $remainingForFreeShipping = max(0, $freeShippingThreshold - $cart->subtotal);
        $freeShippingProgress = $freeShippingThreshold > 0 ? min(100, round(($cart->subtotal / $freeShippingThreshold) * 100)) : 100;

        $items = $cart->items->map(function ($item) {
            $product = $item->variant->product;
            $primaryImg = $product->images->firstWhere('color_id', $item->variant->color_id) ?: $product->primaryImage;
            return [
                'id' => $item->id,
                'variant_id' => $item->product_variant_id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'slug' => $product->slug,
                'size' => $item->variant->size->name,
                'color' => $item->variant->color->name,
                'color_hex' => $item->variant->color->hex_code,
                'sku' => $item->variant->sku,
                'image_url' => $primaryImg ? $primaryImg->url : asset('images/placeholder-polo.svg'),
                'unit_price' => (float) $item->unit_price,
                'quantity' => $item->quantity,
                'subtotal' => $item->subtotal,
                'max_stock' => $item->variant->stock_quantity,
            ];
        });

        return [
            'cart_id' => $cart->id,
            'items' => $items,
            'item_count' => $cart->item_count,
            'subtotal' => (float) $cart->subtotal,
            'discount_amount' => (float) $cart->discount_amount,
            'coupon_code' => $cart->coupon_code,
            'shipping_amount' => (float) $cart->shipping_amount,
            'tax_amount' => (float) $cart->tax_amount,
            'total' => (float) $cart->total,
            'free_shipping_threshold' => $freeShippingThreshold,
            'remaining_for_free_shipping' => $remainingForFreeShipping,
            'free_shipping_progress' => $freeShippingProgress,
        ];
    }

    public function clearCart(): void
    {
        $cart = $this->getCart();
        $cart->items()->delete();
        $cart->update(['coupon_code' => null]);
    }

    private function mergeCarts(Cart $source, Cart $destination): void
    {
        foreach ($source->items as $item) {
            $existing = $destination->items()->where('product_variant_id', $item->product_variant_id)->first();
            if ($existing) {
                $existing->update([
                    'quantity' => min($existing->quantity + $item->quantity, $item->variant->stock_quantity),
                ]);
            } else {
                $item->update(['cart_id' => $destination->id]);
            }
        }

        if ($source->coupon_code && !$destination->coupon_code) {
            $destination->update(['coupon_code' => $source->coupon_code]);
        }

        $source->delete();
    }
}
