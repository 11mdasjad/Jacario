@props(['product'])

@php
    $primaryImage = $product->primaryImage;
    $detailImage = $product->images->firstWhere('sort_order', 2) ?: $primaryImage;
    $colors = $product->availableColors();
    $sizes = $product->availableSizes();
    $firstVariant = $product->activeVariants()->where('stock_quantity', '>', 0)->first();
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
        'reviews_count' => $product->reviews_count,
        'description' => $product->short_description ?: $product->fabric,
        'sizes' => $sizes->map(fn($s) => ['id' => $s->id, 'code' => $s->code]),
        'colors' => $colors->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'hex_code' => $c->hex_code]),
        'variants' => $product->activeVariants->map(fn($v) => ['id' => $v->id, 'size_id' => $v->size_id, 'color_id' => $v->color_id, 'stock' => $v->stock_quantity]),
    ];
@endphp

<div class="group relative flex flex-col bg-white rounded-2xl border border-zinc-200/80 overflow-hidden hover:shadow-xl hover:border-zinc-300 transition-all duration-300"
     x-data="{ 
        selectedColorImg: '{{ $primaryImage ? $primaryImage->url : asset('images/placeholder-polo.svg') }}',
        quickAddOpen: false,
        selectedSizeId: '{{ $sizes->first() ? $sizes->first()->id : '' }}',
        variants: @js($product->activeVariants->map(fn($v) => ['id' => $v->id, 'size_id' => $v->size_id, 'color_id' => $v->color_id, 'stock' => $v->stock_quantity])),
        getSelectedVariantId() {
            const v = this.variants.find(item => item.size_id == this.selectedSizeId && item.stock > 0);
            if (v && v.id) return v.id;
            const anyAvailable = this.variants.find(item => item.stock > 0);
            return anyAvailable ? anyAvailable.id : {{ $firstVariant ? $firstVariant->id : 0 }};
        },
        addToCart() {
            const varId = this.getSelectedVariantId();
            if (!varId || varId <= 0) {
                window.toast('This silhouette is currently out of stock.', 'error');
                return;
            }
            $store.cartDrawer.addItem(varId, 1);
            this.quickAddOpen = false;
        },
        openQuickView() {
            window.dispatchEvent(new CustomEvent('open-quick-view', { detail: @js($quickViewData) }));
        }
     }">
    
    <!-- Image Stage -->
    <div class="relative block aspect-[4/5] bg-zinc-100 overflow-hidden">
        
        <!-- Badges Container -->
        <div class="absolute top-2.5 left-2.5 z-10 flex flex-col space-y-1">
            @if($product->has_discount)
                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] sm:text-[10px] font-bold uppercase tracking-wider bg-[#0B0D10] text-[#DFCAAB] border border-zinc-700 shadow-xs">
                    {{ $product->discount_percent }}% OFF
                </span>
            @endif

            @if($product->is_bestseller)
                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] sm:text-[10px] font-bold uppercase tracking-wider bg-[#C5A880] text-zinc-950 shadow-xs">
                    Bestseller
                </span>
            @elseif($product->is_new_arrival)
                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] sm:text-[10px] font-bold uppercase tracking-wider bg-zinc-900 text-white shadow-xs">
                    New
                </span>
            @endif
        </div>

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

        <!-- Desktop Hover Quick View Overlay Button -->
        <button type="button" 
                @click.prevent="openQuickView()" 
                class="hidden md:flex absolute inset-x-3 bottom-3 py-2 bg-white/95 backdrop-blur-md text-zinc-950 text-[11px] font-bold uppercase tracking-widest rounded-xl items-center justify-center space-x-1.5 opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 transition-all duration-300 shadow-md hover:bg-black hover:text-white z-10">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            <span>Quick View</span>
        </button>

        <!-- Myntra-Style Floating Rating Pill on Image -->
        <div class="absolute bottom-2 left-2 z-10 inline-flex items-center space-x-1 px-1.5 py-0.5 rounded-md bg-white/90 backdrop-blur-xs border border-zinc-200/80 shadow-xs text-[10px] font-bold text-zinc-800">
            <span>{{ number_format($product->average_rating, 1) }}</span>
            <svg class="w-2.5 h-2.5 text-amber-500 fill-amber-500 inline-block" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            <span class="text-zinc-400">|</span>
            <span class="text-zinc-500 font-medium text-[9px]">{{ $product->reviews_count }}</span>
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

            <!-- Quick Add Button with Size Dropdown -->
            <div class="mt-2 sm:mt-3" x-show="!quickAddOpen">
                <button type="button" 
                        @click="quickAddOpen = true" 
                        class="w-full py-1.5 sm:py-2 bg-zinc-900 hover:bg-black text-[#DFCAAB] text-[10px] sm:text-xs font-bold uppercase tracking-wider rounded-lg transition-colors flex items-center justify-center space-x-1 active:scale-95">
                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Quick Add</span>
                </button>
            </div>

            <!-- Size Selector when Quick Add Clicked -->
            <div class="mt-2 p-1.5 sm:p-2 bg-zinc-50 rounded-lg border border-zinc-200" x-show="quickAddOpen" x-transition>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-zinc-500">Select Size:</span>
                    <button type="button" @click="quickAddOpen = false" class="text-zinc-400 hover:text-black text-xs">✕</button>
                </div>
                <div class="grid grid-cols-5 gap-1 mb-1.5">
                    @foreach($sizes as $size)
                        @php
                            $sizeVariant = $product->activeVariants()->where('size_id', $size->id)->first();
                            $inStock = $sizeVariant && $sizeVariant->stock_quantity > 0;
                        @endphp
                        <button type="button" 
                                @click="selectedSizeId = '{{ $size->id }}'"
                                :class="selectedSizeId == '{{ $size->id }}' ? 'bg-black text-white border-black' : 'bg-white text-zinc-800 border-zinc-200 hover:border-black'"
                                class="py-1 text-[10px] font-bold rounded border transition-colors {{ !$inStock ? 'opacity-40 cursor-not-allowed line-through' : '' }}"
                                {{ !$inStock ? 'disabled' : '' }}>
                            {{ $size->code }}
                        </button>
                    @endforeach
                </div>
                <button type="button" 
                        @click="addToCart()" 
                        class="w-full py-1.5 bg-[#0B0D10] text-[#DFCAAB] text-[10px] font-bold uppercase tracking-wider rounded-lg transition-colors hover:bg-black flex items-center justify-center space-x-1 shadow-sm active:scale-95">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span>Add to Bag</span>
                </button>
            </div>

        </div>
    </div>
</div>
