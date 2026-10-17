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
    activeFilterTab: 'category',
    viewMode: localStorage.getItem('jacario_view_mode') || 'grid'
}">

    @if(request('category') === 'mens-polo-t-shirts')
        <!-- Dedicated Editorial Luxury Header: Men's Polo Atelier Collection -->
        <div class="relative bg-[#0B0D10] text-white pt-6 pb-6 sm:pt-10 sm:pb-8 border-b border-zinc-800 overflow-hidden min-h-[320px] sm:min-h-[380px]">
            <!-- Full-Visibility Photography (100% Clear, No Dimming, No Dots) -->
            <div class="absolute right-0 top-0 bottom-0 w-full sm:w-7/12 lg:w-3/5 overflow-hidden pointer-events-none">
                <img src="{{ asset('images/catalog/banners/polo-hero-banner.jpg') }}" 
                     alt="Men's Luxury Polo Atelier Collection" 
                     class="w-full h-full object-cover object-[right_center] filter brightness-100"
                     style="height: 100%; width: 100%; object-fit: cover; object-position: right center;">
                <!-- Smooth Feathered Edge on the left to blend with text background -->
                <div class="hidden sm:block absolute inset-y-0 left-0 w-40 lg:w-56 bg-gradient-to-r from-[#0B0D10] to-transparent"></div>
                <!-- Mobile Only Readability Shield -->
                <div class="sm:hidden absolute inset-0 bg-gradient-to-t from-[#0B0D10] via-[#0B0D10]/75 to-transparent"></div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20">
                <!-- Breadcrumbs -->
                <nav class="flex items-center space-x-2 text-[11px] text-zinc-400 mb-3 sm:mb-4">
                    <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
                    <span>/</span>
                    <a href="{{ route('shop.index') }}" class="hover:text-white transition-colors">Catalog</a>
                    <span>/</span>
                    <span class="text-[#DFCAAB] font-bold">Men's Polo T-Shirts</span>
                </nav>

                <div class="max-w-xl lg:max-w-2xl space-y-3 sm:space-y-4">
                    <!-- Gold Atelier Badge -->
                    <div class="inline-flex items-center space-x-2 px-3.5 py-1 rounded-full bg-white/10 backdrop-blur-md border border-[#DFCAAB]/30 text-[#DFCAAB] text-[10px] sm:text-xs font-bold uppercase tracking-widest">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#DFCAAB] animate-pulse"></span>
                        <span>✦ THE ATELIER POLO COLLECTION • 100% SUPIMA® & MULBERRY SILK</span>
                    </div>

                    <!-- Editorial Headline -->
                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-serif-luxury font-bold text-white tracking-tight leading-tight">
                        Men's Luxury <span class="gold-gradient-text-light">Polo T-Shirts</span>
                    </h1>

                    <!-- Editorial Subtitle (Punch Line) -->
                    <p class="text-xs sm:text-sm text-zinc-300 font-medium leading-relaxed max-w-xl">
                        Architectural structure tailored from boardroom to Riviera.
                    </p>

                    <!-- 4 Craftsmanship Pills -->
                    <div class="flex flex-wrap items-center gap-2 pt-1 text-[11px]">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-zinc-900/90 border border-zinc-700/80 text-zinc-200">
                            ✦ 100% American Supima®
                        </span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-zinc-900/90 border border-zinc-700/80 text-zinc-200">
                            ✦ Anti-Curl Collar Interlining
                        </span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-zinc-900/90 border border-zinc-700/80 text-zinc-200">
                            ✦ Genuine Trocas Shell Buttons
                        </span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-zinc-900/90 border border-zinc-700/80 text-zinc-200">
                            ✦ 240 GSM Luxury Piqué
                        </span>
                    </div>

                    <!-- 4 Key Performance Metrics Bar -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-3 sm:pt-4 border-t border-zinc-800/80 max-w-xl">
                        <div>
                            <span class="block text-base sm:text-lg font-serif-luxury font-bold text-[#DFCAAB]">240 GSM</span>
                            <span class="text-[10px] text-zinc-400 uppercase tracking-wider">Heavyweight Drape</span>
                        </div>
                        <div>
                            <span class="block text-base sm:text-lg font-serif-luxury font-bold text-[#DFCAAB]">Zero Roll</span>
                            <span class="text-[10px] text-zinc-400 uppercase tracking-wider">Stay-Flat Collar</span>
                        </div>
                        <div>
                            <span class="block text-base sm:text-lg font-serif-luxury font-bold text-[#DFCAAB]">100% Pure</span>
                            <span class="text-[10px] text-zinc-400 uppercase tracking-wider">Supima® Certified</span>
                        </div>
                        <div>
                            <span class="block text-base sm:text-lg font-serif-luxury font-bold text-[#DFCAAB]">4.9 / 5.0 ★</span>
                            <span class="text-[10px] text-zinc-400 uppercase tracking-wider">Patron Verified</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif(request('category') === 'round-neck-t-shirts')
        <!-- Dedicated Editorial Luxury Header: Round Neck Collection -->
        <div class="relative bg-[#0B0D10] text-white pt-6 pb-6 sm:pt-10 sm:pb-8 border-b border-zinc-800 overflow-hidden min-h-[320px] sm:min-h-[380px]">
            <!-- Full-Visibility Photography (No Dots, Clear) -->
            <div class="absolute right-0 top-0 bottom-0 w-full sm:w-7/12 lg:w-3/5 overflow-hidden pointer-events-none">
                <img src="{{ asset('images/catalog/banners/roundneck-hero-banner.jpg') }}" 
                     alt="Men's Round Neck Luxury T-Shirts" 
                     class="w-full h-full object-cover object-[right_center] filter brightness-100"
                     style="height: 100%; width: 100%; object-fit: cover; object-position: right center;">
                <div class="hidden sm:block absolute inset-y-0 left-0 w-40 lg:w-56 bg-gradient-to-r from-[#0B0D10] to-transparent"></div>
                <div class="sm:hidden absolute inset-0 bg-gradient-to-t from-[#0B0D10] via-[#0B0D10]/75 to-transparent"></div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20">
                <!-- Breadcrumbs -->
                <nav class="flex items-center space-x-2 text-[11px] text-zinc-400 mb-3 sm:mb-4">
                    <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
                    <span>/</span>
                    <a href="{{ route('shop.index') }}" class="hover:text-white transition-colors">Catalog</a>
                    <span>/</span>
                    <span class="text-[#DFCAAB] font-bold">Round Neck T-Shirts</span>
                </nav>

                <div class="max-w-xl lg:max-w-2xl space-y-3 sm:space-y-4">
                    <!-- Gold Atelier Badge -->
                    <div class="inline-flex items-center space-x-2 px-3.5 py-1 rounded-full bg-white/10 backdrop-blur-md border border-[#DFCAAB]/30 text-[#DFCAAB] text-[10px] sm:text-xs font-bold uppercase tracking-widest">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#DFCAAB] animate-pulse"></span>
                        <span>✦ FEATHERWEIGHT HAUTE COTTON • 100% COMBED COMPACT YARN</span>
                    </div>

                    <!-- Editorial Headline -->
                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-serif-luxury font-bold text-white tracking-tight leading-tight">
                        Round Neck <span class="gold-gradient-text-light">Luxury Tees</span>
                    </h1>

                    <!-- Editorial Subtitle (Punch Line) -->
                    <p class="text-xs sm:text-sm text-zinc-300 font-medium leading-relaxed max-w-xl">
                        Sculpted drape designed for effortless, understated luxury.
                    </p>

                    <!-- 4 Craftsmanship Pills -->
                    <div class="flex flex-wrap items-center gap-2 pt-1 text-[11px]">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-zinc-900/90 border border-zinc-700/80 text-zinc-200">
                            ✦ 100% Combed Compact Cotton
                        </span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-zinc-900/90 border border-zinc-700/80 text-zinc-200">
                            ✦ Reinforced Anti-Sag Ribbed Neck
                        </span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-zinc-900/90 border border-zinc-700/80 text-zinc-200">
                            ✦ Zero-Friction Flatlock Seams
                        </span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-zinc-900/90 border border-zinc-700/80 text-zinc-200">
                            ✦ 200 GSM Featherweight Drape
                        </span>
                    </div>

                    <!-- 4 Key Performance Metrics Bar -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-3 sm:pt-4 border-t border-zinc-800/80 max-w-xl">
                        <div>
                            <span class="block text-base sm:text-lg font-serif-luxury font-bold text-[#DFCAAB]">200 GSM</span>
                            <span class="text-[10px] text-zinc-400 uppercase tracking-wider">Featherweight Drape</span>
                        </div>
                        <div>
                            <span class="block text-base sm:text-lg font-serif-luxury font-bold text-[#DFCAAB]">Zero Sag</span>
                            <span class="text-[10px] text-zinc-400 uppercase tracking-wider">Reinforced Collar</span>
                        </div>
                        <div>
                            <span class="block text-base sm:text-lg font-serif-luxury font-bold text-[#DFCAAB]">100% Pure</span>
                            <span class="text-[10px] text-zinc-400 uppercase tracking-wider">Combed Cotton</span>
                        </div>
                        <div>
                            <span class="block text-base sm:text-lg font-serif-luxury font-bold text-[#DFCAAB]">4.9 / 5.0 ★</span>
                            <span class="text-[10px] text-zinc-400 uppercase tracking-wider">Patron Verified</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif(request('collection') === 'new-arrivals' || request('category') === 'new-arrival-t-shirts')
        <!-- Dedicated Editorial Luxury Header: New Season Arrivals -->
        <div class="relative bg-[#0B0D10] text-white pt-6 pb-6 sm:pt-10 sm:pb-8 border-b border-zinc-800 overflow-hidden min-h-[320px] sm:min-h-[380px]">
            <!-- Full-Visibility Photography (No Dots, Clear) -->
            <div class="absolute right-0 top-0 bottom-0 w-full sm:w-7/12 lg:w-3/5 overflow-hidden pointer-events-none">
                <img src="{{ asset('images/catalog/banners/new-arrivals-banner.jpg') }}" 
                     alt="New Season Luxury Drops" 
                     class="w-full h-full object-cover object-[right_center] filter brightness-100"
                     style="height: 100%; width: 100%; object-fit: cover; object-position: right center;">
                <div class="hidden sm:block absolute inset-y-0 left-0 w-40 lg:w-56 bg-gradient-to-r from-[#0B0D10] to-transparent"></div>
                <div class="sm:hidden absolute inset-0 bg-gradient-to-t from-[#0B0D10] via-[#0B0D10]/75 to-transparent"></div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20">
                <!-- Breadcrumbs -->
                <nav class="flex items-center space-x-2 text-[11px] text-zinc-400 mb-3 sm:mb-4">
                    <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
                    <span>/</span>
                    <a href="{{ route('shop.index') }}" class="hover:text-white transition-colors">Catalog</a>
                    <span>/</span>
                    <span class="text-[#DFCAAB] font-bold">New Season Drops</span>
                </nav>

                <div class="max-w-xl lg:max-w-2xl space-y-3 sm:space-y-4">
                    <!-- Gold Atelier Badge -->
                    <div class="inline-flex items-center space-x-2 px-3.5 py-1 rounded-full bg-white/10 backdrop-blur-md border border-[#DFCAAB]/30 text-[#DFCAAB] text-[10px] sm:text-xs font-bold uppercase tracking-widest">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#DFCAAB] animate-pulse"></span>
                        <span>✦ HAUTE SEASON DROP • LIMITED EDITION RUNS</span>
                    </div>

                    <!-- Editorial Headline -->
                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-serif-luxury font-bold text-white tracking-tight leading-tight">
                        New Season <span class="gold-gradient-text-light">Arrivals</span>
                    </h1>

                    <!-- Editorial Subtitle (Punch Line) -->
                    <p class="text-xs sm:text-sm text-zinc-300 font-medium leading-relaxed max-w-xl">
                        Limited-run silhouettes and rare blends, fresh from the Jacario Atelier.
                    </p>

                    <!-- 4 Craftsmanship Pills -->
                    <div class="flex flex-wrap items-center gap-2 pt-1 text-[11px]">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-zinc-900/90 border border-zinc-700/80 text-zinc-200">
                            ✦ Limited Small Batch
                        </span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-zinc-900/90 border border-zinc-700/80 text-zinc-200">
                            ✦ Seasonal Riviera Colorways
                        </span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-zinc-900/90 border border-zinc-700/80 text-zinc-200">
                            ✦ Supima® & Silk Blends
                        </span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-zinc-900/90 border border-zinc-700/80 text-zinc-200">
                            ✦ Architectural Tailoring
                        </span>
                    </div>

                    <!-- 4 Key Performance Metrics Bar -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-3 sm:pt-4 border-t border-zinc-800/80 max-w-xl">
                        <div>
                            <span class="block text-base sm:text-lg font-serif-luxury font-bold text-[#DFCAAB]">14 Drops</span>
                            <span class="text-[10px] text-zinc-400 uppercase tracking-wider">Limited Run</span>
                        </div>
                        <div>
                            <span class="block text-base sm:text-lg font-serif-luxury font-bold text-[#DFCAAB]">100% Exclusive</span>
                            <span class="text-[10px] text-zinc-400 uppercase tracking-wider">First Access</span>
                        </div>
                        <div>
                            <span class="block text-base sm:text-lg font-serif-luxury font-bold text-[#DFCAAB]">Eco-Dye</span>
                            <span class="text-[10px] text-zinc-400 uppercase tracking-wider">Rich Saturation</span>
                        </div>
                        <div>
                            <span class="block text-base sm:text-lg font-serif-luxury font-bold text-[#DFCAAB]">4.9 / 5.0 ★</span>
                            <span class="text-[10px] text-zinc-400 uppercase tracking-wider">Patron Verified</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Running 3-Banner Shop Header Carousel -->
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
                        
                        <div class="absolute right-0 top-0 bottom-0 w-full sm:w-7/12 lg:w-3/5 overflow-hidden pointer-events-none">
                            <img src="{{ $sBanner->image_url ?? $sBanner->image_path }}" 
                                 alt="{{ $sBanner->title }}" 
                                 class="w-full h-full object-cover object-[center_25%] filter brightness-100"
                                 style="height: 100%; width: 100%; object-fit: cover;">
                            <div class="hidden sm:block absolute inset-y-0 left-0 w-40 lg:w-56 bg-gradient-to-r from-zinc-950 to-transparent"></div>
                            <div class="sm:hidden absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/70 to-transparent"></div>
                        </div>

                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20 h-full flex flex-col justify-between">
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

            <!-- Banner Carousel Controls -->
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
    @endif

    <!-- Category Filter Bar (Scrollable on Mobile) -->
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
                   class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-colors {{ request('category') === $cat->slug ? 'bg-[#0B0D10] text-[#DFCAAB] ring-1 ring-[#DFCAAB]/40' : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200' }}">
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
            <!-- Active Filters Quick Bar & Count -->
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-xs text-zinc-500 font-medium">
                    Showing <span class="font-bold text-zinc-900">{{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}</span> of <span class="font-bold text-zinc-900">{{ $products->total() }}</span> Silhouettes
                </span>

                @if($activeFilterCount > 0)
                    <span class="text-zinc-300 mx-1">|</span>
                    
                    @if(request('q'))
                        <span class="inline-flex items-center space-x-1 px-2.5 py-1 bg-zinc-100 border border-zinc-200 text-zinc-800 rounded-full text-xs font-medium">
                            <span>"{{ request('q') }}"</span>
                            <a href="{{ request()->fullUrlWithQuery(['q' => null]) }}" class="hover:text-black">✕</a>
                        </span>
                    @endif

                    @if(request('category'))
                        <span class="inline-flex items-center space-x-1 px-2.5 py-1 bg-zinc-100 border border-zinc-200 text-zinc-800 rounded-full text-xs font-medium">
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

                    @if(request('fabric'))
                        <span class="inline-flex items-center space-x-1 px-2.5 py-1 bg-[#0B0D10] text-[#DFCAAB] rounded-full text-xs font-medium">
                            <span>Fabric: {{ request('fabric') }}</span>
                            <a href="{{ request()->fullUrlWithQuery(['fabric' => null]) }}" class="hover:text-white">✕</a>
                        </span>
                    @endif

                    @if(request('fit'))
                        @foreach((array)request('fit') as $f)
                            <span class="inline-flex items-center space-x-1 px-2.5 py-1 bg-zinc-100 border border-zinc-200 text-zinc-800 rounded-full text-xs font-medium">
                                <span>Fit: {{ $f }}</span>
                                <a href="{{ request()->fullUrlWithQuery(['fit' => array_diff((array)request('fit'), [$f])]) }}" class="hover:text-black">✕</a>
                            </span>
                        @endforeach
                    @endif

                    @if(request('size'))
                        @foreach((array)request('size') as $s)
                            <span class="inline-flex items-center space-x-1 px-2.5 py-1 bg-zinc-100 border border-zinc-200 text-zinc-800 rounded-full text-xs font-medium">
                                <span>Size: {{ $s }}</span>
                                <a href="{{ request()->fullUrlWithQuery(['size' => array_diff((array)request('size'), [$s])]) }}" class="hover:text-black">✕</a>
                            </span>
                        @endforeach
                    @endif

                    @if(request('color'))
                        @foreach((array)request('color') as $c)
                            <span class="inline-flex items-center space-x-1 px-2.5 py-1 bg-zinc-100 border border-zinc-200 text-zinc-800 rounded-full text-xs font-medium">
                                <span>Color: {{ $c }}</span>
                                <a href="{{ request()->fullUrlWithQuery(['color' => array_diff((array)request('color'), [$c])]) }}" class="hover:text-black">✕</a>
                            </span>
                        @endforeach
                    @endif

                    @if(request('min_price') || request('max_price'))
                        <span class="inline-flex items-center space-x-1 px-2.5 py-1 bg-zinc-100 border border-zinc-200 text-zinc-800 rounded-full text-xs font-medium">
                            <span>₹{{ request('min_price', 0) }} - ₹{{ request('max_price', 'Any') }}</span>
                            <a href="{{ request()->fullUrlWithQuery(['min_price' => null, 'max_price' => null]) }}" class="hover:text-black">✕</a>
                        </span>
                    @endif

                    <a href="{{ route('shop.index') }}" class="text-xs text-rose-600 hover:underline font-semibold ml-2">Clear All</a>
                @endif
            </div>

            <!-- Toolbar Actions: View Toggle + Sorting -->
            <div class="flex items-center space-x-3">
                <!-- Grid / List Switcher -->
                <div class="inline-flex items-center p-0.5 bg-zinc-100 rounded-lg border border-zinc-200">
                    <button type="button" 
                            @click="viewMode = 'grid'; localStorage.setItem('jacario_view_mode', 'grid')" 
                            :class="viewMode === 'grid' ? 'bg-[#0B0D10] text-[#DFCAAB] shadow-xs' : 'text-zinc-500 hover:text-black'" 
                            class="p-1.5 rounded-md transition-all" 
                            title="Grid View">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                    </button>
                    <button type="button" 
                            @click="viewMode = 'list'; localStorage.setItem('jacario_view_mode', 'list')" 
                            :class="viewMode === 'list' ? 'bg-[#0B0D10] text-[#DFCAAB] shadow-xs' : 'text-zinc-500 hover:text-black'" 
                            class="p-1.5 rounded-md transition-all" 
                            title="List View">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                        </svg>
                    </button>
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
                    <!-- 1. Grid View (When viewMode === 'grid') -->
                    <div x-show="viewMode === 'grid'" class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-4 lg:gap-6">
                        @foreach($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>

                    <!-- 2. List View (When viewMode === 'list') -->
                    <div x-show="viewMode === 'list'" style="display: none;" class="space-y-4">
                        @foreach($products as $product)
                            @php
                                $pImg = $product->primaryImage;
                                $pColors = $product->availableColors();
                                $pSizes = $product->availableSizes();
                            @endphp
                            <div class="bg-white rounded-2xl border border-zinc-200/90 overflow-hidden hover:shadow-lg transition-all duration-300 flex flex-col sm:flex-row p-3 sm:p-4 gap-4 items-center">
                                <!-- Thumbnail Left -->
                                <div class="relative w-full sm:w-44 aspect-[4/5] bg-zinc-100 rounded-xl overflow-hidden shrink-0">
                                    <a href="{{ route('products.show', $product->slug) }}" class="block w-full h-full">
                                        <img src="{{ $pImg ? $pImg->url : asset('images/placeholder-polo.svg') }}" 
                                             alt="{{ $product->name }}" 
                                             class="w-full h-full object-cover">
                                    </a>
                                </div>

                                <!-- Content Center/Right -->
                                <div class="flex-1 w-full space-y-2.5">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] font-bold uppercase tracking-widest text-[#8C6D46]">
                                            JACARIO • {{ $product->category->name }}
                                        </span>
                                        <!-- Wishlist toggle -->
                                        <button type="button" 
                                                @click="$store.wishlist.toggle({{ $product->id }}, $el)" 
                                                class="p-1.5 rounded-full text-zinc-400 hover:text-rose-600 hover:bg-zinc-100 transition-colors">
                                            <svg class="w-4 h-4 fill-none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                        </button>
                                    </div>

                                    <h3 class="text-base sm:text-lg font-serif-luxury font-bold text-zinc-900 leading-snug">
                                        <a href="{{ route('products.show', $product->slug) }}" class="hover:text-black transition-colors">
                                            {{ $product->name }}
                                        </a>
                                    </h3>

                                    <!-- Rating & Specs -->
                                    <div class="flex items-center flex-wrap gap-2 text-xs">
                                        <div class="inline-flex items-center space-x-1 px-2 py-0.5 rounded bg-zinc-50 border border-zinc-200 text-amber-500 font-bold text-[11px]">
                                            <span>{{ number_format($product->average_rating, 1) }}</span>
                                            <svg class="w-3 h-3 fill-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            <span class="text-zinc-500 font-semibold">({{ $product->daily_views_count }})</span>
                                        </div>
                                        <span class="px-2 py-0.5 rounded bg-zinc-100 text-zinc-600 text-[10px] font-medium">{{ $product->fabric }}</span>
                                        <span class="px-2 py-0.5 rounded bg-zinc-100 text-zinc-600 text-[10px] font-medium">{{ $product->fit }}</span>
                                    </div>

                                    <!-- Price Line -->
                                    <div class="flex items-baseline space-x-2 pt-1">
                                        <span class="text-base sm:text-lg font-bold text-zinc-950">₹{{ number_format($product->effective_price) }}</span>
                                        @if($product->has_discount)
                                            <span class="text-xs text-zinc-400 line-through">₹{{ number_format($product->base_price) }}</span>
                                            <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">
                                                Save ₹{{ number_format($product->base_price - $product->sale_price) }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Available Sizes & Action CTA -->
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-2 border-t border-zinc-100">
                                        <div class="flex items-center space-x-1">
                                            <span class="text-[11px] text-zinc-400 mr-1">Sizes:</span>
                                            @foreach($pSizes as $sz)
                                                <span class="px-1.5 py-0.5 rounded bg-zinc-50 border border-zinc-200 text-[10px] font-bold text-zinc-700 uppercase">{{ $sz->name }}</span>
                                            @endforeach
                                        </div>

                                        <a href="{{ route('products.show', $product->slug) }}" class="inline-flex items-center justify-center px-4 py-2 bg-[#0B0D10] text-[#DFCAAB] text-xs font-bold uppercase tracking-wider rounded-lg hover:bg-black transition-colors shadow-xs">
                                            <span>View Details</span>
                                            <svg class="w-3.5 h-3.5 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
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

    <!-- JACARIO ATELIER CRAFTSMANSHIP GUARANTEE & PROMISE -->
    <section class="border-t border-zinc-200 bg-white py-12 sm:py-16 mb-16 lg:mb-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(request('category') === 'round-neck-t-shirts')
                <!-- Round Neck Specific Guarantee -->
                <div class="text-center max-w-2xl mx-auto mb-10">
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-widest text-[#8C6D46] bg-[#DFCAAB]/20 px-3.5 py-1.5 rounded-full inline-block">
                        ✦ THE ANATOMY OF A JACARIO LUXURY TEE
                    </span>
                    <h3 class="text-2xl sm:text-3xl font-serif-luxury font-bold text-zinc-950 mt-3 mb-2">
                        Featherweight Combed Cotton Meets Zero-Sag Architecture
                    </h3>
                    <p class="text-xs sm:text-sm text-zinc-600 leading-relaxed font-light">
                        Every JACARIO round neck tee is knit from ultra-fine compact cotton and engineered with micro-rib reinforcement so your neckline stays sharp and defined wash after wash.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Feature 1 -->
                    <div class="p-6 rounded-2xl bg-[#FAF8F5] border border-zinc-200/80 hover:border-zinc-300 transition-all group">
                        <div class="w-11 h-11 rounded-xl bg-[#0B0D10] text-[#DFCAAB] flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <h4 class="text-sm font-bold text-zinc-900 mb-1.5">Anti-Sag Ribbed Collar</h4>
                        <p class="text-xs text-zinc-600 leading-relaxed font-light">
                            Lycra-infused 1x1 micro-rib recovery collar that withstands 50+ wash cycles without stretching, drooping, or curling.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="p-6 rounded-2xl bg-[#FAF8F5] border border-zinc-200/80 hover:border-zinc-300 transition-all group">
                        <div class="w-11 h-11 rounded-xl bg-[#0B0D10] text-[#DFCAAB] flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        </div>
                        <h4 class="text-sm font-bold text-zinc-900 mb-1.5">100% Combed Compact Cotton</h4>
                        <p class="text-xs text-zinc-600 leading-relaxed font-light">
                            Super-combed 30s compact spun yarn providing zero surface fuzzing, buttery softness, and featherweight drape.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="p-6 rounded-2xl bg-[#FAF8F5] border border-zinc-200/80 hover:border-zinc-300 transition-all group">
                        <div class="w-11 h-11 rounded-xl bg-[#0B0D10] text-[#DFCAAB] flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        </div>
                        <h4 class="text-sm font-bold text-zinc-900 mb-1.5">Flatlock Seam Construction</h4>
                        <p class="text-xs text-zinc-600 leading-relaxed font-light">
                            Ultra-flat ergonomic shoulder seams eliminate skin friction and preserve seamless drape under jackets or overshirts.
                        </p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="p-6 rounded-2xl bg-[#FAF8F5] border border-zinc-200/80 hover:border-zinc-300 transition-all group">
                        <div class="w-11 h-11 rounded-xl bg-[#0B0D10] text-[#DFCAAB] flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </div>
                        <h4 class="text-sm font-bold text-zinc-900 mb-1.5">7-Day Concierge Trial</h4>
                        <p class="text-xs text-zinc-600 leading-relaxed font-light">
                            Try our tailored fit in your home wardrobe with complimentary doorstep exchanges and express carbon-neutral delivery.
                        </p>
                    </div>
                </div>
            @elseif(request('collection') === 'new-arrivals' || request('category') === 'new-arrival-t-shirts')
                <!-- New Arrivals Specific Guarantee -->
                <div class="text-center max-w-2xl mx-auto mb-10">
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-widest text-[#8C6D46] bg-[#DFCAAB]/20 px-3.5 py-1.5 rounded-full inline-block">
                        ✦ JACARIO ATELIER LIMITED PRODUCTION ETHOS
                    </span>
                    <h3 class="text-2xl sm:text-3xl font-serif-luxury font-bold text-zinc-950 mt-3 mb-2">
                        Limited Small-Batch Runs Crafted for Connoisseurs
                    </h3>
                    <p class="text-xs sm:text-sm text-zinc-600 leading-relaxed font-light">
                        Our seasonal drops are produced in strictly numbered, small-batch artisan capsules to ensure unprecedented attention to detail, fabric purity, and tailoring integrity.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Feature 1 -->
                    <div class="p-6 rounded-2xl bg-[#FAF8F5] border border-zinc-200/80 hover:border-zinc-300 transition-all group">
                        <div class="w-11 h-11 rounded-xl bg-[#0B0D10] text-[#DFCAAB] flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <h4 class="text-sm font-bold text-zinc-900 mb-1.5">Numbered Small Batches</h4>
                        <p class="text-xs text-zinc-600 leading-relaxed font-light">
                            Small batch runs ensure zero compromise on high needle-per-inch sewing, pattern alignment, and sartorial finish.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="p-6 rounded-2xl bg-[#FAF8F5] border border-zinc-200/80 hover:border-zinc-300 transition-all group">
                        <div class="w-11 h-11 rounded-xl bg-[#0B0D10] text-[#DFCAAB] flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21a4 4 0 01-4-4 4 4 0 014-4 4 4 0 014 4 4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.486"/></svg>
                        </div>
                        <h4 class="text-sm font-bold text-zinc-900 mb-1.5">Eco-Reactive Dye Saturation</h4>
                        <p class="text-xs text-zinc-600 leading-relaxed font-light">
                            Lustrous, long-lasting pigments inspired by Mediterranean coastal palettes that never bleed, fade, or stiffen after washing.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="p-6 rounded-2xl bg-[#FAF8F5] border border-zinc-200/80 hover:border-zinc-300 transition-all group">
                        <div class="w-11 h-11 rounded-xl bg-[#0B0D10] text-[#DFCAAB] flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        </div>
                        <h4 class="text-sm font-bold text-zinc-900 mb-1.5">Bespoke Hardware & Trims</h4>
                        <p class="text-xs text-zinc-600 leading-relaxed font-light">
                            Signature cross-lock shank buttons, stay-flat collar interlining, and reinforced split hems on every drop.
                        </p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="p-6 rounded-2xl bg-[#FAF8F5] border border-zinc-200/80 hover:border-zinc-300 transition-all group">
                        <div class="w-11 h-11 rounded-xl bg-[#0B0D10] text-[#DFCAAB] flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </div>
                        <h4 class="text-sm font-bold text-zinc-900 mb-1.5">7-Day Concierge Trial</h4>
                        <p class="text-xs text-zinc-600 leading-relaxed font-light">
                            Try our fresh drops in your home wardrobe with complimentary doorstep exchanges and lifetime shape guarantee.
                        </p>
                    </div>
                </div>
            @else
                <!-- Men's Polo / Default Guarantee -->
                <div class="text-center max-w-2xl mx-auto mb-10">
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-widest text-[#8C6D46] bg-[#DFCAAB]/20 px-3.5 py-1.5 rounded-full inline-block">
                        ✦ THE ANATOMY OF A JACARIO POLO
                    </span>
                    <h3 class="text-2xl sm:text-3xl font-serif-luxury font-bold text-zinc-950 mt-3 mb-2">
                        Architectural Tailoring Meets Rare Fiber
                    </h3>
                    <p class="text-xs sm:text-sm text-zinc-600 leading-relaxed font-light">
                        Every JACARIO polo is engineered through 48 individual artisan steps to eliminate common collar curl, fabric pilling, and loss of drape after washing.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Feature 1 -->
                    <div class="p-6 rounded-2xl bg-[#FAF8F5] border border-zinc-200/80 hover:border-zinc-300 transition-all group">
                        <div class="w-11 h-11 rounded-xl bg-[#0B0D10] text-[#DFCAAB] flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <h4 class="text-sm font-bold text-zinc-900 mb-1.5">Anti-Curl Collar System</h4>
                        <p class="text-xs text-zinc-600 leading-relaxed font-light">
                            Reinforced with high-density micro-canvas interlining and heat-set edges to prevent curled or rolled collar edges permanently.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="p-6 rounded-2xl bg-[#FAF8F5] border border-zinc-200/80 hover:border-zinc-300 transition-all group">
                        <div class="w-11 h-11 rounded-xl bg-[#0B0D10] text-[#DFCAAB] flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        </div>
                        <h4 class="text-sm font-bold text-zinc-900 mb-1.5">100% American Supima®</h4>
                        <p class="text-xs text-zinc-600 leading-relaxed font-light">
                            Extra-long staple cotton fibers deliver a silkier touch, zero pill, and extraordinary natural luster that deepens with wear.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="p-6 rounded-2xl bg-[#FAF8F5] border border-zinc-200/80 hover:border-zinc-300 transition-all group">
                        <div class="w-11 h-11 rounded-xl bg-[#0B0D10] text-[#DFCAAB] flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        </div>
                        <h4 class="text-sm font-bold text-zinc-900 mb-1.5">Mother-of-Pearl Hardware</h4>
                        <p class="text-xs text-zinc-600 leading-relaxed font-light">
                            Hand-selected Australian trocas shell buttons, anchored with cross-lock shank stitching designed never to loosen or pop off.
                        </p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="p-6 rounded-2xl bg-[#FAF8F5] border border-zinc-200/80 hover:border-zinc-300 transition-all group">
                        <div class="w-11 h-11 rounded-xl bg-[#0B0D10] text-[#DFCAAB] flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </div>
                        <h4 class="text-sm font-bold text-zinc-900 mb-1.5">7-Day Concierge Trial</h4>
                        <p class="text-xs text-zinc-600 leading-relaxed font-light">
                            Try our tailored fit in your home. Enjoy complimentary doorstep exchanges, lifetime shape guarantee, and express delivery.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Myntra-Style Sticky Bottom Dual Action Bar on Mobile (SORT | FILTER) -->
    <div class="fixed bottom-16 inset-x-0 z-30 bg-white border-t border-zinc-200 lg:hidden shadow-[0_-4px_16px_rgba(0,0,0,0.08)] flex items-center h-12 lg:bottom-0">
        
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
