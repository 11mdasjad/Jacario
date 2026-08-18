@extends('layouts.app')

@section('title', 'JACARIO — The Polo, Perfected. | Luxury Polo & Round Neck T-Shirts')

@section('content')

    <!-- Myntra-Style Mobile Story / Category Quick Pills (Mobile Only) -->
    <section class="lg:hidden bg-white border-b border-zinc-200/80 py-3.5 px-3 overflow-x-auto no-scrollbar">
        <div class="flex items-center space-x-4 min-w-max px-1">
            
            <!-- 1. All Polos -->
            <a href="{{ route('shop.index', ['category' => 'mens-polo-t-shirts']) }}" class="flex flex-col items-center space-y-1.5 group">
                <div class="w-14 h-14 rounded-full p-0.5 bg-gradient-to-tr from-[#A4845B] via-[#DFCAAB] to-amber-200 shadow-xs">
                    <div class="w-full h-full rounded-full bg-zinc-950 flex items-center justify-center text-white overflow-hidden p-1">
                        <img src="{{ asset('images/polos/black-polo.svg') }}" alt="Polo" class="w-full h-full object-contain filter invert group-hover:scale-110 transition-transform">
                    </div>
                </div>
                <span class="text-[11px] font-bold text-zinc-800 tracking-tight">Polo Shirts</span>
            </a>

            <!-- 2. Round Neck -->
            <a href="{{ route('shop.index', ['category' => 'round-neck-t-shirts']) }}" class="flex flex-col items-center space-y-1.5 group">
                <div class="w-14 h-14 rounded-full p-0.5 bg-zinc-200 hover:bg-zinc-900 transition-colors shadow-xs">
                    <div class="w-full h-full rounded-full bg-zinc-900 flex items-center justify-center text-white overflow-hidden p-1">
                        <img src="{{ asset('images/polos/white-polo.svg') }}" alt="Round Neck" class="w-full h-full object-contain filter group-hover:scale-110 transition-transform">
                    </div>
                </div>
                <span class="text-[11px] font-bold text-zinc-800 tracking-tight">Round Neck</span>
            </a>

            <!-- 3. New Arrivals -->
            <a href="{{ route('shop.index', ['category' => 'new-arrival-t-shirts']) }}" class="flex flex-col items-center space-y-1.5 group">
                <div class="w-14 h-14 rounded-full p-0.5 bg-gradient-to-tr from-emerald-500 to-teal-300 shadow-xs">
                    <div class="w-full h-full rounded-full bg-emerald-950 flex items-center justify-center text-emerald-200 overflow-hidden font-black text-xs">
                        NEW
                    </div>
                </div>
                <span class="text-[11px] font-bold text-zinc-800 tracking-tight">New Drops</span>
            </a>

            <!-- 4. Best Sellers -->
            <a href="{{ route('shop.index', ['collection' => 'bestsellers']) }}" class="flex flex-col items-center space-y-1.5 group">
                <div class="w-14 h-14 rounded-full p-0.5 bg-gradient-to-tr from-amber-500 to-rose-400 shadow-xs">
                    <div class="w-full h-full rounded-full bg-amber-950 flex items-center justify-center text-amber-200 overflow-hidden font-black text-xs">
                        TOP
                    </div>
                </div>
                <span class="text-[11px] font-bold text-zinc-800 tracking-tight">Best Sellers</span>
            </a>

            <!-- 5. Supima Cotton -->
            <a href="{{ route('shop.index', ['category' => 'supima-luxury-polo']) }}" class="flex flex-col items-center space-y-1.5 group">
                <div class="w-14 h-14 rounded-full p-0.5 bg-zinc-200 shadow-xs">
                    <div class="w-full h-full rounded-full bg-[#FAF8F5] flex items-center justify-center text-[#8C6D46] overflow-hidden font-black text-[10px] uppercase">
                        Supima®
                    </div>
                </div>
                <span class="text-[11px] font-bold text-zinc-800 tracking-tight">Supima®</span>
            </a>

            <!-- 6. Mulberry Silk -->
            <a href="{{ route('shop.index', ['category' => 'silk-cotton-blend-polo']) }}" class="flex flex-col items-center space-y-1.5 group">
                <div class="w-14 h-14 rounded-full p-0.5 bg-zinc-200 shadow-xs">
                    <div class="w-full h-full rounded-full bg-[#FAF8F5] flex items-center justify-center text-zinc-900 overflow-hidden font-black text-[10px] uppercase">
                        Silk
                    </div>
                </div>
                <span class="text-[11px] font-bold text-zinc-800 tracking-tight">Silk Blend</span>
            </a>

            <!-- 7. Track Order -->
            <a href="{{ route('orders.track') }}" class="flex flex-col items-center space-y-1.5 group">
                <div class="w-14 h-14 rounded-full p-0.5 bg-zinc-200 shadow-xs">
                    <div class="w-full h-full rounded-full bg-zinc-100 flex items-center justify-center text-zinc-700 overflow-hidden">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                </div>
                <span class="text-[11px] font-bold text-zinc-800 tracking-tight">Track</span>
            </a>

        </div>
    </section>

    <!-- Luminous Editorial Hero Section -->
    <section class="relative bg-gradient-to-b from-[#F7F5F0] via-[#FAF8F5] to-white text-zinc-900 overflow-hidden border-b border-zinc-200/80">
        <!-- Ambient subtle background glow -->
        <div class="absolute inset-0 opacity-40 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-[#E8DEC8] via-transparent to-transparent pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-24">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                
                <!-- Left Editorial Headline -->
                <div class="lg:col-span-7 space-y-6 lg:space-y-8 z-10">
                    <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-white border border-zinc-200/90 shadow-xs text-[#8C6D46] text-[11px] sm:text-xs font-bold uppercase tracking-widest">
                        <span class="w-2 h-2 rounded-full bg-[#A4845B]"></span>
                        <span>The Modern Haute Collection</span>
                    </div>

                    <h1 class="text-3xl sm:text-6xl lg:text-7xl font-serif-luxury font-bold tracking-tight text-zinc-950 leading-[1.1]">
                        The Polo, <br>
                        <span class="gold-gradient-text">Perfected.</span>
                    </h1>

                    <p class="text-sm sm:text-lg text-zinc-600 max-w-xl font-light leading-relaxed">
                        We obsess over a single garment. Meticulously knitted from double-twisted American Supima® cotton, Mulberry silk blends, and tailored collars that never curl. Made for every move.
                    </p>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2.5 sm:space-y-0 sm:space-x-4 pt-1">
                        <a href="{{ route('shop.index', ['category' => 'mens-polo-t-shirts']) }}" class="px-6 sm:px-8 py-3.5 sm:py-4 bg-zinc-950 hover:bg-black text-white text-xs font-bold uppercase tracking-[0.2em] rounded-xl text-center transition-all duration-300 shadow-md hover:shadow-xl hover:scale-[1.02]">
                            Shop Polo T-Shirts
                        </a>
                        <a href="#styles" class="px-6 sm:px-8 py-3.5 sm:py-4 border border-zinc-300 hover:border-zinc-950 text-zinc-900 bg-white/80 hover:bg-white text-xs font-bold uppercase tracking-[0.2em] rounded-xl text-center transition-all shadow-xs">
                            Explore Collections
                        </a>
                    </div>

                    <!-- Value Highlights -->
                    <div class="grid grid-cols-3 gap-3 sm:gap-6 pt-6 sm:pt-8 border-t border-zinc-200">
                        <div>
                            <p class="text-xl sm:text-2xl font-serif-luxury font-bold text-zinc-950">100%</p>
                            <p class="text-[10px] sm:text-[11px] text-zinc-500 uppercase tracking-wider mt-0.5 font-medium">Supima® & Silk</p>
                        </div>
                        <div>
                            <p class="text-xl sm:text-2xl font-serif-luxury font-bold text-zinc-950">240 GSM</p>
                            <p class="text-[10px] sm:text-[11px] text-zinc-500 uppercase tracking-wider mt-0.5 font-medium">Piqué Weight</p>
                        </div>
                        <div>
                            <p class="text-xl sm:text-2xl font-serif-luxury font-bold text-zinc-950">Zero</p>
                            <p class="text-[10px] sm:text-[11px] text-zinc-500 uppercase tracking-wider mt-0.5 font-medium">Collar Roll</p>
                        </div>
                    </div>
                </div>

                <!-- Right Hero Visual Showcase -->
                <div class="lg:col-span-5 relative flex justify-center">
                    <div class="relative w-full max-w-md aspect-[4/5] rounded-3xl bg-white border border-zinc-200 flex items-center justify-center shadow-2xl overflow-hidden group">
                        
                        <!-- Decorative Seal Badge -->
                        <div class="absolute top-4 right-4 bg-white/95 backdrop-blur-md text-[#8C6D46] border border-zinc-200 rounded-full w-12 h-12 sm:w-14 sm:h-14 flex flex-col items-center justify-center text-center z-10 shadow-md">
                            <span class="text-[8px] sm:text-[9px] font-bold tracking-widest uppercase">EST.</span>
                            <span class="text-[9px] sm:text-[10px] font-bold text-zinc-900">2026</span>
                        </div>

                        <!-- Real Photography Master Artwork -->
                        <img src="https://images.unsplash.com/photo-1586363104862-3a5e2ab60d99?w=900&auto=format&fit=crop&q=80" 
                             alt="JACARIO Signature Obsidian Polo" 
                             class="w-full h-full object-cover filter transition-transform duration-700 transform group-hover:scale-105">

                        <div class="absolute bottom-3 left-3 right-3 sm:bottom-4 sm:left-4 sm:right-4 bg-white/95 backdrop-blur-md rounded-2xl p-3 sm:p-4 border border-zinc-200/90 flex items-center justify-between z-10 shadow-lg">
                            <div>
                                <p class="text-xs font-bold text-zinc-950">Signature Obsidian Supima®</p>
                                <p class="text-[10px] sm:text-[11px] text-[#8C6D46] font-medium">Mother-of-Pearl Shell Buttons</p>
                            </div>
                            <span class="text-xs font-bold text-zinc-950 bg-zinc-100 px-2.5 py-1 rounded-lg border border-zinc-200">₹1,999</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Curated Categories Section -->
    <section id="styles" class="py-12 sm:py-20 bg-white border-b border-zinc-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-2xl mx-auto mb-8 sm:mb-14">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#8C6D46] mb-1.5 sm:mb-2">Curated Silhouettes</p>
                <h2 class="text-2xl sm:text-4xl font-serif-luxury font-bold text-zinc-950 tracking-tight">
                    Signature Collections
                </h2>
                <p class="text-xs sm:text-sm text-zinc-500 mt-2 sm:mt-3 font-light">
                    Every knit is purpose-developed for distinct moments — from crisp morning boardrooms to breezy coastal weekends.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                @foreach($categories as $category)
                    <a href="{{ route('shop.index', ['category' => $category->slug]) }}" class="group relative rounded-3xl bg-zinc-900 overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 flex flex-col justify-end aspect-[4/5] border border-zinc-200">
                        <img src="{{ $category->image ?: 'https://images.unsplash.com/photo-1586363104862-3a5e2ab60d99?w=800&auto=format&fit=crop&q=80' }}" 
                             alt="{{ $category->name }}" 
                             class="absolute inset-0 w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700 opacity-85 group-hover:opacity-95">
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/35 to-transparent"></div>

                        <div class="relative p-6 sm:p-8 z-10 space-y-1.5 sm:space-y-2">
                            <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-[0.25em] text-[#DFCAAB]">Explore Collection</span>
                            <h3 class="text-xl sm:text-2xl font-serif-luxury font-bold text-white group-hover:text-[#DFCAAB] transition-colors">{{ $category->name }}</h3>
                            <p class="text-xs text-zinc-300 line-clamp-2 font-light hidden sm:block">{{ $category->description }}</p>
                            <span class="inline-flex items-center space-x-2 text-xs font-bold uppercase tracking-widest text-white pt-1 sm:pt-2 group-hover:text-[#DFCAAB] transition-colors">
                                <span>Shop Now</span>
                                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>

        </div>
    </section>

    <!-- Best Sellers Section (Myntra-Style 2-Col Mobile Grid) -->
    <section class="py-12 sm:py-20 bg-[#FAF8F5]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex items-center justify-between mb-6 sm:mb-12">
                <div>
                    <p class="text-[10px] sm:text-xs font-bold uppercase tracking-[0.25em] text-[#8C6D46] mb-1">Customer Favorites</p>
                    <h2 class="text-xl sm:text-3xl font-serif-luxury font-bold text-zinc-950 tracking-tight">Best Sellers</h2>
                </div>
                <a href="{{ route('shop.index', ['collection' => 'bestsellers']) }}" class="text-xs font-bold uppercase tracking-wider text-zinc-900 hover:text-[#8C6D46] transition-colors flex items-center space-x-1">
                    <span>View All</span>
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-6">
                @foreach($bestsellers->take(4) as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

        </div>
    </section>

    <!-- Why JACARIO Value Pillars (Luminous Luxury Cards) -->
    <section class="py-16 sm:py-24 bg-white border-y border-zinc-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-16">
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-[0.25em] text-[#8C6D46] mb-1.5 sm:mb-2">Uncompromising Standard</p>
                <h2 class="text-2xl sm:text-4xl font-serif-luxury font-bold text-zinc-950 tracking-tight">
                    Why JACARIO
                </h2>
                <p class="text-xs sm:text-sm text-zinc-500 mt-2 sm:mt-3 font-light">
                    We refused to make twenty mediocre clothing categories. We chose to master the single most versatile garment in menswear.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                
                <!-- Pillar 1 -->
                <div class="p-6 sm:p-8 rounded-3xl bg-[#FAF8F5] border border-zinc-200/80 space-y-3 sm:space-y-4 hover:shadow-lg transition-all">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-white border border-zinc-200 flex items-center justify-center text-[#8C6D46] shadow-xs">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-serif-luxury font-bold text-zinc-950">Rare Supima® & Silk</h3>
                    <p class="text-xs text-zinc-600 leading-relaxed font-light">
                        We source extra-long staple Supima® fibers and grade-6A Mulberry silk for exceptional softness, anti-pilling longevity, and natural drape.
                    </p>
                </div>

                <!-- Pillar 2 -->
                <div class="p-6 sm:p-8 rounded-3xl bg-[#FAF8F5] border border-zinc-200/80 space-y-3 sm:space-y-4 hover:shadow-lg transition-all">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-white border border-zinc-200 flex items-center justify-center text-[#8C6D46] shadow-xs">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-serif-luxury font-bold text-zinc-950">Anti-Curl Collar Engineering</h3>
                    <p class="text-xs text-zinc-600 leading-relaxed font-light">
                        Internal micro-fused interlinings ensure JACARIO collars stay crisp and structured without curling or drooping, even after 50+ wash cycles.
                    </p>
                </div>

                <!-- Pillar 3 -->
                <div class="p-6 sm:p-8 rounded-3xl bg-[#FAF8F5] border border-zinc-200/80 space-y-3 sm:space-y-4 hover:shadow-lg transition-all">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-white border border-zinc-200 flex items-center justify-center text-[#8C6D46] shadow-xs">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879a3 3 0 01-4.242 0 3 3 0 010-4.242L10.758 4.879a3 3 0 014.242 0 3 3 0 010 4.242z"/></svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-serif-luxury font-bold text-zinc-950">Mother-of-Pearl Hardware</h3>
                    <p class="text-xs text-zinc-600 leading-relaxed font-light">
                        Cross-stitched genuine Australian mother-of-pearl buttons with subtle iridescent shimmer that will never crack or chip.
                    </p>
                </div>

                <!-- Pillar 4 -->
                <div class="p-6 sm:p-8 rounded-3xl bg-[#FAF8F5] border border-zinc-200/80 space-y-3 sm:space-y-4 hover:shadow-lg transition-all">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-white border border-zinc-200 flex items-center justify-center text-[#8C6D46] shadow-xs">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/></svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-serif-luxury font-bold text-zinc-950">Pre-Shrunk Precision Fit</h3>
                    <p class="text-xs text-zinc-600 leading-relaxed font-light">
                        Every bolt of fabric undergoes thermal stabilization so the size you try on out of the box remains the exact size you wear two years later.
                    </p>
                </div>

                <!-- Pillar 5 -->
                <div class="p-6 sm:p-8 rounded-3xl bg-[#FAF8F5] border border-zinc-200/80 space-y-3 sm:space-y-4 hover:shadow-lg transition-all">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-white border border-zinc-200 flex items-center justify-center text-[#8C6D46] shadow-xs">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-serif-luxury font-bold text-zinc-950">15-Day Doorstep Exchanges</h3>
                    <p class="text-xs text-zinc-600 leading-relaxed font-light">
                        Complimentary size swaps and returns picked up directly from your doorstep with zero friction.
                    </p>
                </div>

                <!-- Pillar 6 -->
                <div class="p-6 sm:p-8 rounded-3xl bg-[#FAF8F5] border border-zinc-200/80 space-y-3 sm:space-y-4 hover:shadow-lg transition-all">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-white border border-zinc-200 flex items-center justify-center text-[#8C6D46] shadow-xs">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-serif-luxury font-bold text-zinc-950">Encrypted & Secure Payments</h3>
                    <p class="text-xs text-zinc-600 leading-relaxed font-light">
                        Industry-grade 256-bit SSL encryption with Razorpay supporting UPI, Cards, Net Banking, and Cash on Delivery.
                    </p>
                </div>

            </div>

        </div>
    </section>

    <!-- New Arrivals Section (Myntra-Style 2-Col Mobile Grid) -->
    <section class="py-12 sm:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex items-center justify-between mb-6 sm:mb-12">
                <div>
                    <p class="text-[10px] sm:text-xs font-bold uppercase tracking-[0.25em] text-[#8C6D46] mb-1">Latest Atelier Releases</p>
                    <h2 class="text-xl sm:text-3xl font-serif-luxury font-bold text-zinc-950 tracking-tight">New Arrivals</h2>
                </div>
                <a href="{{ route('shop.index', ['collection' => 'new-arrivals']) }}" class="text-xs font-bold uppercase tracking-wider text-zinc-900 hover:text-[#8C6D46] transition-colors flex items-center space-x-1">
                    <span>Explore New</span>
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-6">
                @foreach($newArrivals->take(4) as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

        </div>
    </section>

    <!-- Brand Philosophy & Atelier Story -->
    <section class="py-24 bg-[#F7F4EE] border-y border-[#E8E1D5]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <div class="lg:col-span-6 space-y-6">
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#8C6D46]">The JACARIO Philosophy</p>
                    
                    <h2 class="text-3xl sm:text-4xl font-serif-luxury font-bold text-zinc-950 tracking-tight leading-tight">
                        Obsessed with <br>
                        One Single Silhouette.
                    </h2>

                    <p class="text-sm sm:text-base text-zinc-700 leading-relaxed font-light">
                        Most modern apparel companies build sprawling catalogs of jackets, trousers, hoodies, and sneakers. In doing so, craftsmanship gets diluted into mass production.
                    </p>

                    <p class="text-sm sm:text-base text-zinc-700 leading-relaxed font-light">
                        At JACARIO, we chose a radically different path. We decided to do only one thing — and do it with uncompromising perfection: <strong class="text-zinc-950 font-semibold">The Polo T-Shirt</strong>.
                    </p>

                    <div class="pt-2">
                        <a href="{{ route('about') }}" class="inline-flex items-center space-x-2 text-xs font-bold uppercase tracking-widest text-zinc-950 hover:text-[#8C6D46] transition-colors border-b-2 border-zinc-950 pb-1">
                            <span>Read Our Full Story</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-6">
                    <div class="rounded-3xl bg-white p-8 sm:p-12 shadow-xl border border-zinc-200/80 space-y-6">
                        <div class="flex items-center space-x-4 pb-6 border-b border-zinc-100">
                            <span class="text-4xl font-serif-luxury font-bold text-zinc-950">42</span>
                            <p class="text-xs text-zinc-600 uppercase tracking-wider">Individual hand measurements taken across every single production run</p>
                        </div>

                        <div class="flex items-center space-x-4 pb-6 border-b border-zinc-100">
                            <span class="text-4xl font-serif-luxury font-bold text-[#8C6D46]">100%</span>
                            <p class="text-xs text-zinc-600 uppercase tracking-wider">Natural mother-of-pearl buttons ethically sourced from certified pearl fisheries</p>
                        </div>

                        <div class="flex items-center space-x-4">
                            <span class="text-4xl font-serif-luxury font-bold text-zinc-950">240</span>
                            <p class="text-xs text-zinc-600 uppercase tracking-wider">Grams per square meter — the definitive sweet spot for drape and breathability</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Customer Reviews Showcase -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-2xl mx-auto mb-14">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#8C6D46] mb-2">Verified Client Reviews</p>
                <h2 class="text-3xl font-serif-luxury font-bold text-zinc-950 tracking-tight">
                    What Our Gentlemen Say
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($featuredReviews->take(3) as $review)
                    <div class="p-8 rounded-3xl bg-[#FAF8F5] border border-zinc-200/80 flex flex-col justify-between space-y-6">
                        <div class="space-y-3">
                            <div class="flex items-center space-x-1 text-amber-500">
                                @for($i = 0; $i < $review->rating; $i++)
                                    <svg class="w-4 h-4 fill-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                            <h4 class="text-sm font-bold text-zinc-950 font-serif-luxury">"{{ $review->title }}"</h4>
                            <p class="text-xs text-zinc-600 leading-relaxed font-light">
                                {{ $review->comment }}
                            </p>
                        </div>

                        <div class="pt-4 border-t border-zinc-200 flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-zinc-950">{{ $review->user->name }}</p>
                                <p class="text-[10px] text-zinc-500">{{ $review->product ? $review->product->name : 'JACARIO Client' }}</p>
                            </div>
                            @if($review->is_verified_purchase)
                                <span class="inline-flex items-center space-x-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Verified</span>
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- Warm Luxury Newsletter Section -->
    <section class="py-20 bg-[#FAF8F5]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6 bg-gradient-to-br from-[#F5EFEB] via-[#EFE7DE] to-[#E8DDD1] p-10 sm:p-16 rounded-3xl border border-[#DDCFBF] shadow-sm">
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#8C6D46]">The JACARIO Society</p>
            <h2 class="text-3xl sm:text-4xl font-serif-luxury font-bold text-zinc-950">
                Join the Private Archive
            </h2>
            <p class="text-xs sm:text-sm text-zinc-600 max-w-lg mx-auto font-light leading-relaxed">
                Receive private release previews, rare yarn drops, and an exclusive ₹300 welcome gift code on your inaugural order.
            </p>

            <form action="{{ route('newsletter.subscribe') }}" method="POST" class="max-w-md mx-auto flex flex-col sm:flex-row gap-3 pt-2"
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
                <input type="email" 
                       x-model="email" 
                       required 
                       placeholder="Enter your email address..." 
                       class="flex-1 px-4 py-3.5 rounded-xl bg-white border border-zinc-300 text-zinc-900 text-xs placeholder-zinc-400 focus:outline-none focus:border-[#8C6D46] shadow-xs">
                <button type="submit" 
                        class="px-6 py-3.5 bg-zinc-950 hover:bg-black text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-colors shrink-0 flex items-center justify-center space-x-1 shadow-xs"
                        :disabled="loading">
                    <span x-text="loading ? 'Joining...' : 'Subscribe'"></span>
                </button>
            </form>

            <div x-show="successMsg" x-transition class="text-xs font-bold text-[#8C6D46]" x-text="successMsg"></div>
        </div>
    </section>

    <!-- Instagram Lookbook Section -->
    <section class="py-16 bg-white border-t border-zinc-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#8C6D46]">@JACARIO.OFFICIAL</p>
                    <h3 class="text-xl font-serif-luxury font-bold text-zinc-950 mt-1">Styled by Gentlemen Worldwide</h3>
                </div>
                <a href="https://instagram.com" target="_blank" rel="noopener" class="text-xs font-bold uppercase tracking-widest text-zinc-900 hover:text-[#8C6D46] flex items-center space-x-1">
                    <span>Follow Instagram</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @php
                    $instaImages = [
                        'https://images.unsplash.com/photo-1586363104862-3a5e2ab60d99?w=600&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1581655353564-df123a1eb820?w=600&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=600&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=600&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=600&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=600&auto=format&fit=crop&q=80',
                    ];
                @endphp
                @foreach($instaImages as $photo)
                    <div class="aspect-square bg-zinc-100 rounded-2xl overflow-hidden border border-zinc-200 relative group shadow-xs">
                        <img src="{{ $photo }}" alt="JACARIO Lookbook" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <span class="text-xs font-bold uppercase tracking-widest text-white">#JACARIO</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection
