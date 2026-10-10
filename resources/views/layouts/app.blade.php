<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
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
    
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <script>
        window.initialCartData = @json(app(\App\Services\CartService::class)->getSummary());

        // Early store shim so inline templates / component init methods never encounter ReferenceError
        window._earlyStores = {
            cartDrawer: {
                isOpen: false,
                isLoading: false,
                cartData: window.initialCartData || { items: [], item_count: 0 },
                open() {
                    if (window.Alpine && window.Alpine.store('cartDrawer')) {
                        window.Alpine.store('cartDrawer').open();
                    } else {
                        this.isOpen = true;
                    }
                },
                close() {
                    if (window.Alpine && window.Alpine.store('cartDrawer')) {
                        window.Alpine.store('cartDrawer').close();
                    } else {
                        this.isOpen = false;
                    }
                },
                toggle() {
                    this.isOpen ? this.close() : this.open();
                },
                addItem(...args) {
                    if (window.Alpine && window.Alpine.store('cartDrawer')) {
                        return window.Alpine.store('cartDrawer').addItem(...args);
                    }
                }
            },
            wishlist: { count: {{ Auth::check() ? Auth::user()->wishlists()->count() : 0 }} },
            nav: { mobileMenuOpen: false, searchOpen: false, quickMenuOpen: false }
        };

        window.$store = new Proxy(window._earlyStores, {
            get(target, prop) {
                if (typeof window.Alpine !== 'undefined' && typeof window.Alpine.store === 'function') {
                    try {
                        const s = window.Alpine.store(prop);
                        if (s !== undefined) return s;
                    } catch (e) {}
                }
                return target[prop] || undefined;
            },
            set(target, prop, val) {
                if (typeof window.Alpine !== 'undefined' && typeof window.Alpine.store === 'function') {
                    try {
                        window.Alpine.store(prop, val);
                        return true;
                    } catch (e) {}
                }
                target[prop] = val;
                return true;
            }
        });
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col bg-[#FAFAFA] text-zinc-900 selection:bg-[#C5A880] selection:text-white">

    <!-- Fixed Top Site Header (Permanently Visible on Scroll) -->
    <div id="site-fixed-header" 
         class="fixed top-0 left-0 right-0 z-40 w-full transition-shadow duration-300"
         style="position: fixed; top: 0; left: 0; right: 0; width: 100%; z-index: 40;"
         x-data="{ isScrolled: false }"
         @scroll.window="isScrolled = (window.pageYOffset > 8)"
         :class="{ 'shadow-lg': isScrolled, 'shadow-xs': !isScrolled }">
        <!-- Navigation Header -->
        @include('components.navbar')
    </div>

    <!-- Header Spacer so page content never goes behind fixed header -->
    <div id="site-header-spacer" class="h-[120px] sm:h-[135px] lg:h-[130px] shrink-0 transition-all duration-150" aria-hidden="true"></div>
    <script>
        (function() {
            function syncHeaderSpacer() {
                var header = document.getElementById('site-fixed-header');
                var spacer = document.getElementById('site-header-spacer');
                if (header && spacer) {
                    var h = header.offsetHeight;
                    if (h > 0) {
                        spacer.style.height = h + 'px';
                    }
                }
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', syncHeaderSpacer);
            } else {
                syncHeaderSpacer();
            }
            window.addEventListener('load', syncHeaderSpacer);
            window.addEventListener('resize', syncHeaderSpacer);
            if (window.ResizeObserver) {
                var ro = new ResizeObserver(function() {
                    syncHeaderSpacer();
                });
                var hEl = document.getElementById('site-fixed-header');
                if (hEl) ro.observe(hEl);
            }
        })();
    </script>

    <!-- Toast Notification Container -->
    @include('components.toast')

    <!-- Cart Slide-over Drawer -->
    @include('components.cart-drawer')

    <!-- Global Search Modal -->
    @include('components.search-modal')

    <!-- Global Quick View Modal -->
    @include('components.quick-view-modal')

    <!-- Main Content Area -->
    <main class="flex-grow pb-16 lg:pb-0">
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

    <!-- Myntra-Style Mobile Bottom Navigation Bar -->
    @include('components.mobile-bottom-nav')

    <!-- Luxury Footer -->
    @include('components.footer')

    <script>
        (function() {
            function syncHeaderSpacer() {
                var h = document.getElementById('site-fixed-header');
                var s = document.getElementById('site-header-spacer');
                if (h && s) {
                    s.style.height = h.offsetHeight + 'px';
                }
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', syncHeaderSpacer);
            } else {
                syncHeaderSpacer();
            }
            window.addEventListener('load', syncHeaderSpacer);
            window.addEventListener('resize', syncHeaderSpacer);
            document.addEventListener('alpine:initialized', function() {
                setTimeout(syncHeaderSpacer, 40);
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>
