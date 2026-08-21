@extends('layouts.app')

@section('title', 'Shop Polo T-Shirts | JACARIO Haute Apparel')
@section('meta_description', 'Explore JACARIO\'s comprehensive collection of luxury Polo T-Shirts. Filter by style, size, color, fit, and premium cotton-silk fabrics.')

@section('content')

@php
    $activeFilterCount = 0;
    if(request('q')) $activeFilterCount++;
    if(request('category')) $activeFilterCount++;
    if(request('collection')) $activeFilterCount++;
    if(request('fit')) $activeFilterCount += is_array(request('fit')) ? count(request('fit')) : 1;
    if(request('size')) $activeFilterCount += is_array(request('size')) ? count(request('size')) : 1;
    if(request('color')) $activeFilterCount += is_array(request('color')) ? count(request('color')) : 1;
    if(request('min_price') || request('max_price')) $activeFilterCount++;
    if(request('in_stock')) $activeFilterCount++;
@endphp

<div x-data="{ 
    sortSheetOpen: false, 
    filterSheetOpen: false,
    activeFilterTab: 'category'
}">

    <!-- Breadcrumb & Running 3-Banner Shop Header Carousel -->
    <div class="relative bg-zinc-950 text-white pt-6 pb-4 sm:pt-10 sm:pb-5 border-b border-zinc-800 overflow-hidden"
         x-data="{
            currentShopSlide: 0,
            totalShopSlides: {{ count($shopBanners) }},
            progress: 0,
            isPaused: false,
            timer: null,
            touchStartX: 0,
            touchEndX: 0,
            init() {
                this.startProgress();
            },
            startProgress() {
                clearInterval(this.timer);
                this.timer = setInterval(() => {
                    if (!this.isPaused) {
                        this.progress += 2;
                        if (this.progress >= 100) {
                            this.nextSlide();
                        }
                    }
                }, 90);
            },
            nextSlide() {
                this.currentShopSlide = (this.currentShopSlide + 1) % this.totalShopSlides;
                this.progress = 0;
            },
            prevSlide() {
                this.currentShopSlide = (this.currentShopSlide - 1 + this.totalShopSlides) % this.totalShopSlides;
                this.progress = 0;
            },
            goToSlide(idx) {
                this.currentShopSlide = idx;
                this.progress = 0;
            },
            handleTouchStart(e) {
                this.touchStartX = e.changedTouches[0].screenX;
            },
            handleTouchEnd(e) {
                this.touchEndX = e.changedTouches[0].screenX;
                if (this.touchStartX - this.touchEndX > 40) {
                    this.nextSlide();
                } else if (this.touchEndX - this.touchStartX > 40) {
                    this.prevSlide();
                }
            }
         }"
         @mouseenter="isPaused = true"
         @mouseleave="isPaused = false"
         @touchstart="handleTouchStart($event)"
         @touchend="handleTouchEnd($event)">
        
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#DFCAAB_1px,transparent_1px)] [background-size:20px_20px] pointer-events-none z-10"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-zinc-950 via-zinc-950/90 to-transparent pointer-events-none z-10 w-full sm:w-3/4"></div>

        <!-- Carousel Slides -->
        <div class="relative w-full min-h-[180px] sm:min-h-[220px]">
            @foreach($shopBanners as $index => $sBanner)
                <div class="absolute inset-0 w-full h-full transition-all duration-700 ease-in-out"
                     x-show="currentShopSlide === {{ $index }}"
                     x-transition:enter="transition ease-out duration-700"
                     x-transition:enter-start="opacity-0 translate-x-4"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     x-transition:leave="transition ease-in duration-500"
                     x-transition:leave-start="opacity-100 translate-x-0"
                     x-transition:leave-end="opacity-0 -translate-x-4"
                     style="{{ $index === 0 ? '' : 'display: none;' }}">
                    
                    <!-- Slide Background Image with Soft Fade -->
                    <div class="absolute right-0 top-0 bottom-0 w-full sm:w-2/3 lg:w-1/2 opacity-35 sm:opacity-45 overflow-hidden pointer-events-none">
                        <img src="{{ $sBanner->image_url ?? $sBanner->image_path }}" 
                             alt="{{ $sBanner->title }}" 
                             class="w-full h-full object-cover object-[center_25%] filter brightness-90">
                        <div class="absolute inset-0 bg-gradient-to-r from-zinc-950 via-zinc-950/50 to-transparent"></div>
                    </div>

                    <!-- Slide Content -->
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20 h-full flex flex-col justify-between">
                        
                        <!-- Breadcrumbs -->
                        <nav class="flex items-center space-x-2 text-[11px] text-zinc-400 mb-2 sm:mb-3">
                            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
                            <span>/</span>
                            <span class="text-[#DFCAAB] font-bold">Catalog</span>
                            @if(request('collection') || request('category') || request('fabric'))
                                <span>/</span>
                                <span class="text-white capitalize">{{ request('collection') ?? request('category') ?? request('fabric') }}</span>
                            @endif
                        </nav>

                        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                            <div class="space-y-1.5 max-w-2xl">
                                @if(!empty($sBanner->badge_text))
                                    <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-[#DFCAAB] text-[10px] sm:text-xs font-bold uppercase tracking-widest">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#DFCAAB] animate-pulse"></span>
                                        <span>{{ $sBanner->badge_text }}</span>
                                    </div>
                                @endif

                                <h1 class="text-2xl sm:text-4xl lg:text-5xl font-serif-luxury font-bold text-white tracking-tight leading-tight">
                                    @if(request('collection') === 'bestsellers')
                                        Customer Icons & Best Sellers
                                    @elseif(request('collection') === 'new-arrivals')
                                        New Season Arrivals
                                    @elseif(request('fabric'))
                                        {{ request('fabric') }} Collection
                                    @elseif(request('category'))
                                        {{ $categories->firstWhere('slug', request('category'))->name ?? 'The Collection' }}
                                    @elseif(request('q'))
                                        Search Results for "{{ request('q') }}"
                                    @else
                                        {{ $sBanner->title }}
                                    @endif
                                </h1>

                                <p class="text-xs sm:text-sm text-zinc-300 font-light leading-relaxed max-w-xl">
                                    {{ $sBanner->subtitle }}
                                </p>
                            </div>
                        </div>

                    </div>

                </div>
            @endforeach
        </div>

        <!-- Running Banner Carousel Controls (Bottom Indicator Bar - No Line) -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20 mt-6 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <span class="text-[11px] font-mono text-[#DFCAAB] font-bold" x-text="`0${currentShopSlide + 1} / 0${totalShopSlides}`">01 / 03</span>
                <div class="flex items-center space-x-2">
                    @foreach($shopBanners as $index => $sBanner)
                        <button type="button" 
                                @click="goToSlide({{ $index }})" 
                                class="relative w-12 sm:w-16 h-1.5 rounded-full bg-white/20 overflow-hidden cursor-pointer group"
                                aria-label="Go to banner {{ $index + 1 }}">
                            <div class="absolute inset-y-0 left-0 bg-[#DFCAAB] rounded-full transition-all"
                                 :style="currentShopSlide === {{ $index }} 
                                            ? `width: ${progress}%; transition: width 90ms linear;` 
                                            : (currentShopSlide > {{ $index }} ? 'width: 100%;' : 'width: 0%;')">
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Prev / Next Micro Chevrons -->
            <div class="flex items-center space-x-1.5">
                <button type="button" 
                        @click="prevSlide()" 
                        class="w-7 h-7 rounded-lg bg-white/10 hover:bg-white/25 text-white flex items-center justify-center transition-colors text-xs" 
                        aria-label="Previous Banner">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button type="button" 
                        @click="nextSlide()" 
                        class="w-7 h-7 rounded-lg bg-white/10 hover:bg-white/25 text-white flex items-center justify-center transition-colors text-xs" 
                        aria-label="Next Banner">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>

    </div>

    <!-- Myntra-Style Horizontal Category Filter Bar (Scrollable on Mobile) -->
    <div class="bg-white border-b border-zinc-200 py-2.5 px-4 overflow-x-auto no-scrollbar">
        <div class="max-w-7xl mx-auto flex items-center space-x-2 min-w-max">
            
            <!-- All -->
            <a href="{{ route('shop.index') }}" 
               class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-colors {{ !request('category') && !request('collection') ? 'bg-zinc-950 text-white' : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200' }}">
                All ({{ $categories->sum('products_count') }})
            </a>

            <!-- Categories -->
            @foreach($categories as $cat)
                <a href="{{ route('shop.index', ['category' => $cat->slug]) }}" 
                   class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-colors {{ request('category') === $cat->slug ? 'bg-[#0B0D10] text-[#DFCAAB]' : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200' }}">
                    {{ $cat->name }}
                </a>
            @endforeach

            <!-- Bestsellers -->
            <a href="{{ route('shop.index', ['collection' => 'bestsellers']) }}" 
               class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-colors {{ request('collection') === 'bestsellers' ? 'bg-[#0B0D10] text-[#DFCAAB]' : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200' }}">
                🔥 Best Sellers
            </a>

            <!-- New Arrivals -->
            <a href="{{ route('shop.index', ['collection' => 'new-arrivals']) }}" 
               class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-colors {{ request('collection') === 'new-arrivals' ? 'bg-[#0B0D10] text-[#DFCAAB]' : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200' }}">
                ✨ New Drops
            </a>

        </div>
    </div>

    <!-- Main Catalog Container -->
    <div class="max-w-7xl mx-auto px-2.5 sm:px-6 lg:px-8 py-4 sm:py-8">
        
        <!-- Desktop Controls Bar (Hidden on Mobile) -->
        <div class="hidden lg:flex items-center justify-between pb-6 border-b border-zinc-200">
            <!-- Active Filters Quick Bar -->
            <div class="flex flex-wrap items-center gap-2">
                @if($activeFilterCount > 0)
                    <span class="text-xs text-zinc-500 font-medium">Active Filters:</span>
                    
                    @if(request('q'))
                        <span class="inline-flex items-center space-x-1 px-2.5 py-1 bg-zinc-200 text-zinc-800 rounded-full text-xs font-medium">
                            <span>"{{ request('q') }}"</span>
                            <a href="{{ request()->fullUrlWithQuery(['q' => null]) }}" class="hover:text-black">✕</a>
                        </span>
                    @endif

                    @if(request('category'))
                        <span class="inline-flex items-center space-x-1 px-2.5 py-1 bg-zinc-200 text-zinc-800 rounded-full text-xs font-medium">
                            <span>{{ $categories->firstWhere('slug', request('category'))->name ?? request('category') }}</span>
                            <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="hover:text-black">✕</a>
                        </span>
                    @endif

                    @if(request('collection'))
                        <span class="inline-flex items-center space-x-1 px-2.5 py-1 bg-[#0B0D10] text-[#DFCAAB] rounded-full text-xs font-medium">
                            <span>{{ ucfirst(str_replace('-', ' ', request('collection'))) }}</span>
                            <a href="{{ request()->fullUrlWithQuery(['collection' => null]) }}" class="hover:text-white">✕</a>
                        </span>
                    @endif

                    <a href="{{ route('shop.index') }}" class="text-xs text-rose-600 hover:underline font-semibold ml-2">Clear All</a>
                @endif
            </div>

            <!-- Sorting Select Dropdown -->
            <div class="flex items-center space-x-2">
                <label for="sort" class="text-xs text-zinc-500 font-medium whitespace-nowrap">Sort By:</label>
                <form method="GET" action="{{ route('shop.index') }}" id="desktopSortForm">
                    @foreach(request()->except('sort', 'page') as $key => $val)
                        @if(is_array($val))
                            @foreach($val as $subVal)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $subVal }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                        @endif
                    @endforeach

                    <select name="sort" onchange="document.getElementById('desktopSortForm').submit()" class="text-xs font-semibold text-zinc-800 bg-white border border-zinc-200 rounded-lg px-3 py-2 focus:outline-none focus:border-black cursor-pointer">
                        <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Featured / Recommended</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>What's New</option>
                        <option value="bestselling" {{ request('sort') == 'bestselling' ? 'selected' : '' }}>Best Selling</option>
                        <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Customer Rating</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 pt-2 sm:pt-6">
            
            <!-- Desktop Filters Sidebar (Left 1 Col) -->
            <aside class="hidden lg:block space-y-8">
                <form method="GET" action="{{ route('shop.index') }}">
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif

                    <!-- Search Input -->
                    <div class="pb-6 border-b border-zinc-200">
                        <h4 class="text-xs font-bold uppercase tracking-widest text-zinc-900 mb-3">Search</h4>
                        <div class="relative">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Keyword..." class="w-full text-xs bg-white border border-zinc-200 rounded-lg pl-3 pr-8 py-2 focus:outline-none focus:border-black">
                            <button type="submit" class="absolute right-2.5 top-2.5 text-zinc-400 hover:text-black">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Collection Style / Categories -->
                    <div class="pb-6 border-b border-zinc-200 space-y-2">
                        <h4 class="text-xs font-bold uppercase tracking-widest text-zinc-900 mb-2">Collection Style</h4>
                        <div class="space-y-1.5">
                            <a href="{{ route('shop.index') }}" class="block text-xs {{ !request('category') ? 'font-bold text-zinc-950' : 'text-zinc-600 hover:text-black' }}">
                                All Polos ({{ $products->total() }})
                            </a>
                            @foreach($categories as $cat)
                                <a href="{{ route('shop.index', ['category' => $cat->slug]) }}" class="block text-xs {{ request('category') === $cat->slug ? 'font-bold text-zinc-950' : 'text-zinc-600 hover:text-black' }}">
                                    {{ $cat->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Size Filter -->
                    <div class="py-6 border-b border-zinc-200 space-y-3">
                        <h4 class="text-xs font-bold uppercase tracking-widest text-zinc-900">Size</h4>
                        <div class="grid grid-cols-5 gap-1.5">
                            @foreach($sizes as $size)
                                @php $isSizeActive = in_array($size->code, (array) request('size', [])); @endphp
                                <label class="text-center py-2 text-xs font-bold rounded-lg border cursor-pointer transition-colors {{ $isSizeActive ? 'bg-zinc-900 text-white border-zinc-900' : 'bg-white text-zinc-700 border-zinc-200 hover:border-black' }}">
                                    <input type="checkbox" name="size[]" value="{{ $size->code }}" {{ $isSizeActive ? 'checked' : '' }} onchange="this.form.submit()" class="sr-only">
                                    <span>{{ $size->code }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Color Filter -->
                    <div class="py-6 border-b border-zinc-200 space-y-3">
                        <h4 class="text-xs font-bold uppercase tracking-widest text-zinc-900">Color Palette</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($colors as $color)
                                @php $isColorActive = in_array($color->slug, (array) request('color', [])); @endphp
                                <label class="relative cursor-pointer group" title="{{ $color->name }}">
                                    <input type="checkbox" name="color[]" value="{{ $color->slug }}" {{ $isColorActive ? 'checked' : '' }} onchange="this.form.submit()" class="sr-only">
                                    <span class="w-6 h-6 rounded-full border border-zinc-300 block transition-transform group-hover:scale-110 {{ $isColorActive ? 'ring-2 ring-black ring-offset-2' : '' }}" style="background-color: {{ $color->hex_code }}"></span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Fit Filter -->
                    <div class="py-6 border-b border-zinc-200 space-y-3">
                        <h4 class="text-xs font-bold uppercase tracking-widest text-zinc-900">Fit Silhouette</h4>
                        <div class="space-y-2">
                            @foreach($fits as $fit)
                                @php $isFitActive = in_array($fit, (array) request('fit', [])); @endphp
                                <label class="flex items-center space-x-2 text-xs text-zinc-700 hover:text-black cursor-pointer">
                                    <input type="checkbox" name="fit[]" value="{{ $fit }}" {{ $isFitActive ? 'checked' : '' }} onchange="this.form.submit()" class="rounded border-zinc-300 text-zinc-900 focus:ring-black">
                                    <span>{{ $fit }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="py-6 border-b border-zinc-200 space-y-3">
                        <h4 class="text-xs font-bold uppercase tracking-widest text-zinc-900">Price Range (₹)</h4>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" class="w-full text-xs bg-white border border-zinc-200 rounded-lg p-2 focus:outline-none focus:border-black">
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" class="w-full text-xs bg-white border border-zinc-200 rounded-lg p-2 focus:outline-none focus:border-black">
                        </div>
                        <button type="submit" class="w-full py-2 bg-zinc-100 hover:bg-zinc-200 text-zinc-800 rounded-lg text-xs font-bold uppercase tracking-wider transition-colors">
                            Apply Price
                        </button>
                    </div>
                </form>
            </aside>

            <!-- Products Grid Area (Myntra-Style 2-Col on Mobile, 3-Col on Desktop) -->
            <div class="lg:col-span-3">
                
                @if($products->isEmpty())
                    <!-- Empty State -->
                    <div class="py-16 text-center bg-white rounded-2xl border border-zinc-200 p-8 sm:p-12">
                        <div class="w-14 h-14 mx-auto mb-3 rounded-full bg-zinc-100 flex items-center justify-center text-zinc-400">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <h3 class="text-base font-serif-luxury font-bold text-zinc-900 mb-1">No matching Polo T-Shirts found</h3>
                        <p class="text-xs text-zinc-500 max-w-sm mx-auto mb-4">
                            Try adjusting your filters or resetting your selection.
                        </p>
                        <a href="{{ route('shop.index') }}" class="inline-flex items-center px-6 py-2.5 bg-zinc-900 text-white rounded-lg text-xs font-bold uppercase tracking-widest hover:bg-black transition-colors">
                            Reset All Filters
                        </a>
                    </div>
                @else
                    <!-- Myntra 2-Column Responsive Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-4 lg:gap-6">
                        @foreach($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8 sm:mt-12">
                        {{ $products->links() }}
                    </div>
                @endif

            </div>

        </div>

    </div>

    <!-- Myntra-Style Sticky Bottom Dual Action Bar on Mobile (SORT | FILTER) -->
    <div class="fixed bottom-16 inset-x-0 z-30 bg-white border-t border-zinc-200 lg:hidden shadow-[0_-4px_16px_rgba(0,0,0,0.08)] flex items-center h-12">
        
        <!-- 1. SORT Button -->
        <button type="button" 
                @click="sortSheetOpen = true" 
                class="flex-1 h-full flex items-center justify-center space-x-2 border-r border-zinc-200 text-xs font-bold uppercase tracking-wider text-zinc-800 active:bg-zinc-50">
            <svg class="w-4 h-4 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
            <span>Sort</span>
        </button>

        <!-- 2. FILTER Button -->
        <button type="button" 
                @click="filterSheetOpen = true" 
                class="flex-1 h-full flex items-center justify-center space-x-2 text-xs font-bold uppercase tracking-wider text-zinc-800 active:bg-zinc-50 relative">
            <svg class="w-4 h-4 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            <span>Filter</span>
            @if($activeFilterCount > 0)
                <span class="w-4 h-4 rounded-full bg-rose-600 text-white text-[10px] font-black flex items-center justify-center">
                    {{ $activeFilterCount }}
                </span>
            @endif
        </button>

    </div>

    <!-- Myntra-Style Sort Slide-up Bottom Sheet Modal -->
    <div x-show="sortSheetOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-hidden lg:hidden"
         style="display: none;"
         @keydown.window.escape="sortSheetOpen = false">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs" @click="sortSheetOpen = false"></div>

        <!-- Slide-up Sheet -->
        <div class="fixed inset-x-0 bottom-0 max-h-[80vh] bg-white rounded-t-3xl shadow-2xl flex flex-col z-10 animate-slide-up pb-safe" @click.stop>
            
            <div class="flex items-center justify-between px-5 py-4 border-b border-zinc-100">
                <span class="text-xs font-bold uppercase tracking-widest text-zinc-400">Sort By</span>
                <button type="button" @click="sortSheetOpen = false" class="p-1 text-zinc-400 hover:text-black">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form method="GET" action="{{ route('shop.index') }}" class="p-3 space-y-1">
                @foreach(request()->except('sort', 'page') as $key => $val)
                    @if(is_array($val))
                        @foreach($val as $subVal)
                            <input type="hidden" name="{{ $key }}[]" value="{{ $subVal }}">
                        @endforeach
                    @else
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endif
                @endforeach

                @php
                    $sortOptions = [
                        'featured' => 'Featured / Recommended',
                        'newest' => 'What\'s New',
                        'bestselling' => 'Popularity / Best Sellers',
                        'price-asc' => 'Price: Low to High',
                        'price-desc' => 'Price: High to Low',
                        'rating' => 'Customer Rating',
                    ];
                    $currentSort = request('sort', 'featured');
                @endphp

                @foreach($sortOptions as $optKey => $optLabel)
                    <label class="flex items-center justify-between px-4 py-3.5 rounded-xl cursor-pointer transition-colors {{ $currentSort === $optKey ? 'bg-amber-50/70 font-bold text-zinc-950' : 'text-zinc-700 hover:bg-zinc-50' }}">
                        <span class="text-xs">{{ $optLabel }}</span>
                        <input type="radio" 
                               name="sort" 
                               value="{{ $optKey }}" 
                               {{ $currentSort === $optKey ? 'checked' : '' }} 
                               onchange="this.form.submit()" 
                               class="w-4 h-4 text-zinc-900 focus:ring-black">
                    </label>
                @endforeach
            </form>
        </div>
    </div>

    <!-- Myntra-Style 2-Pane Filter Slide-up Sheet Modal -->
    <div x-show="filterSheetOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-hidden lg:hidden"
         style="display: none;"
         @keydown.window.escape="filterSheetOpen = false">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs" @click="filterSheetOpen = false"></div>

        <!-- 2-Pane Slide-up Container -->
        <div class="fixed inset-x-0 bottom-0 h-[85vh] bg-white rounded-t-3xl shadow-2xl flex flex-col z-10 animate-slide-up" @click.stop>
            
            <!-- Header -->
            <div class="flex items-center justify-between px-5 py-4 border-b border-zinc-100">
                <div class="flex items-center space-x-2">
                    <span class="text-xs font-bold uppercase tracking-widest text-zinc-900">Filters</span>
                    @if($activeFilterCount > 0)
                        <span class="px-2 py-0.5 rounded-full bg-zinc-950 text-[#DFCAAB] text-[10px] font-bold">
                            {{ $activeFilterCount }} applied
                        </span>
                    @endif
                </div>
                <a href="{{ route('shop.index') }}" class="text-xs font-bold text-rose-600 uppercase tracking-wider hover:underline">
                    Clear All
                </a>
            </div>

            <!-- Form Wrapper -->
            <form method="GET" action="{{ route('shop.index') }}" class="flex-1 flex flex-col justify-between overflow-hidden">
                @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif

                <!-- 2-Pane Body Area -->
                <div class="flex-1 grid grid-cols-12 overflow-hidden">
                    
                    <!-- Left Tab Navigation (4 Cols) -->
                    <div class="col-span-4 bg-zinc-50 border-r border-zinc-200 overflow-y-auto space-y-0.5 p-1">
                        
                        <!-- Tab: Category -->
                        <button type="button" 
                                @click="activeFilterTab = 'category'" 
                                :class="activeFilterTab === 'category' ? 'bg-white font-bold text-zinc-950 border-l-4 border-zinc-950 shadow-xs' : 'text-zinc-600 hover:bg-zinc-100'"
                                class="w-full text-left px-3 py-3.5 text-xs transition-all flex items-center justify-between">
                            <span>Category</span>
                            @if(request('category')) <span class="w-1.5 h-1.5 rounded-full bg-[#8C6D46]"></span> @endif
                        </button>

                        <!-- Tab: Size -->
                        <button type="button" 
                                @click="activeFilterTab = 'size'" 
                                :class="activeFilterTab === 'size' ? 'bg-white font-bold text-zinc-950 border-l-4 border-zinc-950 shadow-xs' : 'text-zinc-600 hover:bg-zinc-100'"
                                class="w-full text-left px-3 py-3.5 text-xs transition-all flex items-center justify-between">
                            <span>Size</span>
                            @if(request('size')) <span class="w-1.5 h-1.5 rounded-full bg-[#8C6D46]"></span> @endif
                        </button>

                        <!-- Tab: Color -->
                        <button type="button" 
                                @click="activeFilterTab = 'color'" 
                                :class="activeFilterTab === 'color' ? 'bg-white font-bold text-zinc-950 border-l-4 border-zinc-950 shadow-xs' : 'text-zinc-600 hover:bg-zinc-100'"
                                class="w-full text-left px-3 py-3.5 text-xs transition-all flex items-center justify-between">
                            <span>Color</span>
                            @if(request('color')) <span class="w-1.5 h-1.5 rounded-full bg-[#8C6D46]"></span> @endif
                        </button>

                        <!-- Tab: Fit -->
                        <button type="button" 
                                @click="activeFilterTab = 'fit'" 
                                :class="activeFilterTab === 'fit' ? 'bg-white font-bold text-zinc-950 border-l-4 border-zinc-950 shadow-xs' : 'text-zinc-600 hover:bg-zinc-100'"
                                class="w-full text-left px-3 py-3.5 text-xs transition-all flex items-center justify-between">
                            <span>Fit</span>
                            @if(request('fit')) <span class="w-1.5 h-1.5 rounded-full bg-[#8C6D46]"></span> @endif
                        </button>

                        <!-- Tab: Price -->
                        <button type="button" 
                                @click="activeFilterTab = 'price'" 
                                :class="activeFilterTab === 'price' ? 'bg-white font-bold text-zinc-950 border-l-4 border-zinc-950 shadow-xs' : 'text-zinc-600 hover:bg-zinc-100'"
                                class="w-full text-left px-3 py-3.5 text-xs transition-all flex items-center justify-between">
                            <span>Price</span>
                            @if(request('min_price') || request('max_price')) <span class="w-1.5 h-1.5 rounded-full bg-[#8C6D46]"></span> @endif
                        </button>

                    </div>

                    <!-- Right Option Panel (8 Cols) -->
                    <div class="col-span-8 bg-white overflow-y-auto p-4">
                        
                        <!-- Panel: Category -->
                        <div x-show="activeFilterTab === 'category'" class="space-y-3">
                            <h4 class="text-[11px] font-bold uppercase tracking-wider text-zinc-400 mb-2">Select Category</h4>
                            @foreach($categories as $category)
                                <label class="flex items-center space-x-3 text-xs text-zinc-800 cursor-pointer py-1.5">
                                    <input type="radio" 
                                           name="category" 
                                           value="{{ $category->slug }}" 
                                           {{ request('category') === $category->slug ? 'checked' : '' }} 
                                           class="w-4 h-4 text-zinc-900 focus:ring-black">
                                    <span>{{ $category->name }}</span>
                                    <span class="text-zinc-400 text-[10px]">({{ $category->products_count }})</span>
                                </label>
                            @endforeach
                        </div>

                        <!-- Panel: Size -->
                        <div x-show="activeFilterTab === 'size'" class="space-y-3" style="display: none;">
                            <h4 class="text-[11px] font-bold uppercase tracking-wider text-zinc-400 mb-2">Select Sizes</h4>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach($sizes as $size)
                                    @php $isSizeActive = in_array($size->code, (array) request('size', [])); @endphp
                                    <label class="text-center py-2.5 text-xs font-bold rounded-lg border cursor-pointer transition-colors {{ $isSizeActive ? 'bg-zinc-900 text-white border-zinc-900' : 'bg-white text-zinc-700 border-zinc-200' }}">
                                        <input type="checkbox" name="size[]" value="{{ $size->code }}" {{ $isSizeActive ? 'checked' : '' }} class="sr-only">
                                        <span>{{ $size->code }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Panel: Color -->
                        <div x-show="activeFilterTab === 'color'" class="space-y-3" style="display: none;">
                            <h4 class="text-[11px] font-bold uppercase tracking-wider text-zinc-400 mb-2">Select Colors</h4>
                            <div class="space-y-2">
                                @foreach($colors as $color)
                                    @php $isColorActive = in_array($color->slug, (array) request('color', [])); @endphp
                                    <label class="flex items-center space-x-3 text-xs text-zinc-800 cursor-pointer py-1">
                                        <input type="checkbox" name="color[]" value="{{ $color->slug }}" {{ $isColorActive ? 'checked' : '' }} class="w-4 h-4 text-zinc-900 focus:ring-black rounded">
                                        <span class="w-4 h-4 rounded-full border border-zinc-300 inline-block shrink-0" style="background-color: {{ $color->hex_code }}"></span>
                                        <span>{{ $color->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Panel: Fit -->
                        <div x-show="activeFilterTab === 'fit'" class="space-y-3" style="display: none;">
                            <h4 class="text-[11px] font-bold uppercase tracking-wider text-zinc-400 mb-2">Select Fit Silhouette</h4>
                            @foreach($fits as $fit)
                                @php $isFitActive = in_array($fit, (array) request('fit', [])); @endphp
                                <label class="flex items-center space-x-3 text-xs text-zinc-800 cursor-pointer py-1.5">
                                    <input type="checkbox" name="fit[]" value="{{ $fit }}" {{ $isFitActive ? 'checked' : '' }} class="w-4 h-4 text-zinc-900 focus:ring-black rounded">
                                    <span>{{ $fit }}</span>
                                </label>
                            @endforeach
                        </div>

                        <!-- Panel: Price -->
                        <div x-show="activeFilterTab === 'price'" class="space-y-3" style="display: none;">
                            <h4 class="text-[11px] font-bold uppercase tracking-wider text-zinc-400 mb-2">Price Range (₹)</h4>
                            <div class="space-y-3 pt-1">
                                <div>
                                    <label class="text-[10px] text-zinc-500 font-medium">Minimum Price</label>
                                    <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="₹0" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-lg p-2.5 mt-1 focus:outline-none focus:border-black">
                                </div>
                                <div>
                                    <label class="text-[10px] text-zinc-500 font-medium">Maximum Price</label>
                                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="₹5,000" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-lg p-2.5 mt-1 focus:outline-none focus:border-black">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Footer Fixed Action Buttons -->
                <div class="p-4 border-t border-zinc-200 bg-white flex items-center space-x-3 pb-safe">
                    <button type="button" @click="filterSheetOpen = false" class="flex-1 py-3 text-center border border-zinc-300 text-zinc-700 rounded-xl text-xs font-bold uppercase tracking-wider">
                        Close
                    </button>
                    <button type="submit" class="flex-1 py-3 bg-zinc-950 text-[#DFCAAB] rounded-xl text-xs font-bold uppercase tracking-wider shadow-md">
                        Apply Filters
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection
