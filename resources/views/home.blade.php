@extends('layouts.app')

@section('title', 'JACARIO — The Polo, Perfected. | Premium Polo T-Shirts')
@section('meta_description', 'Discover JACARIO\'s luxury collection of 50 meticulously crafted Polo T-Shirts. Engineered from 100% American Supima® cotton, Mulberry silk blends, and stay-flat collars.')

@section('content')

    <!-- ==========================================
         1. HERO SECTION — 5 AUTOMATIC BANNERS
         ========================================== -->
    <section class="relative bg-zinc-950 text-white overflow-hidden select-none" 
             x-data="{
                currentSlide: 0,
                totalSlides: {{ count($banners) }},
                duration: 5000,
                progress: 0,
                interval: null,
                isPaused: false,
                touchStartX: 0,
                touchEndX: 0,

                init() {
                    this.startAutoplay();
                },
                startAutoplay() {
                    this.stopAutoplay();
                    this.progress = 0;
                    const stepMs = 50;
                    const stepIncrement = (stepMs / this.duration) * 100;
                    
                    this.interval = setInterval(() => {
                        if (!this.isPaused) {
                            this.progress += stepIncrement;
                            if (this.progress >= 100) {
                                this.nextSlide();
                            }
                        }
                    }, stepMs);
                },
                stopAutoplay() {
                    if (this.interval) {
                        clearInterval(this.interval);
                        this.interval = null;
                    }
                },
                nextSlide() {
                    this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
                    this.progress = 0;
                },
                prevSlide() {
                    this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
                    this.progress = 0;
                },
                goToSlide(index) {
                    this.currentSlide = index;
                    this.progress = 0;
                },
                togglePause() {
                    this.isPaused = !this.isPaused;
                },
                handleTouchStart(e) {
                    this.touchStartX = e.changedTouches[0].screenX;
                },
                handleTouchEnd(e) {
                    this.touchEndX = e.changedTouches[0].screenX;
                    if (this.touchStartX - this.touchEndX > 50) {
                        this.nextSlide();
                    } else if (this.touchEndX - this.touchStartX > 50) {
                        this.prevSlide();
                    }
                }
             }"
             @mouseenter="isPaused = true"
             @mouseleave="isPaused = false"
             @touchstart="handleTouchStart($event)"
             @touchend="handleTouchEnd($event)"
             @keydown.window.arrow-left="prevSlide()"
             @keydown.window.arrow-right="nextSlide()">

        <!-- Slides Container -->
        <div class="relative w-full h-[490px] sm:h-[600px] lg:h-[700px] overflow-hidden">
            @foreach($banners as $index => $banner)
                @php
                    $align = $banner->text_alignment ?? 'left';
                    $alignClass = match($align) {
                        'center' => 'items-center justify-center text-center max-w-3xl mx-auto',
                        'right' => 'items-center justify-end text-right max-w-2xl ml-auto',
                        default => 'items-center justify-start text-left max-w-2xl mr-auto',
                    };
                    $flexBtnClass = match($align) {
                        'center' => 'justify-center',
                        'right' => 'justify-end',
                        default => 'justify-start',
                    };
                    $secondaryText = match($banner->cta_text) {
                        'Explore All 50 Polos', 'Explore Stay-Flat Series' => 'The Atelier Story',
                        'Explore Haute Knits' => 'Our Fabric Guide',
                        'Shop Best Sellers' => 'All 50 Polos',
                        default => 'Explore All 50 Polos',
                    };
                    $secondaryUrl = match($banner->cta_text) {
                        'Explore All 50 Polos', 'Explore Stay-Flat Series' => route('about'),
                        'Explore Haute Knits' => route('about'),
                        default => route('shop.index'),
                    };
                @endphp

                <div class="absolute inset-0 w-full h-full transition-all duration-1000 ease-in-out"
                     x-show="currentSlide === {{ $index }}"
                     x-transition:enter="transition ease-out duration-1000"
                     x-transition:enter-start="opacity-0 scale-105"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-700"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     style="{{ $index === 0 ? '' : 'display: none;' }}">
                    
                    <!-- Background Imagery with High-Resolution Responsive Sources -->
                    <picture>
                        @if(!empty($banner->mobile_image_url) && $banner->mobile_image_url !== $banner->image_url)
                            <source media="(max-width: 640px)" srcset="{{ $banner->mobile_image_url }}">
                        @endif
                        <img src="{{ $banner->image_url }}" 
                             alt="{{ $banner->title }}" 
                             loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                             class="w-full h-full object-cover object-[center_15%] sm:object-[center_25%] transform transition-transform duration-1000 ease-out filter brightness-[0.88] sm:brightness-[0.90] contrast-[1.05]">
                    </picture>

                    <!-- Rich Luxury Gradient Overlays for High Legibility on Mobile & Desktop -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-black/20 lg:from-transparent pointer-events-none"></div>
                    <div class="absolute inset-0 bg-gradient-to-l from-black/85 via-black/55 to-transparent w-full lg:w-3/5 ml-auto pointer-events-none hidden lg:block"></div>
                    <div class="absolute inset-0 bg-radial-vignette opacity-35 pointer-events-none"></div>

                    <!-- Slide Editorial Content -->
                    <div class="absolute inset-0 flex items-end sm:items-center z-10">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pb-14 sm:pb-20 pt-4 flex flex-col lg:flex-row items-center justify-between gap-6">
                            
                            <!-- Left Floating Atelier Rack Pill (Desktop) -->
                            <div class="hidden lg:flex flex-col space-y-2.5 max-w-xs self-end mb-6">
                                <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-black/60 backdrop-blur-md border border-white/20 text-white text-[11px] font-semibold tracking-wide shadow-xl">
                                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                    <span>Atelier Studio Collection</span>
                                </div>
                            </div>

                            <!-- Right Content Panel -->
                            <div class="max-w-xl ml-auto text-left space-y-2.5 sm:space-y-5 w-full">
                                
                                @if($banner->badge_text)
                                    <div class="inline-flex items-center space-x-2 px-3 sm:px-4 py-1 sm:py-1.5 rounded-full bg-black/60 backdrop-blur-md border border-[#DFCAAB]/50 text-[#DFCAAB] text-[10px] sm:text-xs font-bold uppercase tracking-[0.2em] shadow-lg">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#DFCAAB] animate-pulse"></span>
                                        <span>{{ $banner->badge_text }}</span>
                                    </div>
                                @endif

                                <h1 class="text-2xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-serif-luxury font-bold tracking-tight text-white leading-tight drop-shadow-xl">
                                    {{ $banner->title }}
                                </h1>

                                @if($banner->subtitle)
                                    <p class="text-xs sm:text-base lg:text-lg text-zinc-200 font-light leading-relaxed drop-shadow-md max-w-lg line-clamp-2 sm:line-clamp-none">
                                        {{ $banner->subtitle }}
                                    </p>
                                @endif

                                <!-- CTA Buttons: Sleek Gold Pill + Secondary -->
                                <div class="pt-1.5 sm:pt-3 flex items-center gap-3 justify-start">
                                    <a href="{{ $banner->cta_url }}" 
                                       class="px-6 sm:px-9 py-3 sm:py-4 bg-[#DFCAAB] hover:bg-white text-zinc-950 text-xs font-bold uppercase tracking-[0.2em] rounded-full sm:rounded-xl transition-all duration-300 shadow-2xl hover:scale-105 hover:shadow-[#DFCAAB]/25 inline-flex items-center space-x-2 group">
                                        <span>{{ $banner->cta_text }}</span>
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </a>
                                    <a href="{{ $secondaryUrl }}" 
                                       class="hidden sm:inline-flex px-6 sm:px-8 py-3 sm:py-4 bg-white/10 hover:bg-white/20 backdrop-blur-md text-white text-xs font-bold uppercase tracking-[0.2em] rounded-xl transition-all border border-white/25 hover:border-white/50 text-center items-center justify-center">
                                        {{ $secondaryText }}
                                    </a>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- Previous / Next Navigation Arrows (Desktop) -->
        <button type="button" 
                @click="prevSlide()" 
                class="hidden md:flex absolute left-6 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-black/40 hover:bg-black/80 backdrop-blur-lg border border-white/25 text-white items-center justify-center transition-all duration-300 hover:scale-110 active:scale-95 shadow-xl hover:border-[#DFCAAB]" 
                aria-label="Previous Slide">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>

        <button type="button" 
                @click="nextSlide()" 
                class="hidden md:flex absolute right-6 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-black/40 hover:bg-black/80 backdrop-blur-lg border border-white/25 text-white items-center justify-center transition-all duration-300 hover:scale-110 active:scale-95 shadow-xl hover:border-[#DFCAAB]" 
                aria-label="Next Slide">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>

        <!-- Bottom Luxury Progress Bar & Slide Controller (Visible on Mobile & Desktop) -->
        <div class="absolute bottom-3 sm:bottom-6 inset-x-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between bg-black/60 backdrop-blur-xl border border-white/20 px-3.5 sm:px-5 py-1.5 sm:py-2.5 rounded-full sm:rounded-2xl max-w-[280px] sm:max-w-xl mx-auto shadow-2xl">
                    
                    <!-- Slide Counter -->
                    <div class="flex items-center space-x-1 text-[10px] sm:text-xs font-serif-luxury tracking-widest text-[#DFCAAB]">
                        <span class="font-bold text-xs sm:text-sm" x-text="String(currentSlide + 1).padStart(2, '0')">01</span>
                        <span class="text-zinc-500">/</span>
                        <span class="text-zinc-400" x-text="String(totalSlides).padStart(2, '0')">05</span>
                    </div>

                    <!-- Interactive Progress Indicators -->
                    <div class="flex items-center space-x-1.5 sm:space-x-3 flex-1 mx-2.5 sm:mx-6">
                        @foreach($banners as $index => $banner)
                            <button type="button" 
                                    @click="goToSlide({{ $index }})" 
                                    class="relative h-1 sm:h-2 flex-1 rounded-full bg-white/20 overflow-hidden group cursor-pointer transition-all"
                                    aria-label="Go to Slide {{ $index + 1 }}: {{ $banner->title }}">
                                <!-- Filled Bar on active, full on past, zero on future -->
                                <div class="absolute inset-y-0 left-0 bg-[#DFCAAB] rounded-full transition-all"
                                     :style="currentSlide === {{ $index }} 
                                                ? `width: ${progress}%; transition: width 50ms linear;` 
                                                : (currentSlide > {{ $index }} ? 'width: 100%;' : 'width: 0%;')">
                                </div>
                            </button>
                        @endforeach
                    </div>

                    <!-- Pause / Resume Controls -->
                    <div class="flex items-center space-x-1">
                        <button type="button" 
                                @click="togglePause()" 
                                class="p-1 sm:p-1.5 rounded-lg text-zinc-400 hover:text-white hover:bg-white/10 transition-colors"
                                :title="isPaused ? 'Resume Autoplay' : 'Pause Autoplay'">
                            <template x-if="!isPaused">
                                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                            </template>
                            <template x-if="isPaused">
                                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </template>
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </section>

    <!-- ==========================================
         2. TRUST & VALUE PILLARS STRIP
         ========================================== -->
    <section class="bg-white border-b border-zinc-200/80 py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center divide-x-0 md:divide-x divide-zinc-200">
                <div class="flex items-center justify-center space-x-2.5 py-1">
                    <svg class="w-5 h-5 text-[#8C6D46] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-xs font-bold text-zinc-900 tracking-tight">100% Supima® & Silk</span>
                </div>
                <div class="flex items-center justify-center space-x-2.5 py-1">
                    <svg class="w-5 h-5 text-[#8C6D46] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span class="text-xs font-bold text-zinc-900 tracking-tight">Anti-Curl Collar Guarantee</span>
                </div>
                <div class="flex items-center justify-center space-x-2.5 py-1">
                    <svg class="w-5 h-5 text-[#8C6D46] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span class="text-xs font-bold text-zinc-900 tracking-tight">15-Day Doorstep Exchanges</span>
                </div>
                <div class="flex items-center justify-center space-x-2.5 py-1">
                    <svg class="w-5 h-5 text-[#8C6D46] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span class="text-xs font-bold text-zinc-900 tracking-tight">Free Shipping Above ₹1,999</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ==========================================
         3. SECTION 1: NEW ARRIVALS (8 PRODUCTS)
         ========================================== -->
    <section class="py-10 sm:py-16 bg-[#FAF8F5]">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            
            <div class="flex items-end justify-between mb-6 sm:mb-10">
                <div>
                    <p class="text-[10px] sm:text-xs font-bold uppercase tracking-[0.25em] text-[#8C6D46] mb-1">New Season Drops</p>
                    <h2 class="text-xl sm:text-3xl font-serif-luxury font-bold text-zinc-950 tracking-tight">New Arrivals</h2>
                </div>
                <a href="{{ route('shop.index', ['collection' => 'new-arrivals']) }}" class="text-xs font-bold uppercase tracking-wider text-zinc-900 hover:text-[#8C6D46] transition-colors flex items-center space-x-1">
                    <span>Explore ({{ $newArrivals->count() }})</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <!-- 2-Col Mobile / 4-Col Desktop Product Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-6">
                @foreach($newArrivals as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

        </div>
    </section>

    <!-- ==========================================
         4. FIT & SILHOUETTES EXPLORER
         ========================================== -->
    <section class="py-12 sm:py-16 bg-white border-y border-zinc-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-2xl mx-auto mb-8 sm:mb-12">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#8C6D46] mb-1.5">Fit & Fabric Perfection</p>
                <h2 class="text-2xl sm:text-4xl font-serif-luxury font-bold text-zinc-950">Curated Silhouettes</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 sm:gap-6">
                <!-- 1. Tailored Regular Fit -->
                <a href="{{ route('shop.index', ['fit' => ['Regular Fit']]) }}" class="group relative rounded-3xl bg-zinc-900 overflow-hidden aspect-[4/5] border border-zinc-200 shadow-md">
                    <img src="https://images.unsplash.com/photo-1578932750294-f5075e85f44a?w=900&auto=format&fit=crop&q=80" alt="Regular Fit Polo" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-80 group-hover:opacity-90">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 right-6 space-y-1.5">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-[#DFCAAB]">Classic Silhouette</span>
                        <h3 class="text-xl font-serif-luxury font-bold text-white group-hover:text-[#DFCAAB] transition-colors">Tailored Regular Fit</h3>
                        <p class="text-xs text-zinc-300 font-light">Engineered for timeless proportions with balanced chest drape.</p>
                    </div>
                </a>

                <!-- 2. Sartorial Slim Fit -->
                <a href="{{ route('shop.index', ['fit' => ['Slim Fit']]) }}" class="group relative rounded-3xl bg-zinc-900 overflow-hidden aspect-[4/5] border border-zinc-200 shadow-md">
                    <img src="https://images.unsplash.com/photo-1516257984-b1b4d707412e?w=900&auto=format&fit=crop&q=80" alt="Slim Fit Polo" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-80 group-hover:opacity-90">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 right-6 space-y-1.5">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-[#DFCAAB]">Sculpted Cut</span>
                        <h3 class="text-xl font-serif-luxury font-bold text-white group-hover:text-[#DFCAAB] transition-colors">Sartorial Slim Fit</h3>
                        <p class="text-xs text-zinc-300 font-light">Tapered waist and contoured sleeves designed for athletic builds.</p>
                    </div>
                </a>

                <!-- 3. Mulberry Silk & Supima -->
                <a href="{{ route('shop.index', ['fabric' => 'Silk']) }}" class="group relative rounded-3xl bg-zinc-900 overflow-hidden aspect-[4/5] border border-zinc-200 shadow-md">
                    <img src="https://images.unsplash.com/photo-1520975954732-35dd22299614?w=900&auto=format&fit=crop&q=80" alt="Silk Blend Polo" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-80 group-hover:opacity-90">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 right-6 space-y-1.5">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-[#DFCAAB]">Haute Yarns</span>
                        <h3 class="text-xl font-serif-luxury font-bold text-white group-hover:text-[#DFCAAB] transition-colors">Mulberry Silk & Supima®</h3>
                        <p class="text-xs text-zinc-300 font-light">Ultra-fine gauge knit offering silken luster and thermal airflow.</p>
                    </div>
                </a>
            </div>

        </div>
    </section>

    <!-- ==========================================
         5. SECTION 2: BEST SELLERS (8 PRODUCTS)
         ========================================== -->
    <section class="py-10 sm:py-16 bg-[#FAF8F5]">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            
            <div class="flex items-end justify-between mb-6 sm:mb-10">
                <div>
                    <p class="text-[10px] sm:text-xs font-bold uppercase tracking-[0.25em] text-[#8C6D46] mb-1">Loved. Worn. Repeated.</p>
                    <h2 class="text-xl sm:text-3xl font-serif-luxury font-bold text-zinc-950 tracking-tight">Best Sellers</h2>
                </div>
                <a href="{{ route('shop.index', ['collection' => 'bestsellers']) }}" class="text-xs font-bold uppercase tracking-wider text-zinc-900 hover:text-[#8C6D46] transition-colors flex items-center space-x-1">
                    <span>View All ({{ $bestsellers->count() }})</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-6">
                @foreach($bestsellers as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

        </div>
    </section>

    <!-- ==========================================
         6. EDITORIAL PROMOTIONAL HIGHLIGHT
         ========================================== -->
    <section class="py-16 sm:py-20 bg-zinc-950 text-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-7 space-y-4 sm:space-y-6">
                    <span class="inline-block px-3 py-1 rounded-full bg-[#DFCAAB]/20 text-[#DFCAAB] text-[10px] sm:text-xs font-bold uppercase tracking-widest border border-[#DFCAAB]/30">
                        Atelier Craftsmanship
                    </span>
                    <h2 class="text-2xl sm:text-4xl lg:text-5xl font-serif-luxury font-bold text-white leading-tight">
                        Collar Engineering That Never Curls.
                    </h2>
                    <p class="text-xs sm:text-sm text-zinc-300 font-light leading-relaxed max-w-xl">
                        Ordinary polo collars warp and roll after a few laundry cycles. JACARIO shirts feature proprietary internal micro-fused interlining and reinforced plackets to stay crisp wear after wear.
                    </p>
                    <div>
                        <a href="{{ route('shop.index', ['fabric' => 'Supima']) }}" class="inline-block px-7 py-3.5 bg-[#DFCAAB] hover:bg-white text-zinc-950 text-xs font-bold uppercase tracking-widest rounded-xl transition-all shadow-lg">
                            Shop Supima® Series
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-5 grid grid-cols-2 gap-4">
                    <div class="p-6 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md text-center space-y-1">
                        <span class="text-3xl font-serif-luxury font-bold text-[#DFCAAB]">240</span>
                        <p class="text-[10px] text-zinc-400 uppercase tracking-widest">GSM Weight</p>
                    </div>
                    <div class="p-6 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md text-center space-y-1">
                        <span class="text-3xl font-serif-luxury font-bold text-[#DFCAAB]">100%</span>
                        <p class="text-[10px] text-zinc-400 uppercase tracking-widest">Mother of Pearl</p>
                    </div>
                    <div class="p-6 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md text-center space-y-1">
                        <span class="text-3xl font-serif-luxury font-bold text-[#DFCAAB]">50+</span>
                        <p class="text-[10px] text-zinc-400 uppercase tracking-widest">Wash Endurance</p>
                    </div>
                    <div class="p-6 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md text-center space-y-1">
                        <span class="text-3xl font-serif-luxury font-bold text-[#DFCAAB]">Zero</span>
                        <p class="text-[10px] text-zinc-400 uppercase tracking-widest">Shrink Rate</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==========================================
         7. SECTION 3: TRENDING POLOS (8 PRODUCTS)
         ========================================== -->
    <section class="py-10 sm:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            
            <div class="flex items-end justify-between mb-6 sm:mb-10">
                <div>
                    <p class="text-[10px] sm:text-xs font-bold uppercase tracking-[0.25em] text-[#8C6D46] mb-1">Seasonal Trending</p>
                    <h2 class="text-xl sm:text-3xl font-serif-luxury font-bold text-zinc-950 tracking-tight">Trending Polos</h2>
                </div>
                <a href="{{ route('shop.index') }}" class="text-xs font-bold uppercase tracking-wider text-zinc-900 hover:text-[#8C6D46] transition-colors flex items-center space-x-1">
                    <span>View All</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-6">
                @foreach($trendingPolos as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

        </div>
    </section>

    <!-- ==========================================
         8. SECTION 4: PREMIUM COLLECTION (8 PRODUCTS)
         ========================================== -->
    <section class="py-10 sm:py-16 bg-[#FAF8F5] border-t border-zinc-200/80">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            
            <div class="flex items-end justify-between mb-6 sm:mb-10">
                <div>
                    <p class="text-[10px] sm:text-xs font-bold uppercase tracking-[0.25em] text-[#8C6D46] mb-1">Mulberry Silk & Supima®</p>
                    <h2 class="text-xl sm:text-3xl font-serif-luxury font-bold text-zinc-950 tracking-tight">Premium Collection</h2>
                </div>
                <a href="{{ route('shop.index', ['fabric' => 'Silk']) }}" class="text-xs font-bold uppercase tracking-wider text-zinc-900 hover:text-[#8C6D46] transition-colors flex items-center space-x-1">
                    <span>Explore Haute Knits</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-6">
                @foreach($premiumCollection as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

        </div>
    </section>

    <!-- ==========================================
         MID-PAGE LUXURY PROMOTIONAL BANNER
         ========================================== -->
    <section class="py-10 sm:py-16 bg-zinc-950 text-white relative overflow-hidden border-y border-zinc-800">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center bg-gradient-to-br from-zinc-900/90 via-black/80 to-zinc-900/90 p-6 sm:p-12 rounded-3xl border border-white/10 shadow-2xl backdrop-blur-md">
                <div class="lg:col-span-7 space-y-4 sm:space-y-5">
                    <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-[#DFCAAB]/15 border border-[#DFCAAB]/30 text-[#DFCAAB] text-[10px] sm:text-xs font-bold uppercase tracking-widest">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#DFCAAB] animate-pulse"></span>
                        <span>Tactile Mastery</span>
                    </div>
                    <h2 class="text-2xl sm:text-4xl font-serif-luxury font-bold text-white leading-tight">
                        The Luxury of Weightless Warmth & Silken Drape.
                    </h2>
                    <p class="text-xs sm:text-sm text-zinc-300 font-light leading-relaxed max-w-xl">
                        Spun with Grade-6A Mulberry Silk and 100% Extra-Long Staple American Supima®, our knit polos deliver an unmistakably opulent texture with all-day microclimate airflow.
                    </p>
                    <div class="pt-2 flex flex-wrap items-center gap-3 sm:gap-4">
                        <a href="{{ route('shop.index', ['fabric' => 'Silk']) }}" class="px-7 py-3.5 bg-[#DFCAAB] hover:bg-white text-zinc-950 text-xs font-bold uppercase tracking-[0.2em] rounded-xl transition-all shadow-xl hover:scale-105">
                            Shop Silk-Knit Series
                        </a>
                        <a href="{{ route('shop.index', ['collection' => 'bestsellers']) }}" class="px-6 py-3.5 bg-white/10 hover:bg-white/20 text-white text-xs font-bold uppercase tracking-[0.2em] rounded-xl border border-white/20 transition-all">
                            View Customer Favorites
                        </a>
                    </div>
                </div>
                <div class="lg:col-span-5 relative aspect-[4/3] sm:aspect-[16/10] rounded-2xl overflow-hidden shadow-2xl border border-white/15 group">
                    <img src="https://images.unsplash.com/photo-1598033129183-c4f50c736f10?w=1000&auto=format&fit=crop&q=85" alt="Mulberry Silk Craftsmanship" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4 flex items-center justify-between text-white text-xs">
                        <span class="font-serif-luxury font-bold text-[#DFCAAB]">Grade-6A Silk Blend</span>
                        <span class="text-[11px] text-zinc-300 font-light">24-Gauge Micro-Piqué</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==========================================
         9. SECTION 5: EVERYDAY ESSENTIALS (8 PRODUCTS)
         ========================================== -->
    <section class="py-10 sm:py-16 bg-white border-t border-zinc-200/80">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            
            <div class="flex items-end justify-between mb-6 sm:mb-10">
                <div>
                    <p class="text-[10px] sm:text-xs font-bold uppercase tracking-[0.25em] text-[#8C6D46] mb-1">Daily Wardrobe Rotation</p>
                    <h2 class="text-xl sm:text-3xl font-serif-luxury font-bold text-zinc-950 tracking-tight">Everyday Essentials</h2>
                </div>
                <a href="{{ route('shop.index', ['max_price' => '1299']) }}" class="text-xs font-bold uppercase tracking-wider text-zinc-900 hover:text-[#8C6D46] transition-colors flex items-center space-x-1">
                    <span>Under ₹1,299</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-6">
                @foreach($everydayEssentials as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

        </div>
    </section>

    <!-- ==========================================
         10. SECTION 6: COMPLETE 50-PRODUCT CATALOG PREVIEW
         ========================================== -->
    <section class="py-12 sm:py-20 bg-[#FAF8F5] border-t border-zinc-200/80">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 text-center space-y-8">
            
            <div class="max-w-2xl mx-auto">
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-[0.25em] text-[#8C6D46] mb-1.5">Complete Archive</p>
                <h2 class="text-2xl sm:text-4xl font-serif-luxury font-bold text-zinc-950">All Polo T-Shirts ({{ $totalProductsCount }} Designs)</h2>
                <p class="text-xs sm:text-sm text-zinc-500 mt-2 font-light">Explore the complete 50-piece bespoke Polo catalog across 14 luxury colors and tailored fits.</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-5 gap-2.5 sm:gap-4 text-left">
                @foreach($allPolosPreview as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

            <!-- View All CTA -->
            <div class="pt-6">
                <a href="{{ route('shop.index') }}" class="inline-flex items-center space-x-3 px-10 py-4 bg-zinc-950 hover:bg-black text-[#DFCAAB] text-xs font-bold uppercase tracking-[0.2em] rounded-2xl transition-all shadow-xl hover:scale-105">
                    <span>View All {{ $totalProductsCount }} Products</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

        </div>
    </section>

    <!-- ==========================================
         11. BRAND PHILOSOPHY
         ========================================== -->
    <section class="py-16 sm:py-24 bg-[#F7F4EE] border-t border-[#E8E1D5]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                <div class="lg:col-span-6 space-y-5">
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#8C6D46]">The JACARIO Philosophy</p>
                    <h2 class="text-2xl sm:text-4xl font-serif-luxury font-bold text-zinc-950 leading-tight">
                        Obsessed with <br> One Single Garment.
                    </h2>
                    <p class="text-xs sm:text-sm text-zinc-700 leading-relaxed font-light">
                        We refused to build twenty mediocre clothing categories. We chose to master the single most versatile silhouette in menswear: <strong class="text-zinc-950 font-semibold">The Polo T-Shirt</strong>.
                    </p>
                    <div>
                        <a href="{{ route('about') }}" class="inline-flex items-center space-x-2 text-xs font-bold uppercase tracking-widest text-zinc-950 hover:text-[#8C6D46] transition-colors border-b-2 border-zinc-950 pb-1">
                            <span>Read Our Full Story</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-6 rounded-3xl bg-white p-8 sm:p-10 shadow-lg border border-zinc-200 space-y-6">
                    <div class="flex items-center space-x-4 pb-4 border-b border-zinc-100">
                        <span class="text-3xl font-serif-luxury font-bold text-zinc-950">50</span>
                        <p class="text-xs text-zinc-600 uppercase tracking-wider">Unique bespoke polo silhouettes in active seasonal rotation</p>
                    </div>
                    <div class="flex items-center space-x-4 pb-4 border-b border-zinc-100">
                        <span class="text-3xl font-serif-luxury font-bold text-[#8C6D46]">100%</span>
                        <p class="text-xs text-zinc-600 uppercase tracking-wider">Natural mother-of-pearl hardware ethically harvested from certified fisheries</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-3xl font-serif-luxury font-bold text-zinc-950">14</span>
                        <p class="text-xs text-zinc-600 uppercase tracking-wider">Signature colorways dyed with eco-friendly reactive pigments</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==========================================
         12. VERIFIED REVIEWS
         ========================================== -->
    <section class="py-16 sm:py-20 bg-white border-t border-zinc-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#8C6D46] mb-1.5">Verified Testimonials</p>
                <h2 class="text-2xl sm:text-3xl font-serif-luxury font-bold text-zinc-950">What Our Clients Say</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($featuredReviews->take(3) as $review)
                    <div class="p-6 sm:p-8 rounded-3xl bg-[#FAF8F5] border border-zinc-200 flex flex-col justify-between space-y-4">
                        <div class="space-y-2.5">
                            <div class="flex items-center space-x-1 text-amber-500">
                                @for($i = 0; $i < $review->rating; $i++)
                                    <svg class="w-4 h-4 fill-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                            <h4 class="text-sm font-bold text-zinc-950 font-serif-luxury">"{{ $review->title }}"</h4>
                            <p class="text-xs text-zinc-600 font-light leading-relaxed">{{ $review->comment }}</p>
                        </div>
                        <div class="pt-3 border-t border-zinc-200 flex items-center justify-between text-[11px]">
                            <span class="font-bold text-zinc-900">{{ $review->user ? $review->user->name : 'JACARIO Member' }}</span>
                            <span class="text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded">✓ Verified Purchase</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ==========================================
         13. VIP SOCIETY NEWSLETTER
         ========================================== -->
    <section class="py-16 bg-[#FAF8F5] border-t border-zinc-200">
        <div class="max-w-4xl mx-auto px-4 text-center space-y-5 bg-gradient-to-br from-[#F5EFEB] via-[#EFE7DE] to-[#E8DDD1] p-8 sm:p-14 rounded-3xl border border-[#DDCFBF]">
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#8C6D46]">Private Archive</p>
            <h2 class="text-2xl sm:text-4xl font-serif-luxury font-bold text-zinc-950">Join the JACARIO Society</h2>
            <p class="text-xs sm:text-sm text-zinc-600 max-w-md mx-auto font-light">
                Receive private release notifications and an exclusive ₹300 welcome code on your inaugural order.
            </p>

            <form action="{{ route('newsletter.subscribe') }}" method="POST" class="max-w-md mx-auto flex flex-col sm:flex-row gap-2 pt-2"
                  x-data="{ email: '', loading: false, successMsg: '' }"
                  @submit.prevent="
                    loading = true;
                    fetch('{{ route('newsletter.subscribe') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ email })
                    })
                    .then(res => res.json())
                    .then(data => {
                        loading = false;
                        successMsg = data.message;
                        email = '';
                    })
                    .catch(() => { loading = false; });
                  ">
                @csrf
                <input type="email" x-model="email" required placeholder="Enter your email address..." class="flex-1 px-4 py-3 rounded-xl bg-white border border-zinc-300 text-zinc-900 text-xs focus:outline-none focus:border-[#8C6D46]">
                <button type="submit" class="px-6 py-3 bg-zinc-950 hover:bg-black text-[#DFCAAB] text-xs font-bold uppercase tracking-widest rounded-xl transition-all shrink-0">
                    <span x-text="loading ? 'Joining...' : 'Subscribe'"></span>
                </button>
            </form>
            <div x-show="successMsg" class="text-xs font-bold text-[#8C6D46]" x-text="successMsg"></div>
        </div>
    </section>

@endsection
