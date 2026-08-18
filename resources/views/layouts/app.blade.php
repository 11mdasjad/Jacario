<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'JACARIO — Premium Polo T-Shirts, Made for Every Move')</title>
    <meta name="description" content="@yield('meta_description', 'Discover JACARIO\'s luxury collection of meticulously crafted Polo T-Shirts. Engineered from 100% Supima® cotton, Mulberry silk blends, and performance knit.')">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'JACARIO — The Polo, Perfected.')">
    <meta property="og:description" content="@yield('meta_description', 'Luxury Polo T-Shirts engineered for effortless elegance and timeless style.')">
    <meta property="og:image" content="@yield('og_image', asset('images/polos/black-polo.svg'))">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'JACARIO — The Polo, Perfected.')">
    <meta name="twitter:description" content="@yield('meta_description', 'Luxury Polo T-Shirts engineered for effortless elegance and timeless style.')">
    <meta name="twitter:image" content="@yield('og_image', asset('images/polos/black-polo.svg'))">

    <!-- Favicon & Touch Icons -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/polos/black-polo.svg') }}">

    <!-- Structured Data Organization -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "ClothingStore",
      "name": "JACARIO",
      "description": "Luxury fashion brand specializing exclusively in high-end Polo T-Shirts",
      "url": "{{ url('/') }}",
      "logo": "{{ asset('images/polos/black-polo.svg') }}",
      "telephone": "{{ \App\Models\Setting::get('contact_phone', '+91 (0) 22 8900 1200') }}",
      "address": {
        "@@type": "PostalAddress",
        "streetAddress": "42 Heritage Boulevard, Bandra West",
        "addressLocality": "Mumbai",
        "addressRegion": "Maharashtra",
        "postalCode": "400050",
        "addressCountry": "IN"
      },
      "priceRange": "₹₹₹"
    }
    </script>

    @stack('schema')

    <!-- Fonts & Compiled Assets -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-full flex flex-col bg-[#FAFAFA] text-zinc-900 selection:bg-[#C5A880] selection:text-white">

    <!-- Top Announcement Bar -->
    <div class="bg-[#0B0D10] text-[#DFCAAB] text-xs font-medium tracking-widest uppercase py-2 px-4 text-center border-b border-zinc-800">
        <div class="max-w-7xl mx-auto flex items-center justify-center space-x-3 overflow-hidden whitespace-nowrap">
            <span class="inline-block w-2 h-2 rounded-full bg-[#C5A880] animate-pulse"></span>
            <span>Complimentary Express Shipping on Orders Above ₹1,999</span>
            <span class="text-zinc-600 hidden sm:inline">•</span>
            <span class="hidden sm:inline">Use Code <strong class="text-white tracking-wider">FIRSTPOLO</strong> for ₹300 Off</span>
            <span class="text-zinc-600 hidden md:inline">•</span>
            <span class="hidden md:inline">15-Day Hassle-Free Doorstep Exchanges</span>
        </div>
    </div>

    <!-- Navigation Header -->
    @include('components.navbar')

    <!-- Toast Notification Container -->
    @include('components.toast')

    <!-- Cart Slide-over Drawer -->
    @include('components.cart-drawer')

    <!-- Global Search Modal -->
    @include('components.search-modal')

    <!-- Main Content Area -->
    <main class="flex-grow">
        <!-- Flash Alert Messages -->
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4" x-data="{ show: true }" x-show="show" x-transition>
                <div class="bg-emerald-950/90 text-emerald-100 border border-emerald-800/80 px-4 py-3 rounded-lg shadow-sm flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-300 hover:text-white"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4" x-data="{ show: true }" x-show="show" x-transition>
                <div class="bg-rose-950/90 text-rose-100 border border-rose-800/80 px-4 py-3 rounded-lg shadow-sm flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-rose-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-rose-300 hover:text-white"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Luxury Footer -->
    @include('components.footer')

    @stack('scripts')
</body>
</html>
