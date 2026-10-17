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
    $inStockVariants = $product->activeVariants->where('stock_quantity', '>', 0);
    $firstInStock = $inStockVariants->first() ?? $product->activeVariants->first();
    $defaultColor = $firstInStock ? $firstInStock->color : $colors->first();
    $defaultSize = $firstInStock ? $firstInStock->size : $sizes->first();
    $isWishlisted = Auth::check() && Auth::user()->wishlists()->where('product_id', $product->id)->exists();
@endphp

<div class="bg-white" 
     x-data="{
        activeImg: '{{ $primaryImg ? $primaryImg->url : asset('images/placeholder-polo.svg') }}',
        activeImgIndex: 1,
        selectedColorId: {{ $defaultColor ? $defaultColor->id : 0 }},
        selectedColorName: '{{ $defaultColor ? addslashes($defaultColor->name) : '' }}',
        selectedSizeId: {{ $defaultSize ? $defaultSize->id : 0 }},
        selectedSizeName: '{{ $defaultSize ? addslashes($defaultSize->name) : '' }}',
        quantity: 1,
        isAdding: false,
        sizeGuideOpen: false,
        activeProductTab: 'description',
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

        isSizeInStockForColor(sizeId) {
            const v = this.variants.find(item => item.size_id == sizeId && item.color_id == this.selectedColorId);
            return v ? v.stock > 0 : false;
        },

        selectColor(color) {
            this.selectedColorId = color.id;
            this.selectedColorName = color.name;
            // If current selected size is not in stock for this color, auto-switch to an in-stock size if one exists
            if (!this.isSizeInStockForColor(this.selectedSizeId)) {
                const inStockVar = this.variants.find(v => v.color_id == color.id && v.stock > 0);
                if (inStockVar) {
                    this.selectedSizeId = inStockVar.size_id;
                    this.selectedSizeName = inStockVar.size_name;
                }
            }
            if (this.stockQuantity > 0 && this.quantity > this.stockQuantity) {
                this.quantity = 1;
            }
            // Update active image to this color
            const matchImg = '{{ asset('images/polos') }}/' + color.slug.replace('obsidian-', '').replace('pure-', '').replace('royal-', '').replace('olive-dusk', 'olive').replace('heather-grey', 'grey').replace('burgundy-royale', 'burgundy').replace('desert-sand', 'sand').replace('forest-emerald', 'forest').replace('slate-charcoal', 'charcoal').replace('sapphire-blue', 'royal-blue') + '-polo.svg';
            this.activeImg = matchImg;
        },

        selectSize(size) {
            this.selectedSizeId = size.id;
            this.selectedSizeName = size.name;
            if (this.stockQuantity > 0 && this.quantity > this.stockQuantity) {
                this.quantity = 1;
            }
        },

        async handleAddToCart() {
            if (!this.currentVariant || !this.isInStock) {
                window.toast('Selected size / color is currently out of stock.', 'error');
                return;
            }
            this.isAdding = true;
            try {
                const drawer = this.$store?.cartDrawer || (window.Alpine && window.Alpine.store('cartDrawer')) || window.$store?.cartDrawer || window.cartDrawer;
                if (!drawer) {
                    window.toast('Cart system initializing, please try again.', 'error');
                    return;
                }
                await drawer.addItem(this.currentVariant.id, this.quantity);
            } catch (err) {
                console.error('Error adding to cart:', err);
                window.toast('Failed to add item to bag. Please try again.', 'error');
            } finally {
                this.isAdding = false;
            }
        },

        async handleBuyNow() {
            if (!this.currentVariant || !this.isInStock) {
                window.toast('Selected size / color is currently out of stock.', 'error');
                return;
            }
            this.isAdding = true;
            try {
                const drawer = this.$store?.cartDrawer || (window.Alpine && window.Alpine.store('cartDrawer')) || window.$store?.cartDrawer || window.cartDrawer;
                if (!drawer) {
                    window.toast('Cart system initializing, please try again.', 'error');
                    return;
                }
                const success = await drawer.addItem(this.currentVariant.id, this.quantity);
                if (success) {
                    window.location.href = '{{ route('checkout.index') }}';
                }
            } catch (err) {
                console.error('Error in buy now:', err);
                window.toast('Failed to proceed to checkout. Please try again.', 'error');
            } finally {
                this.isAdding = false;
            }
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
                <!-- Large Stage Image View with Interactive Magnifying Lens Zoom -->
                <div class="relative aspect-[4/5] bg-zinc-100 rounded-2xl sm:rounded-3xl border border-zinc-200 overflow-hidden flex items-center justify-center cursor-crosshair group select-none"
                     x-data="{
                        zooming: false,
                        zoomX: 50,
                        zoomY: 50,
                        handleMouseMove(e) {
                            const rect = e.currentTarget.getBoundingClientRect();
                            const x = ((e.clientX - rect.left) / rect.width) * 100;
                            const y = ((e.clientY - rect.top) / rect.height) * 100;
                            this.zoomX = Math.max(0, Math.min(100, x));
                            this.zoomY = Math.max(0, Math.min(100, y));
                        }
                     }"
                     @mouseenter="zooming = true"
                     @mouseleave="zooming = false"
                     @mousemove="handleMouseMove($event)">
                    
                    <!-- Badges -->
                    <div class="absolute top-3 left-3 sm:top-4 sm:left-4 z-10 flex flex-col space-y-1.5 pointer-events-none">
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

                    <!-- Photo Index Badge -->
                    <div class="absolute bottom-3 right-3 z-10 px-2 py-0.5 rounded-full bg-black/60 backdrop-blur-xs text-white text-[10px] font-bold pointer-events-none">
                        <span x-text="activeImgIndex"></span> / {{ max(1, $product->images->count()) }}
                    </div>

                    <!-- Zoom Prompt Icon -->
                    <div x-show="!zooming" class="absolute bottom-3 left-3 z-10 px-2.5 py-1 rounded-full bg-black/60 backdrop-blur-md text-white text-[10px] font-bold flex items-center space-x-1.5 pointer-events-none transition-opacity">
                        <svg class="w-3.5 h-3.5 text-[#DFCAAB]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                        <span>Hover to Magnify Knit</span>
                    </div>

                    <!-- Main Real Photo Render with Lens Zoom -->
                    <img :src="activeImg" 
                         alt="{{ $product->name }}" 
                         class="w-full h-full object-cover filter transition-transform duration-100 ease-out"
                         :style="zooming ? `transform: scale(2.2); transform-origin: ${zoomX}% ${zoomY}%;` : 'transform: scale(1);'">
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
                    <a href="#product-tabs-section" @click.prevent="activeProductTab = 'reviews'; document.getElementById('product-tabs-section').scrollIntoView({ behavior: 'smooth' })" class="text-xs font-semibold text-zinc-600 hover:text-black">
                        {{ $product->daily_views_count }} Ratings & Reviews
                    </a>
                </div>

                <!-- Price Block -->
                <div class="flex items-baseline flex-wrap gap-x-2 gap-y-1">
                    @if($product->has_discount)
                        <span class="text-2xl sm:text-3xl font-bold text-zinc-950">₹{{ number_format($product->sale_price) }}</span>
                        <span class="text-sm sm:text-base text-zinc-400 line-through">₹{{ number_format($product->base_price) }}</span>
                        <span class="text-[10px] sm:text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                            Save ₹{{ number_format($product->base_price - $product->sale_price) }} ({{ $product->discount_percent }}% OFF)
                        </span>
                    @else
                        <span class="text-2xl sm:text-3xl font-bold text-zinc-950">₹{{ number_format($product->base_price) }}</span>
                    @endif
                </div>
                <p class="text-[11px] text-zinc-500">Inclusive of all duties & taxes. Complimentary express courier dispatch.</p>

                @if($product->has_discount)
                    <!-- Flash Deal Urgency Count Down Timer -->
                    <div x-data="{
                            hours: '08',
                            minutes: '42',
                            seconds: '19',
                            dealTimer: null,
                            init() {
                                let target = new Date();
                                target.setHours(target.getHours() + 8);
                                target.setMinutes(target.getMinutes() + 42);
                                target.setSeconds(target.getSeconds() + 19);

                                this.dealTimer = setInterval(() => {
                                    const now = new Date().getTime();
                                    const diff = target.getTime() - now;
                                    if (diff <= 0) {
                                        clearInterval(this.dealTimer);
                                        return;
                                    }
                                    const h = Math.floor((diff / (1000 * 60 * 60)) % 24);
                                    const m = Math.floor((diff / 1000 / 60) % 60);
                                    const s = Math.floor((diff / 1000) % 60);
                                    this.hours = String(h).padStart(2, '0');
                                    this.minutes = String(m).padStart(2, '0');
                                    this.seconds = String(s).padStart(2, '0');
                                }, 1000);
                            }
                         }"
                         class="p-3 bg-gradient-to-r from-amber-500/10 via-amber-500/5 to-transparent rounded-xl border border-amber-300/70 flex items-center justify-between">
                        <div class="flex items-center space-x-2.5">
                            <div class="w-8 h-8 rounded-lg bg-[#0B0D10] text-[#DFCAAB] flex items-center justify-center shrink-0 shadow-xs">
                                <svg class="w-4 h-4 text-[#DFCAAB] animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <div class="flex items-center space-x-1.5">
                                    <span class="text-xs font-bold uppercase tracking-wider text-zinc-950">Special Deal Ends In</span>
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span>
                                </div>
                                <p class="text-[10px] text-zinc-500">Limited-time discounted promotional pricing</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-1 font-mono text-xs font-bold text-zinc-900">
                            <div class="flex flex-col items-center">
                                <span class="px-2 py-1 bg-[#0B0D10] text-[#DFCAAB] rounded border border-zinc-800 shadow-xs text-xs" x-text="hours">08</span>
                                <span class="text-[8px] text-zinc-500 font-sans mt-0.5 uppercase tracking-tighter">Hrs</span>
                            </div>
                            <span class="text-zinc-400 font-bold -mt-2.5">:</span>
                            <div class="flex flex-col items-center">
                                <span class="px-2 py-1 bg-[#0B0D10] text-[#DFCAAB] rounded border border-zinc-800 shadow-xs text-xs" x-text="minutes">42</span>
                                <span class="text-[8px] text-zinc-500 font-sans mt-0.5 uppercase tracking-tighter">Mins</span>
                            </div>
                            <span class="text-zinc-400 font-bold -mt-2.5">:</span>
                            <div class="flex flex-col items-center">
                                <span class="px-2 py-1 bg-[#0B0D10] text-[#DFCAAB] rounded border border-zinc-800 shadow-xs text-xs" x-text="seconds">19</span>
                                <span class="text-[8px] text-zinc-500 font-sans mt-0.5 uppercase tracking-tighter">Secs</span>
                            </div>
                        </div>
                    </div>
                @endif

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
                                    @click="selectSize(@js($size))"
                                    :class="{
                                        'bg-zinc-950 text-white border-zinc-950 shadow-sm': selectedSizeId == {{ $size->id }},
                                        'bg-white text-zinc-800 border-zinc-200 hover:border-black': selectedSizeId != {{ $size->id }},
                                        'opacity-40 line-through': !isSizeInStockForColor({{ $size->id }})
                                    }"
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
                            <button type="button" @click="if (quantity > 1) quantity--" class="w-8 h-full text-zinc-600 hover:text-black font-bold focus:outline-none" :disabled="quantity <= 1">-</button>
                            <span class="w-8 text-center text-xs font-bold text-zinc-900" x-text="quantity"></span>
                            <button type="button" @click="if (quantity < stockQuantity) quantity++" class="w-8 h-full text-zinc-600 hover:text-black font-bold focus:outline-none" :disabled="quantity >= stockQuantity">+</button>
                        </div>

                        <!-- Add to Bag CTA Button -->
                        <button type="button" 
                                @click="handleAddToCart()"
                                :disabled="!isInStock || isAdding"
                                class="flex-1 h-12 bg-[#0B0D10] hover:bg-black text-[#DFCAAB] disabled:bg-zinc-300 disabled:text-zinc-500 text-xs font-bold uppercase tracking-[0.2em] rounded-lg transition-colors shadow-lg flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            <span x-text="isAdding ? 'Adding to Bag...' : (isInStock ? 'Add to Shopping Bag' : 'Out of Stock')"></span>
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
                            @click="handleBuyNow()"
                            :disabled="!isInStock || isAdding"
                            class="w-full py-3.5 bg-[#C5A880] hover:bg-[#DFCAAB] text-zinc-950 disabled:hidden text-xs font-bold uppercase tracking-[0.2em] rounded-lg transition-colors shadow-md">
                        <span x-text="isAdding ? 'Processing...' : 'Buy It Now'"></span>
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

        <!-- Product Tabs System (Webishop / Luxury Store Feature) -->
        <div id="product-tabs-section" class="mt-16 pt-8 border-t border-zinc-200">
            <div class="max-w-4xl mx-auto">
                
                <!-- Tab Navigation Buttons -->
                <div class="flex items-center space-x-2 sm:space-x-4 border-b border-zinc-200 overflow-x-auto no-scrollbar pb-px">
                    <button type="button" 
                            @click="activeProductTab = 'description'"
                            :class="activeProductTab === 'description' ? 'border-[#0B0D10] text-[#0B0D10] font-bold border-b-2' : 'border-transparent text-zinc-500 hover:text-zinc-900 font-medium'"
                            class="py-3 px-3 text-xs sm:text-sm uppercase tracking-wider transition-all whitespace-nowrap focus:outline-none">
                        Atelier Story
                    </button>
                    
                    <button type="button" 
                            @click="activeProductTab = 'specifications'"
                            :class="activeProductTab === 'specifications' ? 'border-[#0B0D10] text-[#0B0D10] font-bold border-b-2' : 'border-transparent text-zinc-500 hover:text-zinc-900 font-medium'"
                            class="py-3 px-3 text-xs sm:text-sm uppercase tracking-wider transition-all whitespace-nowrap focus:outline-none">
                        Fabric & Specifications
                    </button>

                    <button type="button" 
                            @click="activeProductTab = 'collar_care'"
                            :class="activeProductTab === 'collar_care' ? 'border-[#0B0D10] text-[#0B0D10] font-bold border-b-2' : 'border-transparent text-zinc-500 hover:text-zinc-900 font-medium'"
                            class="py-3 px-3 text-xs sm:text-sm uppercase tracking-wider transition-all whitespace-nowrap focus:outline-none">
                        Collar & Care
                    </button>

                    <button type="button" 
                            @click="activeProductTab = 'shipping'"
                            :class="activeProductTab === 'shipping' ? 'border-[#0B0D10] text-[#0B0D10] font-bold border-b-2' : 'border-transparent text-zinc-500 hover:text-zinc-900 font-medium'"
                            class="py-3 px-3 text-xs sm:text-sm uppercase tracking-wider transition-all whitespace-nowrap focus:outline-none">
                        Shipping & Exchange
                    </button>

                    <button type="button" 
                            @click="activeProductTab = 'reviews'"
                            :class="activeProductTab === 'reviews' ? 'border-[#0B0D10] text-[#0B0D10] font-bold border-b-2' : 'border-transparent text-zinc-500 hover:text-zinc-900 font-medium'"
                            class="py-3 px-3 text-xs sm:text-sm uppercase tracking-wider transition-all whitespace-nowrap focus:outline-none flex items-center space-x-1.5">
                        <span>Client Reviews</span>
                        <span class="px-1.5 py-0.5 rounded-full bg-zinc-100 text-zinc-700 text-[10px] font-bold">{{ $product->reviews_count }}</span>
                    </button>
                </div>

                <!-- Tab Content Panels -->
                <div class="pt-8">
                    
                    <!-- Tab 1: Description -->
                    <div x-show="activeProductTab === 'description'" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="space-y-6">
                        <div class="space-y-4">
                            <h3 class="text-2xl font-serif-luxury font-bold text-zinc-900">
                                The Making of {{ $product->name }}
                            </h3>
                            <div class="prose prose-zinc max-w-none text-zinc-600 font-light leading-relaxed whitespace-pre-line text-sm sm:text-base">
                                {{ $product->description }}
                            </div>
                        </div>

                        <!-- Brand Craft Highlights -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-6 border-t border-zinc-100">
                            <div class="p-4 rounded-xl bg-zinc-50 border border-zinc-100">
                                <span class="text-xs font-bold uppercase tracking-wider text-zinc-900 block mb-1">Tailored Silhouette</span>
                                <p class="text-xs text-zinc-600">Sculpted shoulder pitch with clean drape around the waistline.</p>
                            </div>
                            <div class="p-4 rounded-xl bg-zinc-50 border border-zinc-100">
                                <span class="text-xs font-bold uppercase tracking-wider text-zinc-900 block mb-1">Long-Staple Fibers</span>
                                <p class="text-xs text-zinc-600">Double-mercerized yarn resistant to pilling, fading, and distortion.</p>
                            </div>
                            <div class="p-4 rounded-xl bg-zinc-50 border border-zinc-100">
                                <span class="text-xs font-bold uppercase tracking-wider text-zinc-900 block mb-1">Mother of Pearl Finish</span>
                                <p class="text-xs text-zinc-600">Signature engraved buttons cross-stitched for enduring resilience.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 2: Fabric & Specifications -->
                    <div x-show="activeProductTab === 'specifications'" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         style="display: none;"
                         class="space-y-6">
                        <h3 class="text-xl font-serif-luxury font-bold text-zinc-900">Sartorial Specifications</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="p-4 bg-zinc-50 rounded-xl border border-zinc-100 flex items-center justify-between">
                                <span class="text-xs text-zinc-500 uppercase tracking-wider">Fabric Composition</span>
                                <span class="text-xs font-bold text-zinc-900">{{ $product->fabric }}</span>
                            </div>
                            <div class="p-4 bg-zinc-50 rounded-xl border border-zinc-100 flex items-center justify-between">
                                <span class="text-xs text-zinc-500 uppercase tracking-wider">Tailored Fit</span>
                                <span class="text-xs font-bold text-zinc-900">{{ $product->fit }}</span>
                            </div>
                            <div class="p-4 bg-zinc-50 rounded-xl border border-zinc-100 flex items-center justify-between">
                                <span class="text-xs text-zinc-500 uppercase tracking-wider">Collar Architecture</span>
                                <span class="text-xs font-bold text-zinc-900">{{ $product->collar_type }}</span>
                            </div>
                            <div class="p-4 bg-zinc-50 rounded-xl border border-zinc-100 flex items-center justify-between">
                                <span class="text-xs text-zinc-500 uppercase tracking-wider">Sleeve Style</span>
                                <span class="text-xs font-bold text-zinc-900">{{ $product->sleeve_type }}</span>
                            </div>
                            <div class="p-4 bg-zinc-50 rounded-xl border border-zinc-100 flex items-center justify-between">
                                <span class="text-xs text-zinc-500 uppercase tracking-wider">Country of Origin</span>
                                <span class="text-xs font-bold text-zinc-900">{{ $product->country_of_origin }}</span>
                            </div>
                            <div class="p-4 bg-zinc-50 rounded-xl border border-zinc-100 flex items-center justify-between">
                                <span class="text-xs text-zinc-500 uppercase tracking-wider">SKU Code</span>
                                <span class="text-xs font-bold font-mono text-zinc-900">{{ $product->sku }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 3: Collar Engineering & Care -->
                    <div x-show="activeProductTab === 'collar_care'" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         style="display: none;"
                         class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="p-5 rounded-2xl bg-zinc-50 border border-zinc-100 space-y-3">
                                <h4 class="text-xs font-bold uppercase tracking-widest text-[#8C6D46]">Collar Engineering</h4>
                                <h5 class="text-base font-serif-luxury font-bold text-zinc-900">Stay-Flat Ribbed Technology</h5>
                                <p class="text-xs text-zinc-600 leading-relaxed">
                                    Every JACARIO collar incorporates a micro-interlining weave that prevents curling, rolling, and fraying, even after repeated machine washes. Designed to stand proud under a tailored sport coat or lie crisp over bare shoulders.
                                </p>
                            </div>
                            <div class="p-5 rounded-2xl bg-zinc-50 border border-zinc-100 space-y-3">
                                <h4 class="text-xs font-bold uppercase tracking-widest text-[#8C6D46]">Wash & Care Guide</h4>
                                <h5 class="text-base font-serif-luxury font-bold text-zinc-900">{{ $product->wash_care }}</h5>
                                <ul class="text-xs text-zinc-600 space-y-2">
                                    <li class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span>Machine wash cold (30°C) with like colors inside out.</span>
                                    </li>
                                    <li class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span>Use mild liquid detergent; avoid optical brighteners or bleach.</span>
                                    </li>
                                    <li class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span>Dry flat in shade. Warm iron if required; do not iron on labels.</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 4: Shipping & Exchange -->
                    <div x-show="activeProductTab === 'shipping'" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         style="display: none;"
                         class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="p-5 rounded-2xl bg-zinc-50 border border-zinc-100 space-y-2">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-zinc-900">Complimentary Courier Dispatch</h4>
                                <p class="text-xs text-zinc-600 leading-relaxed">
                                    All orders over ₹999 ship complimentary across India. Metro cities receive delivery in 24–48 hours, while other locations typically arrive in 3–5 business days.
                                </p>
                            </div>
                            <div class="p-5 rounded-2xl bg-zinc-50 border border-zinc-100 space-y-2">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-zinc-900">15-Day Doorstep Exchange</h4>
                                <p class="text-xs text-zinc-600 leading-relaxed">
                                    Need a different size or fit? We arrange contactless doorstep exchange within 15 days of delivery at zero additional cost.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 5: Client Reviews -->
                    <div x-show="activeProductTab === 'reviews'" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         style="display: none;"
                         class="space-y-10">
                        
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

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-6">
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
            <div class="relative max-w-2xl w-full bg-white rounded-2xl shadow-2xl p-6 sm:p-8 overflow-hidden border border-zinc-200" 
                 x-data="{ sizeUnit: 'in' }"
                 @click.stop>
                
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-zinc-100 gap-3">
                    <div>
                        <h3 class="text-lg font-serif-luxury font-bold text-zinc-900">JACARIO Size & Measurement Guide</h3>
                        <p class="text-xs text-zinc-500">All measurements are tailored to bespoke European sartorial standards</p>
                    </div>
                    
                    <div class="flex items-center space-x-3 self-end sm:self-auto">
                        <!-- Unit Switcher Toggle (Inches / Centimeters) -->
                        <div class="inline-flex items-center p-1 bg-zinc-100 rounded-lg text-xs font-semibold">
                            <button type="button" 
                                    @click="sizeUnit = 'in'" 
                                    :class="sizeUnit === 'in' ? 'bg-[#0B0D10] text-[#DFCAAB] shadow-sm font-bold' : 'text-zinc-600 hover:text-black'" 
                                    class="px-2.5 py-1 rounded-md transition-all">
                                Inches (in)
                            </button>
                            <button type="button" 
                                    @click="sizeUnit = 'cm'" 
                                    :class="sizeUnit === 'cm' ? 'bg-[#0B0D10] text-[#DFCAAB] shadow-sm font-bold' : 'text-zinc-600 hover:text-black'" 
                                    class="px-2.5 py-1 rounded-md transition-all">
                                Centimeters (cm)
                            </button>
                        </div>

                        <button type="button" @click="sizeGuideOpen = false" class="text-zinc-400 hover:text-black p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
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
                                $sizeMatrix = [
                                    ['S', '38"', '96 cm', '27.5"', '70 cm', '17.0"', '43 cm', '8.2"', '21 cm'],
                                    ['M', '40"', '102 cm', '28.5"', '72 cm', '17.7"', '45 cm', '8.6"', '22 cm'],
                                    ['L', '42"', '107 cm', '29.5"', '75 cm', '18.5"', '47 cm', '9.0"', '23 cm'],
                                    ['XL', '44"', '112 cm', '30.5"', '77 cm', '19.3"', '49 cm', '9.4"', '24 cm'],
                                    ['XXL', '46"', '117 cm', '31.5"', '80 cm', '20.1"', '51 cm', '9.8"', '25 cm'],
                                ];
                            @endphp
                            @foreach($sizeMatrix as $row)
                                <tr class="hover:bg-zinc-50 transition-colors">
                                    <td class="p-3 font-bold text-zinc-950">{{ $row[0] }}</td>
                                    <td class="p-3 font-medium text-zinc-700">
                                        <span x-show="sizeUnit === 'in'">{{ $row[1] }}</span>
                                        <span x-show="sizeUnit === 'cm'">{{ $row[2] }}</span>
                                    </td>
                                    <td class="p-3 font-medium text-zinc-700">
                                        <span x-show="sizeUnit === 'in'">{{ $row[3] }}</span>
                                        <span x-show="sizeUnit === 'cm'">{{ $row[4] }}</span>
                                    </td>
                                    <td class="p-3 font-medium text-zinc-700">
                                        <span x-show="sizeUnit === 'in'">{{ $row[5] }}</span>
                                        <span x-show="sizeUnit === 'cm'">{{ $row[6] }}</span>
                                    </td>
                                    <td class="p-3 font-medium text-zinc-700">
                                        <span x-show="sizeUnit === 'in'">{{ $row[7] }}</span>
                                        <span x-show="sizeUnit === 'cm'">{{ $row[8] }}</span>
                                    </td>
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
    <div class="fixed bottom-16 inset-x-0 bg-white border-t border-zinc-200 px-3 py-2.5 z-30 lg:hidden lg:bottom-0 shadow-[0_-4px_20px_rgba(0,0,0,0.12)] flex items-center space-x-2.5 pb-safe">
        
        <!-- 1. Wishlist Button -->
        <button type="button" 
                @click="$store.wishlist.toggle({{ $product->id }}, $el)"
                class="w-1/3 py-3 border border-zinc-300 rounded-xl text-xs font-bold uppercase tracking-wider text-zinc-800 flex items-center justify-center space-x-1.5 active:bg-zinc-100 transition-colors">
            <svg class="w-4 h-4 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            <span>Wishlist</span>
        </button>

        <!-- 2. Add to Bag Button -->
        <button type="button" 
                @click="handleAddToCart()"
                :disabled="!isInStock || isAdding"
                class="w-2/3 py-3 bg-[#0B0D10] text-[#DFCAAB] disabled:bg-zinc-300 disabled:text-zinc-500 text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-md flex items-center justify-center space-x-2 active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            <span x-text="isAdding ? 'Adding...' : (isInStock ? 'Add to Bag' : 'Out of Stock')"></span>
        </button>

    </div>

</div>

@endsection
