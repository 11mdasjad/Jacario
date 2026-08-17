@props(['product'])

@php
    $primaryImage = $product->primaryImage;
    $detailImage = $product->images->firstWhere('sort_order', 2) ?: $primaryImage;
    $colors = $product->availableColors();
    $sizes = $product->availableSizes();
    $firstVariant = $product->activeVariants()->where('stock_quantity', '>', 0)->first();
    $isWishlisted = Auth::check() && Auth::user()->wishlists()->where('product_id', $product->id)->exists();
@endphp

<div class="group relative flex flex-col bg-white rounded-xl border border-zinc-200/80 overflow-hidden hover:shadow-xl hover:border-zinc-300 transition-all duration-300"
     x-data="{ 
        selectedColorImg: '{{ $primaryImage ? $primaryImage->url : asset('images/placeholder-polo.svg') }}',
        quickAddOpen: false,
        selectedSizeId: '{{ $sizes->first() ? $sizes->first()->id : '' }}',
        variants: @js($product->activeVariants->map(fn($v) => ['id' => $v->id, 'size_id' => $v->size_id, 'color_id' => $v->color_id, 'stock' => $v->stock_quantity])),
        getSelectedVariantId() {
            const v = this.variants.find(item => item.size_id == this.selectedSizeId && item.stock > 0);
            return v ? v.id : '{{ $firstVariant ? $firstVariant->id : 0 }}';
        }
     }">
    
    <!-- Badges Container -->
    <div class="absolute top-3 left-3 z-10 flex flex-col space-y-1">
        @if($product->has_discount)
            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-[#0B0D10] text-[#DFCAAB] border border-zinc-700">
                -{{ $product->discount_percent }}%
            </span>
        @endif

        @if($product->is_bestseller)
            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-[#C5A880] text-zinc-950">
                Best Seller
            </span>
        @elseif($product->is_new_arrival)
            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-zinc-900 text-white">
                New
            </span>
        @endif
    </div>

    <!-- Wishlist Button -->
    <button type="button" 
            @click.prevent="$store.wishlist.toggle({{ $product->id }}, $el)" 
            class="absolute top-3 right-3 z-10 w-8 h-8 rounded-full bg-white/90 backdrop-blur-md shadow-sm border border-zinc-100 flex items-center justify-center text-zinc-500 hover:text-rose-600 transition-colors {{ $isWishlisted ? 'text-rose-600' : '' }}" 
            aria-label="Add to Wishlist">
        <svg class="w-4 h-4 {{ $isWishlisted ? 'fill-rose-600' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
        </svg>
    </button>

    <!-- Product Image Anchor with Hover Switch -->
    <a href="{{ route('products.show', $product->slug) }}" class="relative block aspect-[4/5] bg-zinc-100 overflow-hidden">
        <img :src="selectedColorImg" 
             alt="{{ $product->name }}" 
             loading="lazy"
             class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
    </a>

    <!-- Quick Add Size Drawer on Hover -->
    <div class="px-4 pt-2 pb-1 flex items-center justify-between text-xs">
        <!-- Color Swatches -->
        <div class="flex items-center space-x-1.5 overflow-hidden">
            @foreach($colors->take(5) as $color)
                @php
                    $colorImg = $product->images->firstWhere('color_id', $color->id);
                    $imgUrl = $colorImg ? $colorImg->url : ($primaryImage ? $primaryImage->url : '');
                @endphp
                <button type="button" 
                        @mouseenter="selectedColorImg = '{{ $imgUrl }}'" 
                        class="w-4 h-4 rounded-full border border-zinc-300 hover:scale-125 transition-transform focus:outline-none"
                        style="background-color: {{ $color->hex_code }}"
                        title="{{ $color->name }}">
                </button>
            @endforeach
            @if($colors->count() > 5)
                <span class="text-[10px] font-medium text-zinc-400">+{{ $colors->count() - 5 }}</span>
            @endif
        </div>

        <!-- Rating Stars -->
        <div class="flex items-center space-x-1 text-amber-500 text-[11px] font-semibold">
            <svg class="w-3.5 h-3.5 fill-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            <span class="text-zinc-700">{{ number_format($product->average_rating, 1) }}</span>
            <span class="text-zinc-400 text-[10px]">({{ $product->reviews_count }})</span>
        </div>
    </div>

    <!-- Product Info -->
    <div class="p-4 pt-1 flex-1 flex flex-col justify-between space-y-3">
        <div>
            <p class="text-[11px] font-medium text-zinc-500 uppercase tracking-wider mb-1">{{ $product->category->name }}</p>
            <h3 class="text-sm font-semibold text-zinc-900 group-hover:text-black transition-colors line-clamp-1">
                <a href="{{ route('products.show', $product->slug) }}">
                    {{ $product->name }}
                </a>
            </h3>
            <p class="text-xs text-zinc-500 line-clamp-1 mt-0.5">{{ $product->fabric }}</p>
        </div>

        <div>
            <!-- Price Block -->
            <div class="flex items-baseline space-x-2 pt-1">
                @if($product->has_discount)
                    <span class="text-sm font-bold text-zinc-950">₹{{ number_format($product->sale_price) }}</span>
                    <span class="text-xs text-zinc-400 line-through">₹{{ number_format($product->base_price) }}</span>
                @else
                    <span class="text-sm font-bold text-zinc-950">₹{{ number_format($product->base_price) }}</span>
                @endif
            </div>

            <!-- Quick Add Button with Size Dropdown -->
            <div class="mt-3" x-show="!quickAddOpen">
                <button type="button" 
                        @click="quickAddOpen = true" 
                        class="w-full py-2 bg-zinc-900 hover:bg-black text-[#DFCAAB] text-xs font-bold uppercase tracking-widest rounded-lg transition-colors flex items-center justify-center space-x-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Quick Add</span>
                </button>
            </div>

            <!-- Size Selector when Quick Add Clicked -->
            <div class="mt-3 p-2 bg-zinc-50 rounded-lg border border-zinc-200" x-show="quickAddOpen" x-transition>
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-500">Select Size:</span>
                    <button type="button" @click="quickAddOpen = false" class="text-zinc-400 hover:text-black text-xs">✕</button>
                </div>
                <div class="grid grid-cols-5 gap-1 mb-2">
                    @foreach($sizes as $size)
                        @php
                            $sizeVariant = $product->activeVariants()->where('size_id', $size->id)->first();
                            $inStock = $sizeVariant && $sizeVariant->stock_quantity > 0;
                        @endphp
                        <button type="button" 
                                @click="selectedSizeId = '{{ $size->id }}'"
                                :class="selectedSizeId == '{{ $size->id }}' ? 'bg-black text-white border-black' : 'bg-white text-zinc-800 border-zinc-200 hover:border-black'"
                                class="py-1 text-[11px] font-bold rounded border transition-colors {{ !$inStock ? 'opacity-40 cursor-not-allowed line-through' : '' }}"
                                {{ !$inStock ? 'disabled' : '' }}>
                            {{ $size->code }}
                        </button>
                    @endforeach
                </div>
                <button type="button" 
                        @click="$store.cartDrawer.addItem(getSelectedVariantId(), 1); quickAddOpen = false" 
                        class="w-full py-1.5 bg-[#0B0D10] text-[#DFCAAB] text-[11px] font-bold uppercase tracking-wider rounded transition-colors hover:bg-black">
                    Add to Bag
                </button>
            </div>

        </div>
    </div>
</div>
