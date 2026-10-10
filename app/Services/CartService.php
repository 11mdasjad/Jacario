<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Color;
use App\Models\Coupon;
use App\Models\CustomizableProduct;
use App\Models\CustomizableProductVariant;
use App\Models\ProductVariant;
use App\Models\Setting;
use App\Models\Size;
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
            return $cart->load([
                'items.variant.product.images', 
                'items.variant.size', 
                'items.variant.color',
                'items.customizableProduct.settings',
                'items.customColor',
                'items.customSize',
            ]);
        }

        $sessionId = Session::getId();
        $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
        return $cart->load([
            'items.variant.product.images', 
            'items.variant.size', 
            'items.variant.color',
            'items.customizableProduct.settings',
            'items.customColor',
            'items.customSize',
        ]);
    }

    public function addItem(int $variantId, int $quantity = 1): array
    {
        $variant = ProductVariant::with(['product', 'size', 'color'])->find($variantId);
        if (!$variant || !$variant->is_active || !$variant->product || !$variant->product->is_active) {
            return ['success' => false, 'message' => 'Selected Polo variation is currently unavailable.'];
        }

        if ($variant->stock_quantity < $quantity) {
            return ['success' => false, 'message' => "Only {$variant->stock_quantity} units available in stock for this size/color."];
        }

        $cart = $this->getCart();
        $cartItem = $cart->items()->where('product_variant_id', $variantId)->where('is_customized', false)->first();

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
                'is_customized' => false,
                'quantity' => $newQuantity,
                'unit_price' => $unitPrice,
            ]);
        }

        $sizeName = $variant->size ? $variant->size->name : '';
        $colorName = $variant->color ? $variant->color->name : '';
        $variantDesc = trim("{$sizeName} / {$colorName}", ' /');
        $descString = $variantDesc ? " ({$variantDesc})" : '';

        return [
            'success' => true,
            'message' => "Added {$variant->product->name}{$descString} to your bag.",
            'cart' => $this->getSummary(),
        ];
    }

    public function addCustomItem(array $data): array
    {
        $productId = (int) ($data['product_id'] ?? 0);
        $sizeId = (int) ($data['size_id'] ?? 0);
        $colorId = (int) ($data['color_id'] ?? 0);
        $quantity = max(1, (int) ($data['quantity'] ?? 1));

        $product = CustomizableProduct::with(['settings', 'variants'])->find($productId);
        if (!$product || !$product->is_active) {
            return ['success' => false, 'message' => 'Customizable product is currently unavailable.'];
        }

        $variant = CustomizableProductVariant::where('customizable_product_id', $productId)
            ->where('size_id', $sizeId)
            ->where('color_id', $colorId)
            ->first();

        if ($variant && (!$variant->is_active || $variant->stock_quantity < $quantity)) {
            return ['success' => false, 'message' => "Requested quantity ({$quantity}) exceeds available stock ({$variant->stock_quantity})."];
        }

        $settings = $product->settings;
        $basePrice = $product->effective_price;

        // Customization fee calculations
        $customizationFee = $settings ? (float) $settings->base_customization_fee : 0.0;
        $customizationData = $data['customization_data'] ?? [];
        $layers = $customizationData['layers'] ?? [];

        $imageCount = 0;
        $textCount = 0;
        foreach ($layers as $layer) {
            if (($layer['type'] ?? '') === 'image') {
                $imageCount++;
            } elseif (($layer['type'] ?? '') === 'text') {
                $textCount++;
            }
        }

        // Apply extra charges if more than 1 image or text
        if ($settings) {
            if ($imageCount > 1) {
                $customizationFee += ($imageCount - 1) * (float) $settings->extra_image_fee;
            }
            if ($textCount > 1) {
                $customizationFee += ($textCount - 1) * (float) $settings->extra_text_fee;
            }
        }

        $unitPriceBeforeBulk = $basePrice + $customizationFee;

        // Apply bulk discount tier if applicable
        $bulkDiscountPct = $settings ? $settings->calculateBulkDiscount($quantity) : 0.0;
        if ($bulkDiscountPct > 0) {
            $unitPrice = round($unitPriceBeforeBulk * (1 - ($bulkDiscountPct / 100)), 2);
        } else {
            $unitPrice = round($unitPriceBeforeBulk, 2);
        }

        $cart = $this->getCart();

        $cartItem = $cart->items()->create([
            'is_customized' => true,
            'customizable_product_id' => $productId,
            'custom_color_id' => $colorId,
            'custom_size_id' => $sizeId,
            'customization_data' => $customizationData,
            'customization_preview_path' => $data['preview_path'] ?? null,
            'artwork_file_path' => $data['artwork_file_path'] ?? null,
            'artwork_files' => $data['artwork_files'] ?? [],
            'customization_fee' => $customizationFee,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
        ]);

        $size = Size::find($sizeId);
        $color = Color::find($colorId);

        return [
            'success' => true,
            'message' => "Customized {$product->name} (" . ($size ? $size->name : '') . " / " . ($color ? $color->name : '') . ") added to your shopping bag.",
            'item_id' => $cartItem->id,
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

        if ($cartItem->is_customized) {
            $customVariant = CustomizableProductVariant::where('customizable_product_id', $cartItem->customizable_product_id)
                ->where('size_id', $cartItem->custom_size_id)
                ->where('color_id', $cartItem->custom_color_id)
                ->first();

            if ($customVariant && $quantity > $customVariant->stock_quantity) {
                return [
                    'success' => false,
                    'message' => "Only {$customVariant->stock_quantity} units available in stock.",
                    'cart' => $this->getSummary(),
                ];
            }

            // Recalculate bulk discount for custom item if quantity changes
            $product = $cartItem->customizableProduct;
            $settings = $product ? $product->settings : null;
            $basePrice = $product ? $product->effective_price : 1499.00;
            $unitPriceBeforeBulk = $basePrice + (float) $cartItem->customization_fee;

            $bulkDiscountPct = $settings ? $settings->calculateBulkDiscount($quantity) : 0.0;
            $newUnitPrice = $bulkDiscountPct > 0 ? round($unitPriceBeforeBulk * (1 - ($bulkDiscountPct / 100)), 2) : round($unitPriceBeforeBulk, 2);

            $cartItem->update([
                'quantity' => $quantity,
                'unit_price' => $newUnitPrice,
            ]);
        } else {
            $variant = $cartItem->variant;
            if ($variant && $quantity > $variant->stock_quantity) {
                return [
                    'success' => false,
                    'message' => "Only {$variant->stock_quantity} units available in stock.",
                    'cart' => $this->getSummary(),
                ];
            }

            $cartItem->update(['quantity' => $quantity]);
        }

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
        $freeShippingThreshold = (float) Setting::get('free_shipping_threshold', 999);
        $remainingForFreeShipping = max(0, $freeShippingThreshold - $cart->subtotal);
        $freeShippingProgress = $freeShippingThreshold > 0 ? min(100, round(($cart->subtotal / $freeShippingThreshold) * 100)) : 100;

        $items = $cart->items->map(function ($item) {
            if ($item->is_customized && $item->customizableProduct) {
                $customProd = $item->customizableProduct;
                $sizeName = $item->customSize ? $item->customSize->name : 'M';
                $colorName = $item->customColor ? $item->customColor->name : 'Obsidian Black';
                $colorHex = $item->customColor ? $item->customColor->hex_code : '#18181B';
                $imageUrl = $item->customization_preview_path ? asset($item->customization_preview_path) : asset($customProd->featured_image ?: 'images/placeholder-polo.svg');

                return [
                    'id' => $item->id,
                    'is_customized' => true,
                    'variant_id' => null,
                    'product_id' => $customProd->id,
                    'product_name' => 'Custom ' . $customProd->name,
                    'slug' => 'customize/' . $customProd->slug,
                    'size' => $sizeName,
                    'color' => $colorName,
                    'color_hex' => $colorHex,
                    'sku' => $customProd->sku . '-' . strtoupper(substr($colorName, 0, 3)),
                    'image_url' => $imageUrl,
                    'unit_price' => (float) $item->unit_price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->subtotal,
                    'max_stock' => 99,
                    'customization_fee' => (float) $item->customization_fee,
                    'customization_data' => $item->customization_data,
                    'artwork_file_url' => $item->artwork_file_path ? asset($item->artwork_file_path) : null,
                ];
            }

            $product = $item->variant && $item->variant->product ? $item->variant->product : null;
            if (!$product) {
                return null;
            }
            $primaryImg = $product->images->firstWhere('color_id', $item->variant->color_id) ?: $product->primaryImage;
            return [
                'id' => $item->id,
                'is_customized' => false,
                'variant_id' => $item->product_variant_id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'slug' => $product->slug,
                'size' => $item->variant->size ? $item->variant->size->name : '',
                'color' => $item->variant->color ? $item->variant->color->name : '',
                'color_hex' => $item->variant->color ? $item->variant->color->hex_code : '#000000',
                'sku' => $item->variant->sku,
                'image_url' => $primaryImg ? $primaryImg->url : asset('images/placeholder-polo.svg'),
                'unit_price' => (float) $item->unit_price,
                'quantity' => $item->quantity,
                'subtotal' => $item->subtotal,
                'max_stock' => $item->variant->stock_quantity,
            ];
        })->filter()->values();

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
            if (!$item->is_customized) {
                $existing = $destination->items()->where('product_variant_id', $item->product_variant_id)->where('is_customized', false)->first();
                if ($existing) {
                    $existing->update([
                        'quantity' => min($existing->quantity + $item->quantity, $item->variant ? $item->variant->stock_quantity : 20),
                    ]);
                } else {
                    $item->update(['cart_id' => $destination->id]);
                }
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
