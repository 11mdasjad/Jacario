@extends('layouts.app')

@section('title', 'JACARIO — The Polo, Perfected. | Luxury Polo & T-Shirt Store')
@section('meta_description', 'Discover JACARIO\'s luxury collection of meticulously crafted Polo and Round Neck T-Shirts. Engineered from 100% American Supima® cotton, Mulberry silk blends, and stay-flat collars.')

@section('content')

    <!-- ========================================================
         1. HERO SECTION — FULL SCREEN IMMERSIVE CAROUSEL
         ======================================================== -->
    <section class="w-full relative overflow-hidden select-none bg-[#F7F4EE]">
        <div class="relative w-full overflow-hidden select-none"
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
             @touchend="handleTouchEnd($event)">

            <!-- Slides Track (Full Width Responsive Height with Proper Proportions) -->
            <div class="relative w-full h-[380px] sm:h-[440px] md:h-[500px] lg:h-[560px] xl:h-[620px] overflow-hidden bg-[#EFEAE1]">
                @foreach($banners as $index => $banner)
                    <div class="absolute inset-0 w-full h-full transition-all duration-700 ease-in-out"
                         x-show="currentSlide === {{ $index }}"
                         x-transition:enter="transition ease-out duration-700"
                         x-transition:enter-start="opacity-0 scale-102"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-500"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-98"
                         style="{{ $index === 0 ? '' : 'display: none;' }}">
                        
                        @php
                            $isPoloSlide = str_contains($banner->image_path, 'hero-polos-define-you') || str_contains($banner->image_path, 'banner-polos-define-you');
                            $isRoundNeckSlide = str_contains($banner->image_path, 'hero-roundneck-essentials');
                            $isLightCustomSlide = $isPoloSlide || $isRoundNeckSlide;
                        @endphp

                        @if($isLightCustomSlide)
                            <!-- High-Res Custom Photographic Background -->
                            <picture>
                                <img src="{{ $banner->image_url }}" 
                                     alt="{{ $banner->title }}" 
                                     loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                     class="w-full h-full object-cover object-[78%_center] sm:object-center select-none">
                            </picture>

                            <!-- Subtle Mobile Scrim for Text Readability without altering Desktop look -->
                            <div class="absolute inset-0 bg-gradient-to-r from-[#F5F1EB]/95 via-[#F5F1EB]/75 to-transparent sm:bg-none pointer-events-none"></div>

                            <!-- Editorial Overlay Content (Left-Aligned, Exactly Matching Reference) -->
                            <div class="absolute inset-0 z-10 flex items-center pointer-events-none">
                                <div class="w-full max-w-7xl mx-auto px-6 sm:px-12 lg:px-16">
                                    <div class="max-w-xl lg:max-w-2xl space-y-2.5 sm:space-y-3.5 lg:space-y-4 pointer-events-auto">
                                        
                                        <!-- Top Badge / Category Tagline -->
                                        <div class="text-[10px] sm:text-xs font-bold uppercase tracking-[0.25em] text-zinc-600">
                                            {{ $banner->badge_text ?: ($isPoloSlide ? 'PREMIUM ESSENTIALS' : 'SUMMER COLLECTION') }}
                                        </div>

                                        <!-- Huge Bold Sans-Serif Headline -->
                                        @if($isPoloSlide)
                                            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-[54px] xl:text-[60px] font-black text-zinc-950 uppercase tracking-tight leading-[1.04]">
                                                POLOS THAT<br>DEFINE YOU
                                            </h1>
                                            <span class="sr-only">{{ $banner->title }}</span>
                                        @elseif($isRoundNeckSlide)
                                            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-[54px] xl:text-[60px] font-black text-zinc-950 uppercase tracking-tight leading-[1.04]">
                                                ROUND NECK<br>ESSENTIALS
                                            </h1>
                                            <span class="sr-only">{{ $banner->title }}</span>
                                        @else
                                            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-[54px] xl:text-[60px] font-black text-zinc-950 uppercase tracking-tight leading-[1.04]">
                                                {{ $banner->title }}
                                            </h1>
                                        @endif

                                        <!-- Subtitle Description -->
                                        <p class="text-xs sm:text-sm md:text-base text-zinc-600 font-medium max-w-md leading-relaxed">
                                            {{ $banner->subtitle }}
                                        </p>

                                        <!-- CTA Button (Black Pill with Arrow) -->
                                        <div class="pt-1 sm:pt-2">
                                            <a href="{{ $banner->cta_url }}" 
                                               class="inline-flex items-center space-x-2.5 px-6 sm:px-8 py-2.5 sm:py-3.5 bg-zinc-950 hover:bg-black text-white text-xs sm:text-sm font-bold uppercase tracking-wider rounded-md transition-all duration-200 shadow-md hover:shadow-xl hover:-translate-y-0.5 group">
                                                <span>{{ $banner->cta_text ?: ($isPoloSlide ? "Shop Men's Polo" : "Shop Round Neck") }}</span>
                                                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                            </a>
                                        </div>

                                        <!-- Bottom Trust Badges Bar (Inside Banner as per Reference) -->
                                        <div class="pt-3 sm:pt-5 lg:pt-6 flex flex-wrap items-center gap-3 sm:gap-6 lg:gap-7 border-t border-zinc-300/60 mt-3 sm:mt-5">
                                            <!-- Free Shipping -->
                                            <div class="flex items-center space-x-2.5">
                                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-zinc-800 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                                </svg>
                                                <div>
                                                    <div class="text-[11px] sm:text-xs font-bold text-zinc-900 leading-tight">Free Shipping</div>
                                                    <div class="text-[9px] sm:text-[10px] text-zinc-600 font-medium">On orders above ₹999</div>
                                                </div>
                                            </div>

                                            <div class="hidden sm:block w-px h-6 bg-zinc-300/80"></div>

                                            <!-- Easy Returns -->
                                            <div class="flex items-center space-x-2.5">
                                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-zinc-800 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                </svg>
                                                <div>
                                                    <div class="text-[11px] sm:text-xs font-bold text-zinc-900 leading-tight">Easy Returns</div>
                                                    <div class="text-[9px] sm:text-[10px] text-zinc-600 font-medium">7 days hassle free</div>
                                                </div>
                                            </div>

                                            <div class="hidden sm:block w-px h-6 bg-zinc-300/80"></div>

                                            <!-- Secure Payments -->
                                            <div class="flex items-center space-x-2.5">
                                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-zinc-800 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                                </svg>
                                                <div>
                                                    <div class="text-[11px] sm:text-xs font-bold text-zinc-900 leading-tight">Secure Payments</div>
                                                    <div class="text-[9px] sm:text-[10px] text-zinc-600 font-medium">100% safe & secure</div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Dark / Studio Luxury Editorial Layout -->
                            <picture>
                                <img src="{{ $banner->image_url }}" 
                                     alt="{{ $banner->title }}" 
                                     loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                     class="w-full h-full object-cover object-[75%_center] sm:object-center filter brightness-[0.85] contrast-[1.05]">
                            </picture>

                            <!-- Gradient Scrims for Text Readability -->
                            <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/60 to-transparent lg:w-3/4 pointer-events-none"></div>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent sm:hidden pointer-events-none"></div>

                            <!-- Editorial Overlay Content -->
                            <div class="absolute inset-0 flex items-center z-10 pointer-events-none">
                                <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-16 w-full">
                                    <div class="max-w-2xl space-y-3 sm:space-y-4 pointer-events-auto">
                                        
                                        <!-- Badge Pill -->
                                        @if($banner->badge_text)
                                            <div class="inline-flex items-center space-x-2">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#DFCAAB] text-zinc-950 text-[10px] sm:text-xs font-black uppercase tracking-wider shadow-md">
                                                    {{ $banner->badge_text }}
                                                </span>
                                            </div>
                                        @endif

                                        <!-- Bold Headline -->
                                        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-serif-luxury font-bold text-white tracking-tight leading-[1.1] drop-shadow-lg">
                                            {{ $banner->title }}
                                        </h1>

                                        <!-- Subtitle Description -->
                                        <p class="text-xs sm:text-base text-zinc-300 font-light leading-relaxed max-w-lg line-clamp-2 sm:line-clamp-none drop-shadow">
                                            {{ $banner->subtitle }}
                                        </p>

                                        <!-- CTA Action Buttons -->
                                        <div class="pt-2 sm:pt-4 flex items-center gap-3">
                                            <a href="{{ $banner->cta_url }}" 
                                               class="px-7 sm:px-9 py-3 sm:py-3.5 bg-[#0B0D10] hover:bg-black text-[#DFCAAB] border border-zinc-700 text-xs sm:text-sm font-extrabold uppercase tracking-widest rounded-full transition-all duration-200 shadow-xl hover:scale-105 inline-flex items-center space-x-2 group">
                                                <span>{{ $banner->cta_text ?: 'Shop Now' }}</span>
                                                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                            </a>

                                            <a href="{{ route('customize.index') }}" 
                                               class="px-5 sm:px-7 py-3 sm:py-3.5 bg-white/10 hover:bg-white/20 text-white text-xs sm:text-sm font-bold uppercase tracking-wider rounded-full backdrop-blur-md border border-white/20 transition-all">
                                                Custom Studio
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                @endforeach
            </div>

            <!-- Carousel Previous & Next Arrow Buttons (White circular pills as shown in user screenshot) -->
            <button type="button" 
                    @click="prevSlide()" 
                    class="absolute left-3 sm:left-6 top-1/2 -translate-y-1/2 z-20 w-9 h-9 sm:w-11 sm:h-11 rounded-full bg-white/95 hover:bg-white text-zinc-900 flex items-center justify-center transition-all hover:scale-105 active:scale-95 shadow-xl border border-zinc-200/60 focus:outline-none" 
                    aria-label="Previous Slide">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-zinc-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            </button>

            <button type="button" 
                    @click="nextSlide()" 
                    class="absolute right-3 sm:right-6 top-1/2 -translate-y-1/2 z-20 w-9 h-9 sm:w-11 sm:h-11 rounded-full bg-white/95 hover:bg-white text-zinc-900 flex items-center justify-center transition-all hover:scale-105 active:scale-95 shadow-xl border border-zinc-200/60 focus:outline-none" 
                    aria-label="Next Slide">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-zinc-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </button>

            <!-- Bottom Indicator Dots (Matching screenshot: active solid black dot, inactive white dots) -->
            <div class="absolute bottom-3 sm:bottom-5 inset-x-0 z-20 flex justify-center items-center space-x-2.5">
                @foreach($banners as $index => $banner)
                    <button type="button" 
                            @click="goToSlide({{ $index }})" 
                            class="transition-all duration-300 rounded-full !min-h-0 border-0 p-0 focus:outline-none"
                            :class="currentSlide === {{ $index }} ? 'w-2.5 h-2.5 sm:w-3 sm:h-3 bg-zinc-950 ring-2 ring-black/20' : 'w-2 h-2 sm:w-2.5 sm:h-2.5 bg-white/90 hover:bg-white border border-zinc-300/80 shadow-xs'"
                            aria-label="Go to Slide {{ $index + 1 }}">
                    </button>
                @endforeach
            </div>

        </div>
    </section>

    <!-- ========================================================
         2. TRUST & VALUE BADGES BAR (4 CLEAN CARDS)
         ======================================================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            
            <!-- 1. Free Shipping -->
            <div class="p-4 sm:p-5 rounded-2xl bg-white border border-zinc-200/90 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-shadow">
                <div class="w-11 h-11 rounded-2xl bg-amber-500/10 text-[#8C6D46] flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-xs sm:text-sm font-bold text-zinc-950">Free Express Delivery</h4>
                    <p class="text-[11px] text-zinc-500 mt-0.5">On orders above ₹1,999</p>
                </div>
            </div>

            <!-- 2. Genuine Supima Cotton -->
            <div class="p-4 sm:p-5 rounded-2xl bg-white border border-zinc-200/90 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-shadow">
                <div class="w-11 h-11 rounded-2xl bg-emerald-500/10 text-emerald-700 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-xs sm:text-sm font-bold text-zinc-950">100% Genuine Supima®</h4>
                    <p class="text-[11px] text-zinc-500 mt-0.5">American extra-long staple yarn</p>
                </div>
            </div>

            <!-- 3. Bespoke Studio Offers -->
            <div class="p-4 sm:p-5 rounded-2xl bg-white border border-zinc-200/90 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-shadow">
                <div class="w-11 h-11 rounded-2xl bg-purple-500/10 text-purple-700 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-xs sm:text-sm font-bold text-zinc-950">Bespoke Studio Offers</h4>
                    <p class="text-[11px] text-zinc-500 mt-0.5">Live 3D vector canvas preview</p>
                </div>
            </div>

            <!-- 4. 15-Day Doorstep Exchanges -->
            <div class="p-4 sm:p-5 rounded-2xl bg-white border border-zinc-200/90 shadow-xs flex items-center space-x-3.5 hover:shadow-md transition-shadow">
                <div class="w-11 h-11 rounded-2xl bg-blue-500/10 text-blue-700 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-xs sm:text-sm font-bold text-zinc-950">15-Day Easy Exchange</h4>
                    <p class="text-[11px] text-zinc-500 mt-0.5">Doorstep reverse pickup guarantee</p>
                </div>
            </div>

        </div>
    </section>

    <!-- ========================================================
         3. TRIO PROMO SPOTLIGHT CARDS (WEBISHOP 3-CARD BANNER)
         ======================================================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
            
            <!-- Card 1: Men's Supima Polo -->
            <div class="group relative rounded-3xl overflow-hidden p-6 sm:p-8 bg-gradient-to-br from-amber-500/15 via-amber-600/5 to-zinc-900 border border-amber-500/30 shadow-md hover:shadow-xl transition-all duration-300 flex flex-col justify-between min-h-[240px]">
                <div class="space-y-2 relative z-10">
                    <span class="inline-block px-2.5 py-0.5 rounded-full bg-amber-500 text-zinc-950 text-[10px] font-black uppercase tracking-wider">
                        UP TO 20% OFF
                    </span>
                    <h3 class="text-xl sm:text-2xl font-serif-luxury font-bold text-zinc-950 group-hover:text-[#8C6D46] transition-colors">
                        Men's Supima® Polo
                    </h3>
                    <p class="text-xs text-zinc-600">240 GSM anti-curl structured collar.</p>
                    <p class="text-sm font-extrabold text-zinc-900 pt-1">From ₹1,499</p>
                </div>
                <div class="pt-4 relative z-10">
                    <a href="{{ route('shop.index', ['category' => 'mens-polo-t-shirts']) }}" 
                       class="inline-flex items-center space-x-1.5 text-xs font-black uppercase tracking-wider text-zinc-950 group-hover:text-[#8C6D46] border-b-2 border-zinc-950 group-hover:border-[#8C6D46] pb-0.5 transition-colors">
                        <span>Shop Now</span>
                        <svg class="w-3.5 h-3.5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
                <img src="{{ asset('images/polos/black-polo.svg') }}" alt="Men's Polo" class="absolute -right-6 -bottom-6 w-44 h-44 object-contain opacity-40 group-hover:opacity-60 group-hover:scale-105 transition-all duration-500 pointer-events-none">
            </div>

            <!-- Card 2: Round Neck Essentials -->
            <div class="group relative rounded-3xl overflow-hidden p-6 sm:p-8 bg-gradient-to-br from-blue-500/15 via-blue-600/5 to-zinc-900 border border-blue-500/30 shadow-md hover:shadow-xl transition-all duration-300 flex flex-col justify-between min-h-[240px]">
                <div class="space-y-2 relative z-10">
                    <span class="inline-block px-2.5 py-0.5 rounded-full bg-blue-600 text-white text-[10px] font-black uppercase tracking-wider">
                        UP TO 30% OFF
                    </span>
                    <h3 class="text-xl sm:text-2xl font-serif-luxury font-bold text-zinc-950 group-hover:text-blue-800 transition-colors">
                        Round Neck T-Shirts
                    </h3>
                    <p class="text-xs text-zinc-600">Silken 220 GSM combed jersey drape.</p>
                    <p class="text-sm font-extrabold text-zinc-900 pt-1">From ₹899</p>
                </div>
                <div class="pt-4 relative z-10">
                    <a href="{{ route('shop.index', ['category' => 'round-neck-t-shirts']) }}" 
                       class="inline-flex items-center space-x-1.5 text-xs font-black uppercase tracking-wider text-zinc-950 group-hover:text-blue-800 border-b-2 border-zinc-950 group-hover:border-blue-800 pb-0.5 transition-colors">
                        <span>Shop Now</span>
                        <svg class="w-3.5 h-3.5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
                <img src="{{ asset('images/polos/royal-blue-polo.svg') }}" alt="Round Neck T-Shirt" class="absolute -right-6 -bottom-6 w-44 h-44 object-contain opacity-40 group-hover:opacity-60 group-hover:scale-105 transition-all duration-500 pointer-events-none">
            </div>

            <!-- Card 3: Customize Studio -->
            <div class="group relative rounded-3xl overflow-hidden p-6 sm:p-8 bg-gradient-to-br from-emerald-500/15 via-emerald-600/5 to-zinc-900 border border-emerald-500/30 shadow-md hover:shadow-xl transition-all duration-300 flex flex-col justify-between min-h-[240px]">
                <div class="space-y-2 relative z-10">
                    <span class="inline-block px-2.5 py-0.5 rounded-full bg-emerald-600 text-white text-[10px] font-black uppercase tracking-wider">
                        UP TO 35% OFF
                    </span>
                    <h3 class="text-xl sm:text-2xl font-serif-luxury font-bold text-zinc-950 group-hover:text-emerald-800 transition-colors">
                        Customize Studio
                    </h3>
                    <p class="text-xs text-zinc-600">Personalized monograms & corporate crests.</p>
                    <p class="text-sm font-extrabold text-zinc-900 pt-1">From ₹849</p>
                </div>
                <div class="pt-4 relative z-10">
                    <a href="{{ route('customize.index') }}" 
                       class="inline-flex items-center space-x-1.5 text-xs font-black uppercase tracking-wider text-zinc-950 group-hover:text-emerald-800 border-b-2 border-zinc-950 group-hover:border-emerald-800 pb-0.5 transition-colors">
                        <span>Shop Now</span>
                        <svg class="w-3.5 h-3.5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
                <img src="{{ asset('images/customizer/polo/royal-blue.jpg') }}" alt="Customize Studio" class="absolute -right-6 -bottom-6 w-44 h-44 object-cover rounded-2xl opacity-40 group-hover:opacity-60 group-hover:scale-105 transition-all duration-500 pointer-events-none">
            </div>

        </div>
    </section>

    <!-- ========================================================
         4. "SHOP BY CATEGORY" (SLIDER STRIP WITH PASTEL CARDS)
         ======================================================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12"
             x-data="{
                scrollLeft() {
                    this.$refs.catTrack.scrollBy({ left: -280, behavior: 'smooth' });
                },
                scrollRight() {
                    this.$refs.catTrack.scrollBy({ left: 280, behavior: 'smooth' });
                }
             }">
        
        <div class="flex items-center justify-between mb-6">
            <div>
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-[0.2em] text-[#8C6D46] mb-1">Catalog Explorer</p>
                <h2 class="text-2xl sm:text-3xl font-serif-luxury font-bold text-zinc-950 tracking-tight">Shop By Category</h2>
            </div>
            
            <!-- Navigation Slider Arrows -->
            <div class="flex items-center space-x-2">
                <button @click="scrollLeft()" 
                        type="button" 
                        class="w-9 h-9 rounded-full bg-white border border-zinc-300 hover:border-zinc-500 text-zinc-800 flex items-center justify-center transition-all shadow-xs hover:bg-zinc-50" 
                        aria-label="Previous Categories">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button @click="scrollRight()" 
                        type="button" 
                        class="w-9 h-9 rounded-full bg-white border border-zinc-300 hover:border-zinc-500 text-zinc-800 flex items-center justify-center transition-all shadow-xs hover:bg-zinc-50" 
                        aria-label="Next Categories">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>

        <!-- Horizontal Scrollable Track -->
        <div x-ref="catTrack" class="flex items-center space-x-4 overflow-x-auto no-scrollbar pb-3 snap-x">
            
            <!-- Category 1: Men's Polo T-Shirts -->
            <a href="{{ route('shop.index', ['category' => 'mens-polo-t-shirts']) }}" 
               class="min-w-[190px] sm:min-w-[210px] p-5 rounded-3xl bg-[#FBF6EF] border border-[#EFE5D5] flex flex-col items-center text-center group hover:shadow-lg transition-all duration-200 snap-start shrink-0">
                <div class="w-24 h-24 rounded-2xl bg-white p-2.5 shadow-xs mb-3 flex items-center justify-center group-hover:scale-105 transition-transform">
                    <img src="{{ asset('images/polos/black-polo.svg') }}" alt="Men's Polo" class="w-full h-full object-contain">
                </div>
                <h4 class="text-xs sm:text-sm font-bold text-zinc-900 group-hover:text-[#8C6D46] transition-colors">Men's Polo T-Shirts</h4>
                <p class="text-[11px] text-zinc-500 mt-0.5">25 Designs</p>
            </a>

            <!-- Category 2: Round Neck T-Shirts -->
            <a href="{{ route('shop.index', ['category' => 'round-neck-t-shirts']) }}" 
               class="min-w-[190px] sm:min-w-[210px] p-5 rounded-3xl bg-[#EFF4F9] border border-[#DEE7F0] flex flex-col items-center text-center group hover:shadow-lg transition-all duration-200 snap-start shrink-0">
                <div class="w-24 h-24 rounded-2xl bg-white p-2.5 shadow-xs mb-3 flex items-center justify-center group-hover:scale-105 transition-transform">
                    <img src="{{ asset('images/polos/royal-blue-polo.svg') }}" alt="Round Neck" class="w-full h-full object-contain">
                </div>
                <h4 class="text-xs sm:text-sm font-bold text-zinc-900 group-hover:text-[#8C6D46] transition-colors">Round Neck T-Shirts</h4>
                <p class="text-[11px] text-zinc-500 mt-0.5">12 Designs</p>
            </a>

            <!-- Category 3: New Arrival T-Shirts -->
            <a href="{{ route('shop.index', ['category' => 'new-arrival-t-shirts']) }}" 
               class="min-w-[190px] sm:min-w-[210px] p-5 rounded-3xl bg-[#F0F8F3] border border-[#DCEEE3] flex flex-col items-center text-center group hover:shadow-lg transition-all duration-200 snap-start shrink-0">
                <div class="w-24 h-24 rounded-2xl bg-white p-2.5 shadow-xs mb-3 flex items-center justify-center group-hover:scale-105 transition-transform">
                    <img src="{{ asset('images/polos/burgundy-polo.svg') }}" alt="New Arrivals" class="w-full h-full object-contain">
                </div>
                <h4 class="text-xs sm:text-sm font-bold text-zinc-900 group-hover:text-[#8C6D46] transition-colors">New Arrival T-Shirts</h4>
                <p class="text-[11px] text-zinc-500 mt-0.5">13 Designs</p>
            </a>

            <!-- Category 4: Mulberry Silk & Supima -->
            <a href="{{ route('shop.index', ['fabric' => 'Silk']) }}" 
               class="min-w-[190px] sm:min-w-[210px] p-5 rounded-3xl bg-[#F7F2FA] border border-[#EAE0F0] flex flex-col items-center text-center group hover:shadow-lg transition-all duration-200 snap-start shrink-0">
                <div class="w-24 h-24 rounded-2xl bg-white p-2.5 shadow-xs mb-3 flex items-center justify-center group-hover:scale-105 transition-transform">
                    <img src="{{ asset('images/polos/sand-polo.svg') }}" alt="Mulberry Silk" class="w-full h-full object-contain">
                </div>
                <h4 class="text-xs sm:text-sm font-bold text-zinc-900 group-hover:text-[#8C6D46] transition-colors">Mulberry Silk & Supima®</h4>
                <p class="text-[11px] text-zinc-500 mt-0.5">8 Luxury Knits</p>
            </a>

            <!-- Category 5: Tailored Regular Fit -->
            <a href="{{ route('shop.index', ['fit' => ['Regular Fit']]) }}" 
               class="min-w-[190px] sm:min-w-[210px] p-5 rounded-3xl bg-[#F8F5EE] border border-[#EBE3D3] flex flex-col items-center text-center group hover:shadow-lg transition-all duration-200 snap-start shrink-0">
                <div class="w-24 h-24 rounded-2xl bg-white p-2.5 shadow-xs mb-3 flex items-center justify-center group-hover:scale-105 transition-transform">
                    <img src="{{ asset('images/polos/olive-polo-detail.svg') }}" alt="Tailored Regular Fit" class="w-full h-full object-contain">
                </div>
                <h4 class="text-xs sm:text-sm font-bold text-zinc-900 group-hover:text-[#8C6D46] transition-colors">Tailored Regular Fit</h4>
                <p class="text-[11px] text-zinc-500 mt-0.5">25 Designs</p>
            </a>

            <!-- Category 6: Sartorial Slim Fit -->
            <a href="{{ route('shop.index', ['fit' => ['Slim Fit']]) }}" 
               class="min-w-[190px] sm:min-w-[210px] p-5 rounded-3xl bg-[#F0F5F8] border border-[#DFEBF2] flex flex-col items-center text-center group hover:shadow-lg transition-all duration-200 snap-start shrink-0">
                <div class="w-24 h-24 rounded-2xl bg-white p-2.5 shadow-xs mb-3 flex items-center justify-center group-hover:scale-105 transition-transform">
                    <img src="{{ asset('images/polos/navy-polo.svg') }}" alt="Sartorial Slim Fit" class="w-full h-full object-contain">
                </div>
                <h4 class="text-xs sm:text-sm font-bold text-zinc-900 group-hover:text-[#8C6D46] transition-colors">Sartorial Slim Fit</h4>
                <p class="text-[11px] text-zinc-500 mt-0.5">25 Designs</p>
            </a>

            <!-- Category 7: Jacario Customize Studio -->
            <a href="{{ route('customize.index') }}" 
               class="min-w-[190px] sm:min-w-[210px] p-5 rounded-3xl bg-[#FDF5E6] border border-[#F4E3C4] flex flex-col items-center text-center group hover:shadow-lg transition-all duration-200 snap-start shrink-0">
                <div class="w-24 h-24 rounded-2xl bg-zinc-950 p-2.5 shadow-xs mb-3 flex items-center justify-center group-hover:scale-105 transition-transform">
                    <svg class="w-10 h-10 text-[#DFCAAB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                </div>
                <h4 class="text-xs sm:text-sm font-bold text-zinc-900 group-hover:text-[#8C6D46] transition-colors">Customize Studio</h4>
                <p class="text-[11px] font-bold text-amber-700 mt-0.5">Bespoke Design</p>
            </a>

        </div>
    </section>

    <!-- ========================================================
         5. "POPULAR PRODUCTS" SHOWCASE (TABBED FILTER GRID)
         ======================================================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12"
             x-data="{ activeTab: 'all' }">
        
        <!-- Header with Filter Tabs -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
            <div>
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-[0.2em] text-[#8C6D46] mb-1">Curated Favorites</p>
                <h2 class="text-2xl sm:text-4xl font-serif-luxury font-bold text-zinc-950 tracking-tight">Popular Products</h2>
            </div>

            <!-- Tab Pills -->
            <div class="flex items-center space-x-1.5 sm:space-x-2 bg-zinc-100 p-1.5 rounded-full overflow-x-auto no-scrollbar">
                <button type="button" 
                        @click="activeTab = 'all'" 
                        :class="activeTab === 'all' ? 'bg-zinc-950 text-white shadow-xs' : 'text-zinc-700 hover:text-black'"
                        class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all !min-h-0 border-0 shrink-0">
                    All Products
                </button>

                <button type="button" 
                        @click="activeTab = 'polo'" 
                        :class="activeTab === 'polo' ? 'bg-zinc-950 text-white shadow-xs' : 'text-zinc-700 hover:text-black'"
                        class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all !min-h-0 border-0 shrink-0">
                    Men's Polo
                </button>

                <button type="button" 
                        @click="activeTab = 'round'" 
                        :class="activeTab === 'round' ? 'bg-zinc-950 text-white shadow-xs' : 'text-zinc-700 hover:text-black'"
                        class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all !min-h-0 border-0 shrink-0">
                    Round Neck
                </button>

                <button type="button" 
                        @click="activeTab = 'new'" 
                        :class="activeTab === 'new' ? 'bg-zinc-950 text-white shadow-xs' : 'text-zinc-700 hover:text-black'"
                        class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all !min-h-0 border-0 shrink-0">
                    New Arrivals
                </button>
            </div>
        </div>

        <!-- Tab 1: All Popular Products (Bestsellers) -->
        <div x-show="activeTab === 'all'" x-cloak class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            @foreach($bestsellers as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>

        <!-- Tab 2: Men's Polo T-Shirts -->
        <div x-show="activeTab === 'polo'" x-cloak class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6" style="display: none;">
            @php
                $poloSubset = \App\Models\Product::with(['category', 'images', 'activeVariants.color', 'activeVariants.size'])
                    ->active()
                    ->where('category_id', 1)
                    ->take(8)
                    ->get();
            @endphp
            @foreach($poloSubset as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>

        <!-- Tab 3: Round Neck T-Shirts -->
        <div x-show="activeTab === 'round'" x-cloak class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6" style="display: none;">
            @php
                $roundSubset = \App\Models\Product::with(['category', 'images', 'activeVariants.color', 'activeVariants.size'])
                    ->active()
                    ->where('category_id', 2)
                    ->take(8)
                    ->get();
            @endphp
            @foreach($roundSubset as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>

        <!-- Tab 4: New Arrivals -->
        <div x-show="activeTab === 'new'" x-cloak class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6" style="display: none;">
            @foreach($newArrivals as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>

    </section>

    <!-- ========================================================
         6. SECTION: NEW ARRIVALS (8 PRODUCTS)
         ======================================================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <div class="flex items-end justify-between mb-6">
            <div>
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-[0.2em] text-[#8C6D46] mb-1">New Season Drops</p>
                <h2 class="text-2xl sm:text-3xl font-serif-luxury font-bold text-zinc-950 tracking-tight">New Arrivals</h2>
            </div>
            <a href="{{ route('shop.index', ['category' => 'new-arrival-t-shirts']) }}" class="text-xs font-bold uppercase tracking-wider text-zinc-900 hover:text-[#8C6D46] transition-colors flex items-center space-x-1">
                <span>Explore ({{ $newArrivals->count() }})</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            @foreach($newArrivals as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>

    <!-- ========================================================
         7. SECTION: BEST SELLERS (8 PRODUCTS)
         ======================================================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 bg-white rounded-3xl border border-zinc-200/80 my-6">
        <div class="flex items-end justify-between mb-6">
            <div>
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-[0.2em] text-[#8C6D46] mb-1">Loved. Worn. Repeated.</p>
                <h2 class="text-2xl sm:text-3xl font-serif-luxury font-bold text-zinc-950 tracking-tight">Best Sellers</h2>
            </div>
            <a href="{{ route('shop.index', ['collection' => 'bestsellers']) }}" class="text-xs font-bold uppercase tracking-wider text-zinc-900 hover:text-[#8C6D46] transition-colors flex items-center space-x-1">
                <span>View All ({{ $bestsellers->count() }})</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            @foreach($bestsellers as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>

    <!-- ========================================================
         8. SECTION: TRENDING POLOS (8 PRODUCTS)
         ======================================================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <div class="flex items-end justify-between mb-6">
            <div>
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-[0.2em] text-[#8C6D46] mb-1">Seasonal Trending</p>
                <h2 class="text-2xl sm:text-3xl font-serif-luxury font-bold text-zinc-950 tracking-tight">Trending Polos</h2>
            </div>
            <a href="{{ route('shop.index') }}" class="text-xs font-bold uppercase tracking-wider text-zinc-900 hover:text-[#8C6D46] transition-colors flex items-center space-x-1">
                <span>View All</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            @foreach($trendingPolos as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>

    <!-- ========================================================
         9. SECTION: PREMIUM COLLECTION (MULBERRY SILK & SUPIMA)
         ======================================================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 bg-[#FAF8F5] rounded-3xl border border-zinc-200/80 my-6">
        <div class="flex items-end justify-between mb-6">
            <div>
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-[0.2em] text-[#8C6D46] mb-1">Mulberry Silk & Supima®</p>
                <h2 class="text-2xl sm:text-3xl font-serif-luxury font-bold text-zinc-950 tracking-tight">Premium Collection</h2>
            </div>
            <a href="{{ route('shop.index', ['fabric' => 'Silk']) }}" class="text-xs font-bold uppercase tracking-wider text-zinc-900 hover:text-[#8C6D46] transition-colors flex items-center space-x-1">
                <span>Explore Haute Knits</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            @foreach($premiumCollection as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>

    <!-- ========================================================
         10. SECTION: EVERYDAY ESSENTIALS (8 PRODUCTS)
         ======================================================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <div class="flex items-end justify-between mb-6">
            <div>
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-[0.2em] text-[#8C6D46] mb-1">Daily Wardrobe Rotation</p>
                <h2 class="text-2xl sm:text-3xl font-serif-luxury font-bold text-zinc-950 tracking-tight">Everyday Essentials</h2>
            </div>
            <a href="{{ route('shop.index', ['max_price' => '1299']) }}" class="text-xs font-bold uppercase tracking-wider text-zinc-900 hover:text-[#8C6D46] transition-colors flex items-center space-x-1">
                <span>Under ₹1,299</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            @foreach($everydayEssentials as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>

    <!-- ========================================================
         11. SECTION: ALL POLO T-SHIRTS ARCHIVE (PREVIEW)
         ======================================================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16 text-center space-y-8">
        <div class="max-w-2xl mx-auto">
            <p class="text-[10px] sm:text-xs font-bold uppercase tracking-[0.25em] text-[#8C6D46] mb-1.5">Complete Archive</p>
            <h2 class="text-2xl sm:text-4xl font-serif-luxury font-bold text-zinc-950">All Polo T-Shirts ({{ $totalProductsCount }} Designs)</h2>
            <p class="text-xs sm:text-sm text-zinc-500 mt-2 font-light">Explore the complete bespoke Polo and Round Neck catalog across 14 luxury colors and tailored fits.</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4 text-left">
            @foreach($allPolosPreview as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>

        <div class="pt-4">
            <a href="{{ route('shop.index') }}" class="inline-flex items-center space-x-3 px-10 py-4 bg-zinc-950 hover:bg-black text-[#DFCAAB] text-xs font-bold uppercase tracking-[0.2em] rounded-full transition-all shadow-xl hover:scale-105">
                <span>View All {{ $totalProductsCount }} Products</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </section>

    <!-- ========================================================
         12. BESPOKE CUSTOMIZE STUDIO REAL-TIME SHOWCASE
         ======================================================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">
        <div class="relative rounded-3xl bg-gradient-to-br from-[#0B0D10] via-zinc-950 to-[#121418] text-white p-8 sm:p-14 overflow-hidden border border-zinc-800 shadow-2xl">
            <!-- Background Glow -->
            <div class="absolute top-0 right-1/4 w-96 h-96 bg-[#A4845B]/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-0 left-10 w-80 h-80 bg-amber-600/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center relative z-10">
                <div class="lg:col-span-7 space-y-4 sm:space-y-6">
                    <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-[#18181B] border border-[#A4845B]/40 text-[#DFCAAB] text-[10px] sm:text-xs font-bold uppercase tracking-[0.2em]">
                        <span class="w-2 h-2 rounded-full bg-[#A4845B] animate-ping"></span>
                        <span>JACARIO BESPOKE STUDIO</span>
                    </div>

                    <h2 class="text-2xl sm:text-4xl lg:text-5xl font-serif-luxury font-bold text-white leading-tight">
                        Customize Your Polo & T-Shirt in Real Time.
                    </h2>

                    <p class="text-xs sm:text-sm text-zinc-300 font-normal leading-relaxed max-w-xl">
                        Design personalized luxury apparel with your company crest, club emblem, or signature typography. Zero minimum order quantity, live 3D vector canvas preview, and high-definition precision printing.
                    </p>

                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        <a href="{{ route('customize.index') }}" class="px-7 py-3.5 bg-gradient-to-r from-[#DFCAAB] via-[#F3E5D0] to-[#A4845B] text-zinc-950 text-xs font-extrabold uppercase tracking-widest rounded-xl hover:brightness-110 transition-all shadow-xl flex items-center space-x-2">
                            <span>Open Customize Studio</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                        <a href="{{ route('customize.studio', 'polo-t-shirt') }}" class="px-6 py-3.5 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all">
                            Customize Polo
                        </a>
                        <a href="{{ route('customize.studio', 't-shirt') }}" class="px-6 py-3.5 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all">
                            Customize T-Shirt
                        </a>
                    </div>
                </div>

                <!-- Right Visual Teasers -->
                <div class="lg:col-span-5 grid grid-cols-2 gap-4">
                    <div class="p-4 rounded-2xl bg-zinc-900/90 border border-zinc-800 text-center">
                        <div class="aspect-square bg-zinc-950 rounded-xl p-2 mb-3 flex items-center justify-center">
                            <img src="{{ asset('images/customizer/polo/black.jpg') }}" alt="Polo Preview" class="w-full h-full object-contain">
                        </div>
                        <h4 class="text-xs font-bold text-white">Custom Polo</h4>
                        <p class="text-[10px] text-[#DFCAAB] mt-1 font-semibold">From ₹1,299</p>
                    </div>

                    <div class="p-4 rounded-2xl bg-zinc-900/90 border border-zinc-800 text-center">
                        <div class="aspect-square bg-zinc-950 rounded-xl p-2 mb-3 flex items-center justify-center">
                            <img src="{{ asset('images/customizer/tshirt/black.jpg') }}" alt="T-Shirt Preview" class="w-full h-full object-contain">
                        </div>
                        <h4 class="text-xs font-bold text-white">Custom Crewneck</h4>
                        <p class="text-[10px] text-[#DFCAAB] mt-1 font-semibold">From ₹849</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================================
         13. ATELIER CRAFTSMANSHIP & COLLAR ENGINEERING
         ======================================================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">
        <div class="rounded-3xl bg-zinc-950 text-white p-8 sm:p-14 border border-zinc-800 shadow-xl">
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
                    <div class="p-5 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md text-center space-y-1">
                        <span class="text-3xl font-serif-luxury font-bold text-[#DFCAAB]">240</span>
                        <p class="text-[10px] text-zinc-400 uppercase tracking-widest">GSM Weight</p>
                    </div>
                    <div class="p-5 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md text-center space-y-1">
                        <span class="text-3xl font-serif-luxury font-bold text-[#DFCAAB]">100%</span>
                        <p class="text-[10px] text-zinc-400 uppercase tracking-widest">Mother of Pearl</p>
                    </div>
                    <div class="p-5 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md text-center space-y-1">
                        <span class="text-3xl font-serif-luxury font-bold text-[#DFCAAB]">50+</span>
                        <p class="text-[10px] text-zinc-400 uppercase tracking-widest">Wash Endurance</p>
                    </div>
                    <div class="p-5 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md text-center space-y-1">
                        <span class="text-3xl font-serif-luxury font-bold text-[#DFCAAB]">Zero</span>
                        <p class="text-[10px] text-zinc-400 uppercase tracking-widest">Shrink Rate</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================================
         14. VERIFIED REVIEWS
         ======================================================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#8C6D46] mb-1.5">Verified Testimonials</p>
            <h2 class="text-2xl sm:text-3xl font-serif-luxury font-bold text-zinc-950">What Our Clients Say</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($featuredReviews->take(3) as $review)
                <div class="p-6 sm:p-8 rounded-3xl bg-white border border-zinc-200/90 flex flex-col justify-between space-y-4 shadow-xs">
                    <div class="space-y-2.5">
                        <div class="flex items-center space-x-1 text-amber-500">
                            @for($i = 0; $i < $review->rating; $i++)
                                <svg class="w-4 h-4 fill-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <h4 class="text-sm font-bold text-zinc-950 font-serif-luxury">"{{ $review->title }}"</h4>
                        <p class="text-xs text-zinc-600 font-light leading-relaxed">{{ $review->comment }}</p>
                    </div>
                    <div class="pt-3 border-t border-zinc-100 flex items-center justify-between text-[11px]">
                        <span class="font-bold text-zinc-900">{{ $review->user ? $review->user->name : 'JACARIO Member' }}</span>
                        <span class="text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded">✓ Verified Purchase</span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- ========================================================
         15. VIP SOCIETY NEWSLETTER
         ======================================================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="rounded-3xl bg-gradient-to-br from-[#F5EFEB] via-[#EFE7DE] to-[#E8DDD1] p-8 sm:p-14 border border-[#DDCFBF] text-center space-y-4 max-w-4xl mx-auto">
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#8C6D46]">Private Archive</p>
            <h2 class="text-2xl sm:text-4xl font-serif-luxury font-bold text-zinc-950">Join the JACARIO Society</h2>
            <p class="text-xs sm:text-sm text-zinc-600 max-w-md mx-auto font-light">
                Receive private release notifications and an exclusive ₹300 welcome gift code on your inaugural order.
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
                <input type="email" x-model="email" required placeholder="Enter your email address..." class="flex-1 px-4 py-3 rounded-full bg-white border border-zinc-300 text-zinc-900 text-xs focus:outline-none focus:border-[#8C6D46] shadow-xs">
                <button type="submit" class="px-7 py-3 bg-zinc-950 hover:bg-black text-[#DFCAAB] text-xs font-bold uppercase tracking-widest rounded-full transition-all shrink-0 shadow-sm">
                    <span x-text="loading ? 'Joining...' : 'Subscribe'"></span>
                </button>
            </form>
            <div x-show="successMsg" class="text-xs font-bold text-[#8C6D46]" x-text="successMsg"></div>
        </div>
    </section>

@endsection
