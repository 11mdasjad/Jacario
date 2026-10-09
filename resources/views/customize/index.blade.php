@extends('layouts.app')

@section('title', 'Customize Polo T-Shirts & T-Shirts | JACARIO Bespoke Studio')
@section('meta_description', 'Design personalized luxury Polo T-Shirts and Crewneck T-Shirts in real-time. High-definition embroidery and printing with zero minimums and bulk discounts up to 35% off.')

@section('content')
<div class="bg-[#FAF8F5] min-h-screen">
    
    <!-- ==========================================
         1. PRINTMINE-STYLE RUNNING BANNER CAROUSEL
         ========================================== -->
    <section class="relative bg-zinc-950 text-white overflow-hidden select-none"
             x-data="{
                currentSlide: 0,
                totalSlides: 3,
                duration: 5000,
                progress: 0,
                interval: null,
                isPaused: false,

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
                }
             }"
             @mouseenter="isPaused = true"
             @mouseleave="isPaused = false">

        <!-- Slides Container -->
        <div class="relative w-full h-[480px] sm:h-[540px] lg:h-[600px] overflow-hidden">
            
            <!-- Slide 1: Customize Polo & T-Shirt Studio -->
            <div class="absolute inset-0 transition-opacity duration-700 ease-in-out flex items-center"
                 :class="currentSlide === 0 ? 'opacity-100 z-10 pointer-events-auto' : 'opacity-0 z-0 pointer-events-none'">
                
                <img src="{{ asset('images/banners/banner-customize-hero.jpg') }}" alt="Customize Apparel" class="absolute inset-0 w-full h-full object-cover object-center">
                <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/60 to-transparent"></div>

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
                    <div class="max-w-2xl space-y-4 sm:space-y-6">
                        <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-[#18181B]/90 border border-[#A4845B]/60 text-[#DFCAAB] text-[10px] sm:text-xs font-black uppercase tracking-[0.25em] shadow-lg">
                            <span class="w-2 h-2 rounded-full bg-[#A4845B] animate-ping"></span>
                            <span>LIVE 3D/HD STUDIO 3.0</span>
                        </div>

                        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-bold font-serif-luxury text-white tracking-tight leading-tight">
                            Personalize Luxury <br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#DFCAAB] via-[#F3E5D0] to-[#A4845B]">Polos & T-Shirts</span>
                        </h1>

                        <p class="text-xs sm:text-sm text-zinc-300 font-light leading-relaxed max-w-xl">
                            Upload your corporate emblem, company logo, or bespoke typography on authentic 240 GSM Supima® pique and combed jersey. Zero minimum orders with real-time visual proofing.
                        </p>

                        <div class="flex flex-wrap items-center gap-3 sm:gap-4 pt-2">
                            <a href="{{ route('customize.studio', 'polo-t-shirt') }}" class="px-7 py-3.5 bg-[#DFCAAB] hover:bg-white text-zinc-950 text-xs font-bold uppercase tracking-widest rounded-xl transition-all shadow-xl flex items-center space-x-2">
                                <span>Customize Polo T-Shirt</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                            <a href="{{ route('customize.studio', 't-shirt') }}" class="px-6 py-3.5 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all">
                                Customize T-Shirt
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2: Corporate & Bulk Ordering -->
            <div class="absolute inset-0 transition-opacity duration-700 ease-in-out flex items-center"
                 :class="currentSlide === 1 ? 'opacity-100 z-10 pointer-events-auto' : 'opacity-0 z-0 pointer-events-none'">
                
                <img src="{{ asset('images/banners/banner-customize-corporate.jpg') }}" alt="Corporate Custom Polos" class="absolute inset-0 w-full h-full object-cover object-center">
                <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/60 to-transparent"></div>

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
                    <div class="max-w-2xl space-y-4 sm:space-y-6">
                        <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-blue-950/80 border border-blue-400/50 text-blue-300 text-[10px] sm:text-xs font-black uppercase tracking-[0.25em] shadow-lg">
                            <span>TEAM & CORPORATE ATELIER</span>
                        </div>

                        <h2 class="text-3xl sm:text-5xl lg:text-6xl font-bold font-serif-luxury text-white tracking-tight leading-tight">
                            Uniforms Crafted for <br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 via-sky-100 to-indigo-300">Modern Leaders</span>
                        </h2>

                        <p class="text-xs sm:text-sm text-zinc-300 font-light leading-relaxed max-w-xl">
                            Reinforce your brand identity with precision anti-curl collar polos. High-density embroidery, tailored cuts, and 10 rich executive colorways.
                        </p>

                        <div class="flex flex-wrap items-center gap-3 sm:gap-4 pt-2">
                            <a href="{{ route('customize.studio', 'polo-t-shirt') }}" class="px-7 py-3.5 bg-white hover:bg-[#DFCAAB] text-zinc-950 text-xs font-bold uppercase tracking-widest rounded-xl transition-all shadow-xl">
                                Launch Polo Customizer
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3: Tiered Bulk Discounts -->
            <div class="absolute inset-0 transition-opacity duration-700 ease-in-out flex items-center"
                 :class="currentSlide === 2 ? 'opacity-100 z-10 pointer-events-auto' : 'opacity-0 z-0 pointer-events-none'">
                
                <img src="{{ asset('images/banners/banner-customize-bulk.jpg') }}" alt="Bulk Discounts" class="absolute inset-0 w-full h-full object-cover object-center">
                <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/60 to-transparent"></div>

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
                    <div class="max-w-2xl space-y-4 sm:space-y-6">
                        <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-emerald-950/80 border border-emerald-400/50 text-emerald-300 text-[10px] sm:text-xs font-black uppercase tracking-[0.25em] shadow-lg">
                            <span>AUTOMATIC VOLUME PRICING</span>
                        </div>

                        <h2 class="text-3xl sm:text-5xl lg:text-6xl font-bold font-serif-luxury text-white tracking-tight leading-tight">
                            Save Up to <span class="text-emerald-400">35% Off</span> <br>
                            on Bulk Custom Orders
                        </h2>

                        <p class="text-xs sm:text-sm text-zinc-300 font-light leading-relaxed max-w-xl">
                            Instant quantity-based bulk discounts applied live in studio: 5+ units (10% off), 15+ units (18% off), 30+ units (25% off), 50+ units (35% off).
                        </p>

                        <div class="flex flex-wrap items-center gap-3 sm:gap-4 pt-2">
                            <a href="#customizable-products" class="px-7 py-3.5 bg-emerald-400 hover:bg-white text-zinc-950 text-xs font-bold uppercase tracking-widest rounded-xl transition-all shadow-xl">
                                Select Garment to Begin
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Carousel Navigation Controls -->
        <button type="button" @click="prevSlide()" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-black/50 hover:bg-black/80 border border-white/20 text-white flex items-center justify-center transition-all hover:scale-105">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>

        <button type="button" @click="nextSlide()" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-black/50 hover:bg-black/80 border border-white/20 text-white flex items-center justify-center transition-all hover:scale-105">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>

        <!-- Carousel Indicators with Live Progress Bar -->
        <div class="absolute bottom-4 inset-x-0 z-20 flex items-center justify-center space-x-2">
            <template x-for="(idx, i) in 3" :key="i">
                <button type="button" @click="goToSlide(i)" class="h-1.5 rounded-full transition-all duration-300 overflow-hidden bg-white/30" :class="currentSlide === i ? 'w-10' : 'w-3'">
                    <div x-show="currentSlide === i" class="h-full bg-[#DFCAAB] transition-all" :style="`width: ${progress}%`"></div>
                </button>
            </template>
        </div>

    </section>

    <!-- ==========================================
         2. TRUST PILLARS STRIP
         ========================================== -->
    <section class="bg-white border-b border-zinc-200/80 py-4 sm:py-5 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center divide-x-0 md:divide-x divide-zinc-200">
                <div class="flex items-center justify-center space-x-2.5 py-1">
                    <svg class="w-5 h-5 text-[#8C6D46] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-xs font-bold text-zinc-900">Zero Minimum Orders</span>
                </div>
                <div class="flex items-center justify-center space-x-2.5 py-1">
                    <svg class="w-5 h-5 text-[#8C6D46] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span class="text-xs font-bold text-zinc-900">Real Photographic Mockups</span>
                </div>
                <div class="flex items-center justify-center space-x-2.5 py-1">
                    <svg class="w-5 h-5 text-[#8C6D46] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-xs font-bold text-zinc-900">Bulk Discounts up to 35%</span>
                </div>
                <div class="flex items-center justify-center space-x-2.5 py-1">
                    <svg class="w-5 h-5 text-[#8C6D46] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span class="text-xs font-bold text-zinc-900">Pan-India Express Dispatch</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ==========================================
         3. PRODUCT SELECTION SECTION (POLO & T-SHIRT)
         ========================================== -->
    <section id="customizable-products" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        
        <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-12">
            <span class="text-[10px] sm:text-xs font-extrabold uppercase tracking-[0.25em] text-[#8C6D46] block mb-1">Select Garment to Personalize</span>
            <h2 class="text-2xl sm:text-4xl font-serif-luxury font-bold text-zinc-950">Choose Your Master Garment</h2>
            <p class="text-xs sm:text-sm text-zinc-600 mt-2">Currently available for our signature Polo T-Shirt and Crewneck T-Shirt.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
            
            @php
                $colorSlugMap = [
                    '#18181B' => 'black',
                    '#F8FAFC' => 'white',
                    '#1E293B' => 'navy',
                    '#334155' => 'charcoal',
                    '#5C1D24' => 'burgundy',
                    '#1B4332' => 'forest',
                    '#D6C7B2' => 'sand',
                    '#1D4ED8' => 'royal-blue',
                    '#64748B' => 'grey',
                    '#3F4E3F' => 'olive'
                ];
            @endphp

            @foreach($products as $product)
                @php
                    $folder = $product->type === 'polo_tshirt' ? 'polo' : 'tshirt';
                @endphp
                <div class="group bg-white rounded-3xl p-6 sm:p-8 border border-zinc-200 shadow-md hover:shadow-xl transition-all duration-300 flex flex-col justify-between"
                     x-data="{
                        selectedColorSlug: 'black',
                        getImageUrl() {
                            return '/images/customizer/{{ $folder }}/' + this.selectedColorSlug + '.jpg';
                        }
                     }">
                    
                    <div>
                        <!-- Top Tag -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-amber-50 border border-amber-200/80 text-[#8C6D46] text-[11px] font-extrabold uppercase tracking-wider">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#A4845B]"></span>
                                <span>{{ $product->type === 'polo_tshirt' ? 'Signature 240 GSM Pique' : '100% Combed Supima® Jersey' }}</span>
                            </span>

                            <span class="text-xs font-bold text-zinc-400 font-mono">
                                {{ $product->sku }}
                            </span>
                        </div>

                        <!-- Real Photorealistic Garment Image Display with Dynamic Color Swapping -->
                        <div class="relative w-full aspect-[4/3] bg-zinc-50 rounded-2xl p-4 flex items-center justify-center overflow-hidden mb-6 border border-zinc-100">
                            <img :src="getImageUrl()" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-contain filter drop-shadow-md transition-all duration-300 group-hover:scale-105">
                            
                            <div class="absolute bottom-3 right-3 bg-white/95 backdrop-blur-xs px-2.5 py-1 rounded-lg text-[10px] font-bold text-zinc-800 border border-zinc-200 shadow-xs flex items-center space-x-1">
                                <svg class="w-3.5 h-3.5 text-[#A4845B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                <span>Live Studio Mockup</span>
                            </div>
                        </div>

                        <!-- Interactive Real-Time Color Swatches -->
                        <div class="mb-5">
                            <div class="flex items-center justify-between text-xs mb-2">
                                <span class="text-[11px] font-bold text-zinc-500 uppercase tracking-wider">Preview Colorways:</span>
                                <span class="font-bold text-zinc-900 capitalize" x-text="selectedColorSlug.replace('-', ' ')"></span>
                            </div>
                            
                            <div class="flex flex-wrap items-center gap-1.5">
                                @foreach($colors as $color)
                                    @php
                                        $slug = $colorSlugMap[$color->hex_code] ?? 'black';
                                    @endphp
                                    <button type="button" 
                                            @click="selectedColorSlug = '{{ $slug }}'"
                                            class="w-6 h-6 rounded-full border transition-all duration-150 relative cursor-pointer"
                                            :class="selectedColorSlug === '{{ $slug }}' ? 'ring-2 ring-black ring-offset-2 scale-110 border-white' : 'border-zinc-300 hover:scale-105'"
                                            style="background-color: {{ $color->hex_code }};"
                                            title="{{ $color->name }}">
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Product Title & Description -->
                        <h3 class="text-xl sm:text-2xl font-bold font-serif-luxury text-zinc-950 group-hover:text-[#A4845B] transition-colors">
                            {{ $product->name }}
                        </h3>

                        <p class="mt-2 text-xs sm:text-sm text-zinc-600 leading-relaxed min-h-[3rem]">
                            {{ $product->short_description }}
                        </p>

                        <!-- Highlights Specs -->
                        <div class="mt-4 pt-4 border-t border-zinc-100 grid grid-cols-2 gap-2 text-xs text-zinc-700">
                            <div class="flex items-center space-x-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>10+ Luxury Colors</span>
                            </div>
                            <div class="flex items-center space-x-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Sizes S to 3XL</span>
                            </div>
                            <div class="flex items-center space-x-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>HD Digital Print & Embroidery</span>
                            </div>
                            <div class="flex items-center space-x-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Save 35% on Bulk</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Action & Pricing -->
                    <div class="mt-6 pt-6 border-t border-zinc-100 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] text-zinc-500 block uppercase tracking-wider font-semibold">Starting From</span>
                            <div class="flex items-baseline space-x-2">
                                <span class="text-xl sm:text-2xl font-bold font-serif-luxury text-zinc-950">
                                    {{ $product->formatted_price }}
                                </span>
                                @if($product->discount_price)
                                    <span class="text-xs text-zinc-400 line-through">
                                        ₹{{ number_format($product->base_price, 2) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <a href="{{ route('customize.studio', $product->slug) }}" 
                           class="px-6 py-3.5 bg-zinc-950 hover:bg-black text-[#DFCAAB] text-xs font-bold uppercase tracking-widest rounded-xl transition-all shadow-md flex items-center space-x-2 hover:scale-[1.02]">
                            <span>Launch Studio</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>

                </div>
            @endforeach

        </div>
    </section>

    <!-- ==========================================
         4. PRINTMINE-STYLE 3-STEP CUSTOMIZATION FLOW
         ========================================== -->
    <section class="bg-white py-14 sm:py-20 border-t border-zinc-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="text-[10px] sm:text-xs font-extrabold uppercase tracking-[0.25em] text-[#8C6D46] block mb-1">Simple & Seamless Experience</span>
                <h2 class="text-2xl sm:text-4xl font-serif-luxury font-bold text-zinc-950">How Customization Works</h2>
                <p class="text-xs sm:text-sm text-zinc-600 mt-2">Design, preview, and place your order in less than 2 minutes.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="p-8 rounded-3xl bg-zinc-50 border border-zinc-200 text-center relative group hover:bg-white hover:border-[#A4845B]/60 transition-all shadow-xs">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-zinc-950 text-[#DFCAAB] flex items-center justify-center text-xl font-bold font-serif-luxury mb-5 shadow-md">
                        1
                    </div>
                    <h3 class="text-base font-bold text-zinc-950 font-serif-luxury mb-2">Pick Your Garment & Color</h3>
                    <p class="text-xs text-zinc-600 leading-relaxed">
                        Choose between our signature Polo T-Shirt or Crewneck T-Shirt across 10 luxury shades and select your size.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="p-8 rounded-3xl bg-zinc-50 border border-zinc-200 text-center relative group hover:bg-white hover:border-[#A4845B]/60 transition-all shadow-xs">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-zinc-950 text-[#DFCAAB] flex items-center justify-center text-xl font-bold font-serif-luxury mb-5 shadow-md">
                        2
                    </div>
                    <h3 class="text-base font-bold text-zinc-950 font-serif-luxury mb-2">Upload Logo & Personalize</h3>
                    <p class="text-xs text-zinc-600 leading-relaxed">
                        Drag and drop your company logo or add custom text with Google typography. Scale, rotate, and position with live real-time preview.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="p-8 rounded-3xl bg-zinc-50 border border-zinc-200 text-center relative group hover:bg-white hover:border-[#A4845B]/60 transition-all shadow-xs">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-zinc-950 text-[#DFCAAB] flex items-center justify-center text-xl font-bold font-serif-luxury mb-5 shadow-md">
                        3
                    </div>
                    <h3 class="text-base font-bold text-zinc-950 font-serif-luxury mb-2">HD Print & Fast Delivery</h3>
                    <p class="text-xs text-zinc-600 leading-relaxed">
                        We inspect your artwork, execute high-precision digital printing or embroidery, and deliver directly to your doorstep.
                    </p>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
