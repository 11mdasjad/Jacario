@extends('layouts.app')

@section('title', 'The Atelier Story | JACARIO Haute Apparel')
@section('meta_description', 'Learn about JACARIO\'s unwavering dedication to crafting the world\'s finest Polo T-Shirts. Discover our materials, craftsmanship, and single-silhouette philosophy.')

@section('content')

    <!-- Hero Header -->
    <section class="relative bg-gradient-to-b from-[#F7F4EE] via-[#FAF8F5] to-white text-zinc-900 py-24 border-b border-zinc-200/80">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <span class="text-xs font-bold uppercase tracking-[0.25em] text-[#8C6D46]">The JACARIO Atelier</span>
            <h1 class="text-4xl sm:text-6xl font-serif-luxury font-bold text-zinc-950 tracking-tight leading-tight">
                One Silhouette. <br>
                <span class="gold-gradient-text">Zero Compromises.</span>
            </h1>
            <p class="text-base sm:text-lg text-zinc-600 font-light max-w-2xl mx-auto leading-relaxed">
                We believe that true mastery comes from singular focus. We don't make jeans, suits, or sneakers. We make only the quintessential luxury Polo & Round Neck T-Shirt.
            </p>
        </div>
    </section>

    <!-- Narrative Section -->
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
            
            <div class="space-y-6">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#A4845B]">Origin Story</p>
                <h2 class="text-2xl sm:text-3xl font-serif-luxury font-bold text-zinc-900 leading-snug">
                    Why the World Needed a Better Polo
                </h2>
                <p class="text-sm sm:text-base text-zinc-600 font-light leading-relaxed">
                    For decades, menswear was forced to choose between two disappointing extremes: floppy casual polo shirts that curled at the collar after two washes, or stiff, unbreathable synthetic shirts that trapped heat.
                </p>
                <p class="text-sm sm:text-base text-zinc-600 font-light leading-relaxed">
                    In 2026, JACARIO was founded with a single mission: engineer the definitive Polo T-Shirt from the yarn up. We spent two years perfecting the yarn twist, the collar interlining density, and the placement of our three-button placket to create a silhouette that stands crisp under a tailored blazer yet breathes effortlessly on a Mediterranean terrace.
                </p>
            </div>

            <!-- Material Pillars -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 py-8 border-y border-zinc-200">
                <div class="space-y-3">
                    <span class="text-3xl font-serif-luxury font-bold text-zinc-900">01</span>
                    <h3 class="text-base font-bold text-zinc-900">Rare Supima® Cotton</h3>
                    <p class="text-xs text-zinc-500 leading-relaxed font-light">
                        American-grown extra-long staple fibers that produce 45% stronger yarns with natural, luminous color depth.
                    </p>
                </div>

                <div class="space-y-3">
                    <span class="text-3xl font-serif-luxury font-bold text-[#A4845B]">02</span>
                    <h3 class="text-base font-bold text-zinc-900">Grade-6A Mulberry Silk</h3>
                    <p class="text-xs text-zinc-500 leading-relaxed font-light">
                        Blended with Egyptian Mako cotton for a fluid, featherweight drape that feels cool against the skin even in extreme humidity.
                    </p>
                </div>

                <div class="space-y-3">
                    <span class="text-3xl font-serif-luxury font-bold text-zinc-900">03</span>
                    <h3 class="text-base font-bold text-zinc-900">Mother-of-Pearl Finish</h3>
                    <p class="text-xs text-zinc-500 leading-relaxed font-light">
                        Each button is carved from genuine Australian oyster shell and cross-anchored with heat-sealed thread.
                    </p>
                </div>
            </div>

            <div class="text-center space-y-6 pt-4">
                <h3 class="text-2xl font-serif-luxury font-bold text-zinc-900">Experience the Perfection</h3>
                <p class="text-xs sm:text-sm text-zinc-500 max-w-md mx-auto">
                    Try any JACARIO Polo with complimentary express delivery and a 15-day doorstep size exchange guarantee.
                </p>
                <a href="{{ route('shop.index') }}" class="inline-flex items-center px-8 py-4 bg-[#0B0D10] hover:bg-black text-[#DFCAAB] rounded-xl text-xs font-bold uppercase tracking-widest transition-colors shadow-xl">
                    Shop The Collection
                </a>
            </div>

        </div>
    </section>

@endsection
