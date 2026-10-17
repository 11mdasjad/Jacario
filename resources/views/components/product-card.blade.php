@props(['product'])

@php
    $primaryImage = $product->primaryImage;
    $detailImage = $product->images->firstWhere('sort_order', 2) ?: $primaryImage;
    $colors = $product->availableColors();
    $sizes = $product->availableSizes();
    $inStockVariants = $product->activeVariants->where('stock_quantity', '>', 0);
    $firstVariant = $inStockVariants->first() ?? $product->activeVariants->first();
    $defaultSizeId = $firstVariant ? $firstVariant->size_id : ($sizes->first() ? $sizes->first()->id : '');
    $isWishlisted = Auth::check() && Auth::user()->wishlists()->where('product_id', $product->id)->exists();

    $quickViewData = [
        'id' => $product->id,
        'name' => $product->name,
        'slug' => $product->slug,
        'category_name' => $product->category ? $product->category->name : 'Polo T-Shirt',
        'image' => $primaryImage ? $primaryImage->url : asset('images/placeholder-polo.svg'),
        'base_price' => number_format($product->base_price),
        'sale_price' => $product->sale_price ? number_format($product->sale_price) : null,
        'effective_price' => number_format($product->effective_price),
        'has_discount' => $product->has_discount,
        'discount_percent' => $product->discount_percent,
        'rating' => number_format($product->average_rating, 1),
        'reviews_count' => $product->daily_views_count,
        'description' => $product->short_description ?: $product->fabric,
        'sizes' => $sizes->map(fn($s) => ['id' => $s->id, 'code' => $s->code, 'name' => $s->name]),
        'colors' => $colors->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'hex_code' => $c->hex_code]),
        'variants' => $product->activeVariants->map(fn($v) => [
            'id' => $v->id,
            'size_id' => $v->size_id,
            'color_id' => $v->color_id,
            'stock' => $v->stock_quantity
        ]),
    ];
@endphp

<div class="group relative flex flex-col bg-white rounded-2xl border border-zinc-200/80 overflow-hidden hover:shadow-xl hover:border-zinc-300 transition-all duration-300"
     x-data="{ 
        selectedColorImg: '{{ $primaryImage ? $primaryImage->url : asset('images/placeholder-polo.svg') }}',
        quickAddOpen: false,
        isAdding: false,
        selectedSizeId: '{{ $defaultSizeId }}',
        variants: @js($product->activeVariants->map(fn($v) => ['id' => $v->id, 'size_id' => $v->size_id, 'color_id' => $v->color_id, 'stock' => $v->stock_quantity])),
        getSelectedVariantId(sizeId = null) {
            const targetSize = sizeId || this.selectedSizeId;
            const v = this.variants.find(item => item.size_id == targetSize && item.stock > 0);
            if (v && v.id) return v.id;
            const anyAvailable = this.variants.find(item => item.stock > 0);
            return anyAvailable ? anyAvailable.id : {{ $firstVariant ? $firstVariant->id : 0 }};
        },
        async quickAddSize(sizeId) {
            this.selectedSizeId = sizeId;
            await this.addToCart(sizeId);
        },
        async addToCart(explicitSizeId = null) {
            const varId = this.getSelectedVariantId(explicitSizeId);
            if (!varId || varId <= 0) {
                window.toast('This silhouette is currently out of stock.', 'error');
                return;
            }
            this.isAdding = true;
            try {
                const drawer = this.$store?.cartDrawer || (window.Alpine && window.Alpine.store('cartDrawer')) || window.$store?.cartDrawer || window.cartDrawer;
                if (!drawer) {
                    window.toast('Cart system initializing, please try again.', 'error');
                    return;
                }
                const success = await drawer.addItem(varId, 1);
                if (success) {
                    this.quickAddOpen = false;
                }
            } catch (err) {
                console.error('Error adding to bag:', err);
                window.toast('Failed to add item to bag. Please try again.', 'error');
            } finally {
                this.isAdding = false;
            }
        },
        openQuickView() {
            window.dispatchEvent(new CustomEvent('open-quick-view', { detail: @js($quickViewData) }));
        }
     }">
    
    <!-- Image Stage -->
    <div class="relative block aspect-[4/5] bg-zinc-100 overflow-hidden">
        


        <!-- Floating Wishlist Heart Button -->
        <button type="button" 
                @click.prevent="$store.wishlist.toggle({{ $product->id }}, $el)" 
                class="absolute top-2.5 right-2.5 z-10 w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-white/90 backdrop-blur-md shadow-sm border border-zinc-100 flex items-center justify-center text-zinc-400 hover:text-rose-600 active:scale-90 transition-all {{ $isWishlisted ? 'text-rose-600' : '' }}" 
                aria-label="Add to Wishlist">
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 {{ $isWishlisted ? 'fill-rose-600' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </button>

        <!-- Product Image Anchor with Hover Zoom -->
        <a href="{{ route('products.show', $product->slug) }}" class="block w-full h-full">
            <img :src="selectedColorImg" 
                 alt="{{ $product->name }}" 
                 loading="lazy"
                 class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
        </a>

        <!-- Myntra-Style Floating Rating Pill on Image -->
        <div class="absolute bottom-2 left-2 z-10 inline-flex items-center space-x-1 px-1.5 py-0.5 rounded-md bg-white/90 backdrop-blur-xs border border-zinc-200/80 shadow-xs text-[10px] font-bold text-zinc-800">
            <span>{{ number_format($product->average_rating, 1) }}</span>
            <svg class="w-2.5 h-2.5 text-amber-500 fill-amber-500 inline-block" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            <span class="text-zinc-400">|</span>
            <span class="text-zinc-600 font-semibold text-[9.5px]">{{ $product->daily_views_count }}</span>
        </div>
    </div>

    <!-- Product Info (Compact for 2-Col Mobile) -->
    <div class="p-2.5 sm:p-4 flex-1 flex flex-col justify-between space-y-2 sm:space-y-3">
        <div>
            <!-- Brand & Category -->
            <div class="flex items-center justify-between">
                <p class="text-[9px] sm:text-[10px] font-bold text-zinc-900 uppercase tracking-widest">JACARIO</p>
                <p class="text-[9px] sm:text-[10px] text-[#8C6D46] font-medium truncate max-w-[80px] sm:max-w-none">{{ $product->category->name }}</p>
            </div>

            <!-- Product Title -->
            <h3 class="text-xs sm:text-sm font-semibold text-zinc-900 group-hover:text-[#8C6D46] transition-colors line-clamp-1 mt-0.5">
                <a href="{{ route('products.show', $product->slug) }}">
                    {{ $product->name }}
                </a>
            </h3>
            
            <!-- Fabric subtitle -->
            <p class="text-[10px] sm:text-xs text-zinc-500 line-clamp-1 mt-0.5 hidden sm:block">{{ $product->fabric }}</p>
        </div>

        <div>
            <!-- Myntra-Style Pricing Block -->
            <div class="flex items-baseline flex-wrap gap-x-1.5 gap-y-0.5 pt-0.5">
                @if($product->has_discount)
                    <span class="text-xs sm:text-sm font-bold text-zinc-950">₹{{ number_format($product->sale_price) }}</span>
                    <span class="text-[10px] sm:text-xs text-zinc-400 line-through">₹{{ number_format($product->base_price) }}</span>
                    <span class="text-[10px] sm:text-xs font-bold text-emerald-700">({{ $product->discount_percent }}% OFF)</span>
                @else
                    <span class="text-xs sm:text-sm font-bold text-zinc-950">₹{{ number_format($product->base_price) }}</span>
                @endif
            </div>

            <!-- Add to Cart CTA Button with Expandable Size Selector -->
            <div class="mt-2 sm:mt-3" x-show="!quickAddOpen">
                <button type="button" 
                        @click="quickAddOpen = true" 
                        class="w-full py-1.5 sm:py-2 bg-emerald-800 hover:bg-emerald-900 text-[#DFCAAB] text-[10px] sm:text-xs font-bold uppercase tracking-wider rounded-lg transition-all duration-200 flex items-center justify-center space-x-1.5 active:scale-95 shadow-sm group/btn">
                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 transition-transform duration-200 group-hover/btn:scale-110 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Add to Cart</span>
                </button>
            </div>

            <!-- Size Selector when Add to Cart Clicked -->
            <div class="mt-2 p-1.5 sm:p-2 bg-zinc-50 rounded-xl border border-zinc-200 shadow-inner" x-show="quickAddOpen" x-transition>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-zinc-600">Select Size:</span>
                    <button type="button" @click="quickAddOpen = false" class="text-zinc-400 hover:text-black text-xs p-0.5" aria-label="Cancel">✕</button>
                </div>
                <div class="grid grid-cols-5 gap-1 mb-1.5">
                    @foreach($sizes as $size)
                        @php
                            $inStock = $product->activeVariants->where('size_id', $size->id)->where('stock_quantity', '>', 0)->isNotEmpty();
                        @endphp
                        <button type="button" 
                                @click="quickAddSize('{{ $size->id }}')"
                                :class="selectedSizeId == '{{ $size->id }}' ? 'bg-black text-white border-black ring-1 ring-black' : 'bg-white text-zinc-800 border-zinc-200 hover:border-black hover:bg-zinc-100'"
                                class="py-1 text-[10px] font-bold rounded-md border transition-all active:scale-90 {{ !$inStock ? 'opacity-30 cursor-not-allowed line-through bg-zinc-100' : 'cursor-pointer hover:shadow-xs' }}"
                                title="{{ $inStock ? 'Tap to add Size ' . $size->code : 'Out of stock' }}"
                                {{ !$inStock ? 'disabled' : '' }}>
                            {{ $size->code }}
                        </button>
                    @endforeach
                </div>
                <button type="button" 
                        @click="addToCart()" 
                        :disabled="isAdding"
                        class="w-full py-1.5 bg-emerald-800 hover:bg-emerald-900 text-[#DFCAAB] text-[10px] font-bold uppercase tracking-wider rounded-lg transition-colors flex items-center justify-center space-x-1 shadow-sm active:scale-95 disabled:opacity-50">
                    <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span x-text="isAdding ? 'Adding to Cart...' : 'Add to Cart'"></span>
                </button>
            </div>

        </div>
    </div>
</div>
