@extends('layouts.app')

@section('title', "{$product->name} | JACARIO Luxury Polo T-Shirts")
@section('meta_description', $product->short_description ?? "Buy {$product->name} at JACARIO. Crafted with {$product->fabric}, {$product->collar_type}, and timeless tailored aesthetics.")

@push('schema')
    <script type="application/ld+json">
        {!! json_encode($schemaData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
@endpush

@section('content')

@php
    $primaryImg = $product->primaryImage;
    $colors = $product->availableColors();
    $sizes = $product->availableSizes();
    $defaultColor = $colors->first();
    $defaultSize = $sizes->first();
    $isWishlisted = Auth::check() && Auth::user()->wishlists()->where('product_id', $product->id)->exists();
@endphp

<div class="bg-white" 
     x-data="{
        activeImg: '{{ $primaryImg ? $primaryImg->url : asset('images/placeholder-polo.svg') }}',
        activeImgIndex: 1,
        selectedColorId: {{ $defaultColor ? $defaultColor->id : 0 }},
        selectedColorName: '{{ $defaultColor ? $defaultColor->name : '' }}',
        selectedSizeId: {{ $defaultSize ? $defaultSize->id : 0 }},
        selectedSizeName: '{{ $defaultSize ? $defaultSize->name : '' }}',
        quantity: 1,
        sizeGuideOpen: false,
        pincode: '',
        pincodeResult: null,
        pincodeLoading: false,
        variants: @js($variantsMatrix),
        
        get currentVariant() {
            return this.variants.find(v => v.color_id == this.selectedColorId && v.size_id == this.selectedSizeId) || null;
        },

        get isInStock() {
            return this.currentVariant ? this.currentVariant.stock > 0 : false;
        },

        get stockQuantity() {
            return this.currentVariant ? this.currentVariant.stock : 0;
        },

        selectColor(color) {
            this.selectedColorId = color.id;
            this.selectedColorName = color.name;
            // Update active image to this color
            const matchImg = '{{ asset('images/polos') }}/' + color.slug.replace('obsidian-', '').replace('pure-', '').replace('royal-', '').replace('olive-dusk', 'olive').replace('heather-grey', 'grey').replace('burgundy-royale', 'burgundy').replace('desert-sand', 'sand').replace('forest-emerald', 'forest').replace('slate-charcoal', 'charcoal').replace('sapphire-blue', 'royal-blue') + '-polo.svg';
            this.activeImg = matchImg;
        },

        checkPincode() {
            if (!this.pincode || this.pincode.length !== 6) return;
            this.pincodeLoading = true;
            fetch('{{ route('products.check-pincode') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ pincode: this.pincode })
            })
            .then(res => res.json())
            .then(data => {
                this.pincodeLoading = false;
                this.pincodeResult = data;
            })
            .catch(() => { this.pincodeLoading = false; });
        }
     }">

    <!-- Breadcrumbs (Desktop) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 sm:pt-6">
        <nav class="flex items-center space-x-2 text-[11px] sm:text-xs text-zinc-500">
            <a href="{{ route('home') }}" class="hover:text-black transition-colors">Home</a>
            <span>/</span>
            <a href="{{ route('shop.index') }}" class="hover:text-black transition-colors">Polos</a>
            <span>/</span>
            <a href="{{ route('shop.index', ['category' => $product->category->slug]) }}" class="hover:text-black transition-colors">{{ $product->category->name }}</a>
            <span>/</span>
            <span class="text-zinc-900 font-semibold truncate max-w-[150px] sm:max-w-none">{{ $product->name }}</span>
        </nav>
    </div>

    <!-- Main Product Layout Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8 lg:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-12">
            
            <!-- Left: Gallery & Zoom Showcase (7 Cols) -->
            <div class="lg:col-span-7 space-y-3 sm:space-y-4">
                <!-- Large Stage Image View -->
                <div class="relative aspect-[4/5] bg-zinc-100 rounded-2xl sm:rounded-3xl border border-zinc-200 overflow-hidden flex items-center justify-center">
                    
                    <!-- Badges -->
                    <div class="absolute top-3 left-3 sm:top-4 sm:left-4 z-10 flex flex-col space-y-1.5">
                        @if($product->has_discount)
                            <span class="px-2 py-0.5 sm:px-2.5 sm:py-1 rounded text-[10px] sm:text-[11px] font-bold uppercase tracking-wider bg-[#0B0D10] text-[#DFCAAB] border border-zinc-700 shadow-sm">
                                {{ $product->discount_percent }}% OFF
                            </span>
                        @endif
                        @if($product->is_bestseller)
                            <span class="px-2 py-0.5 sm:px-2.5 sm:py-1 rounded text-[10px] sm:text-[11px] font-bold uppercase tracking-wider bg-[#C5A880] text-zinc-950 shadow-sm">
                                Best Seller
                            </span>
                        @endif
                    </div>

                    <!-- Photo Index Badge (Myntra Style 1/4) -->
                    <div class="absolute bottom-3 right-3 z-10 px-2 py-0.5 rounded-full bg-black/60 backdrop-blur-xs text-white text-[10px] font-bold">
                        <span x-text="activeImgIndex"></span> / {{ max(1, $product->images->count()) }}
                    </div>

                    <!-- Main Real Photo Render -->
                    <img :src="activeImg" 
                         alt="{{ $product->name }}" 
                         class="w-full h-full object-cover filter transition-all duration-300 transform hover:scale-105 cursor-zoom-in">
                </div>

                <!-- Thumbnails Gallery Row (Horizontal Scroll on Mobile) -->
                <div class="flex sm:grid sm:grid-cols-4 gap-2.5 overflow-x-auto no-scrollbar pb-1">
                    @foreach($product->images as $index => $img)
                        <button type="button" 
                                @click="activeImg = '{{ $img->url }}'; activeImgIndex = {{ $index + 1 }}"
                                :class="activeImg === '{{ $img->url }}' ? 'border-zinc-950 ring-2 ring-zinc-950 ring-offset-2' : 'border-zinc-200 hover:border-zinc-400'"
                                class="w-16 h-20 sm:w-auto sm:aspect-square bg-zinc-100 rounded-xl border overflow-hidden transition-all focus:outline-none shrink-0">
                            <img src="{{ $img->url }}" alt="{{ $img->alt_text }}" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Right: Product Purchase Details & Selectors (5 Cols) -->
            <div class="lg:col-span-5 space-y-5 sm:space-y-6">
                
                <!-- Title & Brand -->
                <div>
                    <div class="flex items-center justify-between">
                        <p class="text-[11px] sm:text-xs font-bold uppercase tracking-[0.2em] text-[#8C6D46]">
                            JACARIO • {{ $product->category->name }}
                        </p>
                    </div>
                    <h1 class="text-xl sm:text-3xl font-serif-luxury font-bold text-zinc-900 tracking-tight leading-snug mt-1">
                        {{ $product->name }}
                    </h1>
                    <p class="text-[11px] text-zinc-400 font-mono mt-0.5">SKU: <span x-text="currentVariant ? currentVariant.sku : '{{ $product->sku }}'"></span></p>
                </div>

                <!-- Myntra-Style Ratings Pill Header -->
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-lg bg-zinc-50 border border-zinc-200">
                    <div class="flex items-center space-x-1 text-amber-500 font-bold text-xs">
                        <span>{{ number_format($product->average_rating, 1) }}</span>
                        <svg class="w-3.5 h-3.5 fill-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                    <span class="text-zinc-300">|</span>
                    <a href="#reviews-section" class="text-xs font-semibold text-zinc-600 hover:text-black">
                        {{ $product->reviews_count }} Ratings & Reviews
                    </a>
                </div>

                <!-- Price Block -->
                <div class="flex items-baseline space-x-3">
                    @if($product->has_discount)
                        <span class="text-3xl font-bold text-zinc-950">₹{{ number_format($product->sale_price) }}</span>
                        <span class="text-base text-zinc-400 line-through">₹{{ number_format($product->base_price) }}</span>
                        <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                            Save ₹{{ number_format($product->base_price - $product->sale_price) }} ({{ $product->discount_percent }}% OFF)
                        </span>
                    @else
                        <span class="text-3xl font-bold text-zinc-950">₹{{ number_format($product->base_price) }}</span>
                    @endif
                </div>
                <p class="text-[11px] text-zinc-500">Inclusive of all duties & taxes. Complimentary express courier dispatch.</p>

                <!-- Color Selection -->
                <div class="space-y-2 pt-2">
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-bold uppercase tracking-wider text-zinc-800">
                            Color: <span class="font-semibold text-zinc-600" x-text="selectedColorName"></span>
                        </span>
                    </div>
                    <div class="flex items-center space-x-2.5">
                        @foreach($colors as $col)
                            <button type="button" 
                                    @click="selectColor(@js($col))"
                                    :class="selectedColorId == {{ $col->id }} ? 'ring-2 ring-zinc-950 ring-offset-2 scale-110' : 'border-zinc-300 hover:scale-105'"
                                    class="w-7 h-7 rounded-full border transition-all focus:outline-none"
                                    style="background-color: {{ $col->hex_code }}"
                                    title="{{ $col->name }}">
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Size Selection & Size Guide Modal Trigger -->
                <div class="space-y-2 pt-2">
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-bold uppercase tracking-wider text-zinc-800">
                            Size: <span class="font-semibold text-zinc-600" x-text="selectedSizeName"></span>
                        </span>
                        <button type="button" @click="sizeGuideOpen = true" class="text-xs font-semibold text-[#A4845B] hover:underline flex items-center space-x-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                            <span>Size Guide</span>
                        </button>
                    </div>

                    <div class="grid grid-cols-5 gap-2">
                        @foreach($sizes as $size)
                            <button type="button" 
                                    @click="selectedSizeId = {{ $size->id }}; selectedSizeName = '{{ $size->name }}'"
                                    :class="selectedSizeId == {{ $size->id }} ? 'bg-zinc-950 text-white border-zinc-950 shadow-sm' : 'bg-white text-zinc-800 border-zinc-200 hover:border-black'"
                                    class="py-3 text-xs font-bold rounded-lg border transition-all uppercase focus:outline-none">
                                {{ $size->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Real-time Stock Indicator -->
                <div class="pt-1">
                    <template x-if="isInStock">
                        <div class="flex items-center space-x-2 text-xs">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span class="font-medium text-emerald-800" x-show="stockQuantity > 5">In Stock — Ready for Express Dispatch</span>
                            <span class="font-bold text-amber-700" x-show="stockQuantity <= 5" x-text="'Only ' + stockQuantity + ' units remaining in this size/color!'"></span>
                        </div>
                    </template>
                    <template x-if="!isInStock">
                        <div class="flex items-center space-x-2 text-xs font-bold text-rose-700">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                            <span>Currently Out of Stock in this combination</span>
                        </div>
                    </template>
                </div>

                <!-- Quantity & Actions (Add to Bag / Wishlist) -->
                <div class="space-y-3 pt-2">
                    <div class="flex items-center space-x-3">
                        
                        <!-- Quantity Counter -->
                        <div class="flex items-center border border-zinc-200 rounded-lg bg-zinc-50 h-12 px-2">
                            <button type="button" @click="if (quantity > 1) quantity--" class="w-8 h-full text-zinc-600 hover:text-black font-bold focus:outline-none">-</button>
                            <span class="w-8 text-center text-xs font-bold text-zinc-900" x-text="quantity"></span>
                            <button type="button" @click="if (quantity < stockQuantity) quantity++" class="w-8 h-full text-zinc-600 hover:text-black font-bold focus:outline-none">+</button>
                        </div>

                        <!-- Add to Bag CTA Button -->
                        <button type="button" 
                                @click="if (isInStock) $store.cartDrawer.addItem(currentVariant.id, quantity)"
                                :disabled="!isInStock"
                                class="flex-1 h-12 bg-[#0B0D10] hover:bg-black text-[#DFCAAB] disabled:bg-zinc-300 disabled:text-zinc-500 text-xs font-bold uppercase tracking-[0.2em] rounded-lg transition-colors shadow-lg flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            <span x-text="isInStock ? 'Add to Shopping Bag' : 'Out of Stock'"></span>
                        </button>

                        <!-- Wishlist Toggle -->
                        <button type="button" 
                                @click="$store.wishlist.toggle({{ $product->id }}, $el)"
                                class="w-12 h-12 rounded-lg border border-zinc-200 hover:border-black flex items-center justify-center text-zinc-600 hover:text-rose-600 transition-colors focus:outline-none" 
                                aria-label="Save to Wishlist">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </button>
                    </div>

                    <!-- Direct Checkout Button -->
                    <button type="button" 
                            @click="if (isInStock) { $store.cartDrawer.addItem(currentVariant.id, quantity); setTimeout(() => window.location.href = '{{ route('checkout.index') }}', 400); }"
                            :disabled="!isInStock"
                            class="w-full py-3.5 bg-[#C5A880] hover:bg-[#DFCAAB] text-zinc-950 disabled:hidden text-xs font-bold uppercase tracking-[0.2em] rounded-lg transition-colors shadow-md">
                        Buy It Now
                    </button>
                </div>

                <!-- Delivery Availability Checker -->
                <div class="p-4 rounded-xl bg-zinc-50 border border-zinc-200/80 space-y-2">
                    <div class="flex items-center space-x-2 text-xs font-bold uppercase tracking-wider text-zinc-800">
                        <svg class="w-4 h-4 text-[#A4845B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Check Delivery Availability</span>
                    </div>
                    <div class="flex space-x-2">
                        <input type="text" 
                               x-model="pincode" 
                               placeholder="Enter 6-digit Pincode (e.g. 400050)" 
                               maxlength="6"
                               class="flex-1 text-xs bg-white border border-zinc-200 rounded-lg px-3 py-2 focus:outline-none focus:border-black">
                        <button type="button" 
                                @click="checkPincode()" 
                                class="px-4 py-2 bg-zinc-900 text-white text-xs font-bold uppercase tracking-wider rounded-lg hover:bg-black transition-colors"
                                :disabled="pincodeLoading">
                            <span x-text="pincodeLoading ? 'Checking...' : 'Check'"></span>
                        </button>
                    </div>

                    <div x-show="pincodeResult" x-transition class="pt-2 text-xs">
                        <p class="font-semibold text-emerald-800 flex items-center space-x-1" x-show="pincodeResult?.available">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span x-text="pincodeResult?.message"></span>
                        </p>
                    </div>
                </div>

                <!-- Product Specifications Grid -->
                <div class="pt-4 border-t border-zinc-200 space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-widest text-zinc-900">Sartorial Specifications</h3>
                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div class="p-2.5 bg-zinc-50 rounded-lg border border-zinc-100">
                            <span class="text-zinc-400 block text-[10px] uppercase">Fabric Composition</span>
                            <span class="font-semibold text-zinc-800">{{ $product->fabric }}</span>
                        </div>
                        <div class="p-2.5 bg-zinc-50 rounded-lg border border-zinc-100">
                            <span class="text-zinc-400 block text-[10px] uppercase">Tailored Fit</span>
                            <span class="font-semibold text-zinc-800">{{ $product->fit }}</span>
                        </div>
                        <div class="p-2.5 bg-zinc-50 rounded-lg border border-zinc-100">
                            <span class="text-zinc-400 block text-[10px] uppercase">Collar Construction</span>
                            <span class="font-semibold text-zinc-800">{{ $product->collar_type }}</span>
                        </div>
                        <div class="p-2.5 bg-zinc-50 rounded-lg border border-zinc-100">
                            <span class="text-zinc-400 block text-[10px] uppercase">Sleeve Finish</span>
                            <span class="font-semibold text-zinc-800">{{ $product->sleeve_type }}</span>
                        </div>
                        <div class="p-2.5 bg-zinc-50 rounded-lg border border-zinc-100">
                            <span class="text-zinc-400 block text-[10px] uppercase">Care Instructions</span>
                            <span class="font-semibold text-zinc-800">{{ $product->wash_care }}</span>
                        </div>
                        <div class="p-2.5 bg-zinc-50 rounded-lg border border-zinc-100">
                            <span class="text-zinc-400 block text-[10px] uppercase">Country of Origin</span>
                            <span class="font-semibold text-zinc-800">{{ $product->country_of_origin }}</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- Detailed Editorial Description -->
        <div class="mt-16 pt-12 border-t border-zinc-200">
            <div class="max-w-3xl mx-auto space-y-6">
                <h3 class="text-2xl font-serif-luxury font-bold text-zinc-900 text-center">
                    The Making of {{ $product->name }}
                </h3>
                <div class="prose prose-zinc max-w-none text-zinc-600 font-light leading-relaxed whitespace-pre-line text-sm sm:text-base">
                    {{ $product->description }}
                </div>
            </div>
        </div>

        <!-- Customer Reviews Section -->
        <div id="reviews-section" class="mt-20 pt-12 border-t border-zinc-200">
            <div class="max-w-4xl mx-auto space-y-10">
                
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-zinc-200 gap-4">
                    <div>
                        <h3 class="text-2xl font-serif-luxury font-bold text-zinc-900">Client Reviews</h3>
                        <p class="text-xs text-zinc-500 mt-1">Authentic ratings from verified purchasers</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="text-right">
                            <p class="text-2xl font-serif-luxury font-bold text-zinc-900">{{ number_format($product->average_rating, 1) }}</p>
                            <p class="text-[10px] text-zinc-400">out of 5 stars</p>
                        </div>
                        <div class="flex items-center space-x-1 text-amber-500">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= round($product->average_rating) ? 'fill-amber-400' : 'fill-zinc-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- Review Submission Box (If purchased or logged in) -->
                @auth
                    @if($canReview)
                        <div class="p-6 rounded-2xl bg-zinc-50 border border-zinc-200 space-y-4" x-data="{ rating: 5 }">
                            <h4 class="text-sm font-bold uppercase tracking-wider text-zinc-900">Write a Review for this Polo</h4>
                            
                            <form method="POST" action="{{ route('products.reviews.store', $product->slug) }}" class="space-y-4">
                                @csrf
                                
                                <div>
                                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Your Rating:</label>
                                    <div class="flex items-center space-x-2">
                                        <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                                            <button type="button" @click="rating = star" class="focus:outline-none">
                                                <svg class="w-6 h-6 transition-colors" :class="star <= rating ? 'text-amber-400 fill-amber-400' : 'text-zinc-300'" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            </button>
                                        </template>
                                        <input type="hidden" name="rating" :value="rating">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Review Headline</label>
                                    <input type="text" name="title" required placeholder="e.g. Flawless collar drape and fit" class="w-full text-xs bg-white border border-zinc-300 rounded-lg p-2.5 focus:outline-none focus:border-black">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Detailed Review</label>
                                    <textarea name="comment" rows="3" required placeholder="Describe the handfeel, wash durability, collar structure, and fit..." class="w-full text-xs bg-white border border-zinc-300 rounded-lg p-2.5 focus:outline-none focus:border-black"></textarea>
                                </div>

                                <button type="submit" class="px-6 py-2.5 bg-zinc-900 text-white rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-black transition-colors">
                                    Submit Review
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth

                <!-- Reviews Listing -->
                <div class="space-y-6">
                    @forelse($product->approvedReviews as $review)
                        <div class="pb-6 border-b border-zinc-100 last:border-0">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-2">
                                    <span class="font-bold text-xs text-zinc-900">{{ $review->user->name }}</span>
                                    @if($review->is_verified_purchase)
                                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                            Verified Buyer
                                        </span>
                                    @endif
                                </div>
                                <span class="text-[11px] text-zinc-400">{{ $review->created_at->format('M j, Y') }}</span>
                            </div>

                            <div class="flex items-center space-x-1 text-amber-500 mb-1.5">
                                @for($i = 0; $i < $review->rating; $i++)
                                    <svg class="w-3.5 h-3.5 fill-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>

                            <h5 class="text-xs font-bold text-zinc-900 font-serif-luxury">{{ $review->title }}</h5>
                            <p class="text-xs text-zinc-600 font-light leading-relaxed mt-1">{{ $review->comment }}</p>
                        </div>
                    @empty
                        <div class="text-center py-8 text-zinc-400 text-xs">
                            No reviews yet for this Polo T-Shirt. Be the first to share your impression.
                        </div>
                    @endforelse
                </div>

            </div>
        </div>

        <!-- Related Polo T-Shirts -->
        @if($relatedProducts->isNotEmpty())
            <div class="mt-24 pt-12 border-t border-zinc-200">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#A4845B]">Complete Your Collection</p>
                        <h3 class="text-2xl font-serif-luxury font-bold text-zinc-900 mt-1">You May Also Admire</h3>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $relProduct)
                        <x-product-card :product="$relProduct" />
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    <!-- Size Guide Modal -->
    <div x-show="sizeGuideOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;"
         @keydown.window.escape="sizeGuideOpen = false">
        
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="sizeGuideOpen = false"></div>

        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative max-w-2xl w-full bg-white rounded-2xl shadow-2xl p-6 sm:p-8 overflow-hidden border border-zinc-200" @click.stop>
                
                <div class="flex items-center justify-between pb-4 border-b border-zinc-100">
                    <div>
                        <h3 class="text-lg font-serif-luxury font-bold text-zinc-900">JACARIO Size & Measurement Guide</h3>
                        <p class="text-xs text-zinc-500">All measurements are tailored to European standards</p>
                    </div>
                    <button type="button" @click="sizeGuideOpen = false" class="text-zinc-400 hover:text-black">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="mt-6 overflow-x-auto">
                    <table class="w-full text-xs text-left border-collapse">
                        <thead>
                            <tr class="bg-zinc-100 border-b border-zinc-200 text-zinc-700 font-bold uppercase tracking-wider">
                                <th class="p-3">Size</th>
                                <th class="p-3">Chest Circumference</th>
                                <th class="p-3">Back Length</th>
                                <th class="p-3">Shoulder Width</th>
                                <th class="p-3">Sleeve Length</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100">
                            @php
                                $chart = $product->size_chart['rows'] ?? [
                                    ['S', '38" / 96 cm', '27.5" / 70 cm', '17.0" / 43 cm', '8.2" / 21 cm'],
                                    ['M', '40" / 102 cm', '28.5" / 72 cm', '17.7" / 45 cm', '8.6" / 22 cm'],
                                    ['L', '42" / 107 cm', '29.5" / 75 cm', '18.5" / 47 cm', '9.0" / 23 cm'],
                                    ['XL', '44" / 112 cm', '30.5" / 77 cm', '19.3" / 49 cm', '9.4" / 24 cm'],
                                    ['XXL', '46" / 117 cm', '31.5" / 80 cm', '20.1" / 51 cm', '9.8" / 25 cm'],
                                ];
                            @endphp
                            @foreach($chart as $row)
                                <tr class="hover:bg-zinc-50">
                                    <td class="p-3 font-bold text-zinc-900">{{ $row[0] }}</td>
                                    <td class="p-3 text-zinc-600">{{ $row[1] }}</td>
                                    <td class="p-3 text-zinc-600">{{ $row[2] }}</td>
                                    <td class="p-3 text-zinc-600">{{ $row[3] }}</td>
                                    <td class="p-3 text-zinc-600">{{ $row[4] ?? '9.0"' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 p-4 rounded-xl bg-zinc-50 border border-zinc-200 text-xs text-zinc-600 space-y-1">
                    <p class="font-bold text-zinc-900">How to Measure:</p>
                    <p>• <strong>Chest</strong>: Measure around the fullest part of your chest, keeping the tape horizontal.</p>
                    <p>• <strong>Length</strong>: Measure from the highest point of the shoulder seam straight down to the hem.</p>
                    <p>• <strong>Fit Advice</strong>: If between sizes, choose the larger size for a relaxed drape, or the smaller size for a sculpted silhouette.</p>
                </div>

            </div>
        </div>
    </div>

    <!-- Myntra-Style Mobile Sticky Bottom Action Bar (WISHLIST + ADD TO BAG) -->
    <div class="fixed bottom-0 inset-x-0 bg-white border-t border-zinc-200 px-3 py-2.5 z-40 lg:hidden shadow-[0_-4px_20px_rgba(0,0,0,0.12)] flex items-center space-x-2.5 pb-safe">
        
        <!-- 1. Wishlist Button -->
        <button type="button" 
                @click="$store.wishlist.toggle({{ $product->id }}, $el)"
                class="w-1/3 py-3 border border-zinc-300 rounded-xl text-xs font-bold uppercase tracking-wider text-zinc-800 flex items-center justify-center space-x-1.5 active:bg-zinc-100 transition-colors">
            <svg class="w-4 h-4 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            <span>Wishlist</span>
        </button>

        <!-- 2. Add to Bag Button -->
        <button type="button" 
                @click="if (isInStock && currentVariant) $store.cartDrawer.addItem(currentVariant.id, quantity)"
                :disabled="!isInStock || !currentVariant"
                class="w-2/3 py-3 bg-[#0B0D10] text-[#DFCAAB] disabled:bg-zinc-300 disabled:text-zinc-500 text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-md flex items-center justify-center space-x-2 active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            <span x-text="isInStock ? 'Add to Bag' : 'Out of Stock'"></span>
        </button>

    </div>

</div>

@endsection
