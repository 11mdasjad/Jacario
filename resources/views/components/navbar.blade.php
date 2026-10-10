<header class="w-full bg-white border-b border-zinc-200/90">
    <!-- ========================================================
         TIER 1: MAIN HEADER (LOGO, SEARCH, HELPLINE, ACCOUNT, CART)
         ======================================================== -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 sm:h-20 gap-3 lg:gap-6">
            
            <!-- Left on Mobile: Hamburger Menu Button & Logo -->
            <div class="flex items-center space-x-2 sm:space-x-3 shrink-0">
                <button @click="$store.nav.toggleMobile()" type="button" class="p-2 -ml-2 rounded-xl text-zinc-800 hover:bg-zinc-100 focus:outline-none transition-colors lg:hidden" aria-label="Open Navigation Menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <!-- Brand Logo -->
                <a href="{{ route('home') }}" class="group flex items-center focus:outline-none shrink-0" aria-label="JACARIO Home">
                    <img src="{{ asset('images/logo-horizontal.png') }}" alt="JACARIO" class="h-8 sm:h-9 md:h-10 w-auto object-contain transition-transform duration-200 group-hover:scale-102">
                </a>
            </div>

            <!-- Center on Desktop: Prominent Search Bar (Webishop Theme Style) -->
            <div class="hidden lg:flex flex-1 max-w-2xl mx-2">
                <form action="{{ route('shop.index') }}" method="GET" class="relative w-full flex items-center">
                    <div class="relative w-full">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Search for polo t-shirts, round neck, silk knit, colors..." 
                               class="w-full pl-5 pr-28 py-2.5 sm:py-3 text-xs sm:text-sm bg-zinc-50 hover:bg-white focus:bg-white text-zinc-900 placeholder-zinc-400 rounded-full border border-zinc-300 focus:border-[#A4845B] focus:ring-2 focus:ring-[#A4845B]/20 transition-all outline-none shadow-xs">
                        
                        <button type="submit" 
                                class="absolute right-1.5 top-1/2 -translate-y-1/2 px-4 py-1.5 sm:py-2 bg-[#0B0D10] hover:bg-[#A4845B] text-white text-xs font-bold uppercase tracking-wider rounded-full transition-all duration-200 flex items-center space-x-1.5 shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span class="hidden xl:inline">Search</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right Actions: Help Line, Account, Wishlist, Cart -->
            <div class="flex items-center space-x-2 sm:space-x-4 shrink-0 text-zinc-800">
                
                <!-- Concierge Helpline (Desktop Only) -->
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', \App\Models\Setting::get('contact_phone', '+91 22 8900 1200')) }}" 
                   class="hidden xl:flex items-center space-x-2.5 p-1.5 rounded-xl hover:bg-zinc-100 transition-colors group">
                    <div class="w-9 h-9 rounded-full bg-amber-500/10 text-[#8C6D46] flex items-center justify-center shrink-0 group-hover:bg-[#8C6D46] group-hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <div class="text-left leading-tight">
                        <span class="block text-[10px] uppercase font-bold tracking-wider text-zinc-400">Call For Concierge</span>
                        <span class="block text-xs font-bold text-zinc-900">{{ \App\Models\Setting::get('contact_phone', '+91 22 8900 1200') }}</span>
                    </div>
                </a>

                <!-- Account / Sign In Dropdown -->
                <div class="relative" x-data="{ userMenuOpen: false }">
                    @auth
                        <button @click="userMenuOpen = !userMenuOpen" @click.away="userMenuOpen = false" type="button" class="flex items-center space-x-2 p-1.5 sm:p-2 rounded-xl text-zinc-800 hover:bg-zinc-100 hover:text-black focus:outline-none transition-colors">
                            <div class="w-8 h-8 rounded-full bg-zinc-950 text-[#DFCAAB] flex items-center justify-center text-xs font-bold shadow-xs">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="hidden sm:block text-left leading-tight">
                                <span class="block text-[10px] text-zinc-400 font-bold uppercase tracking-wider">Account</span>
                                <span class="block text-xs font-bold text-zinc-900 truncate max-w-[90px]">{{ Str::words(Auth::user()->name, 1, '') }}</span>
                            </div>
                            <svg class="w-3.5 h-3.5 text-zinc-500 transition-transform duration-200 hidden sm:block" :class="{ 'rotate-180': userMenuOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <div x-show="userMenuOpen" 
                             x-cloak
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="absolute right-0 mt-2 w-56 rounded-2xl shadow-2xl bg-white border border-zinc-200 p-2 z-50 text-zinc-900">
                            <div class="px-3 py-2 border-b border-zinc-100">
                                <p class="text-[10px] text-zinc-400 font-bold uppercase tracking-wider">Signed in as</p>
                                <p class="text-xs font-bold text-zinc-900 truncate">{{ Auth::user()->name }}</p>
                            </div>

                            @if(Auth::user()->isStaff())
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2 px-3 py-2 text-xs font-semibold text-[#8C6D46] bg-amber-50/60 hover:bg-amber-100/80 rounded-xl transition-colors mt-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span>Staff Administration</span>
                                </a>
                            @endif

                            <a href="{{ route('account.dashboard') }}" class="flex items-center space-x-2 px-3 py-2 text-xs font-medium text-zinc-700 hover:bg-zinc-50 hover:text-black rounded-xl transition-colors">
                                <span>My Account</span>
                            </a>
                            <a href="{{ route('account.orders') }}" class="flex items-center space-x-2 px-3 py-2 text-xs font-medium text-zinc-700 hover:bg-zinc-50 hover:text-black rounded-xl transition-colors">
                                <span>Order History</span>
                            </a>
                            <a href="{{ route('account.addresses') }}" class="flex items-center space-x-2 px-3 py-2 text-xs font-medium text-zinc-700 hover:bg-zinc-50 hover:text-black rounded-xl transition-colors">
                                <span>Saved Addresses</span>
                            </a>
                            <a href="{{ route('wishlist.index') }}" class="flex items-center space-x-2 px-3 py-2 text-xs font-medium text-zinc-700 hover:bg-zinc-50 hover:text-black rounded-xl transition-colors">
                                <span>My Wishlist</span>
                            </a>

                            <div class="border-t border-zinc-100 my-1"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center space-x-2 px-3 py-2 text-xs font-medium text-rose-600 hover:bg-rose-50 rounded-xl transition-colors">
                                    <span>Sign Out</span>
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center space-x-2 p-1.5 sm:p-2 rounded-xl text-zinc-800 hover:bg-zinc-100 hover:text-black transition-colors focus:outline-none" aria-label="Sign In">
                            <div class="w-8 h-8 rounded-full bg-zinc-100 flex items-center justify-center text-zinc-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div class="hidden sm:block text-left leading-tight">
                                <span class="block text-[10px] text-zinc-400 font-bold uppercase tracking-wider">Account</span>
                                <span class="block text-xs font-bold text-zinc-900">Sign In</span>
                            </div>
                        </a>
                    @endauth
                </div>

                <!-- Wishlist Icon with live Alpine Badge -->
                <a href="{{ route('wishlist.index') }}" class="relative p-2 sm:p-2.5 rounded-xl text-zinc-800 hover:bg-zinc-100 hover:text-black transition-colors" aria-label="Wishlist">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    <span x-cloak
                          x-show="$store.wishlist && $store.wishlist.count > 0" 
                          x-text="$store.wishlist.count" 
                          class="absolute top-1 right-1 bg-rose-600 text-white text-[10px] font-black w-4 h-4 rounded-full flex items-center justify-center shadow-xs"
                          style="{{ (Auth::check() && Auth::user()->wishlists()->count() > 0) ? '' : 'display: none;' }}">
                        {{ Auth::check() ? Auth::user()->wishlists()->count() : '' }}
                    </span>
                </a>

                <!-- Shopping Cart Pill Button (Webishop Style with count & total) -->
                @php
                    $cartInitial = app(\App\Services\CartService::class)->getSummary();
                    $cartInitialCount = $cartInitial['item_count'] ?? 0;
                    $cartInitialSubtotal = $cartInitial['subtotal'] ?? 0;
                @endphp
                <a href="{{ route('cart.index') }}" 
                   class="flex items-center space-x-2 pl-2 sm:pl-3 pr-2.5 sm:pr-3.5 py-1.5 sm:py-2 rounded-full bg-zinc-900 hover:bg-black text-white transition-all duration-200 shadow-sm group focus:outline-none" 
                   aria-label="Shopping Cart">
                    <div class="relative flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#DFCAAB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <span x-cloak
                              x-show="$store.cartDrawer && $store.cartDrawer.cartData && $store.cartDrawer.cartData.item_count > 0" 
                              x-text="$store.cartDrawer.cartData.item_count" 
                              class="absolute -top-1.5 -right-2 bg-[#A4845B] text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center ring-2 ring-zinc-900"
                              style="{{ $cartInitialCount > 0 ? '' : 'display: none !important;' }}">
                            {{ $cartInitialCount > 0 ? $cartInitialCount : '' }}
                        </span>
                    </div>
                    <div class="hidden sm:flex flex-col text-left leading-none pl-1">
                        <span class="text-[9px] uppercase font-bold tracking-wider text-zinc-400">Cart</span>
                        <span class="text-xs font-bold text-[#DFCAAB]">
                            ₹<span x-text="($store.cartDrawer && $store.cartDrawer.cartData && $store.cartDrawer.cartData.subtotal !== undefined) ? Number($store.cartDrawer.cartData.subtotal).toLocaleString('en-IN') : '{{ number_format($cartInitialSubtotal) }}'">{{ number_format($cartInitialSubtotal) }}</span>
                        </span>
                    </div>
                </a>

            </div>
        </div>
    </div>

    <!-- ========================================================
         TIER 2: SECONDARY NAVIGATION BAR (DESKTOP - CLEAN & CENTERED)
         ======================================================== -->
    <div class="hidden lg:block bg-[#FAF8F5] border-t border-zinc-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-center h-12">
                
                <!-- Primary Navigation Links (Centered) -->
                <nav class="flex items-center space-x-8 xl:space-x-10 2xl:space-x-12">
                    <a href="{{ route('home') }}" 
                       class="text-xs uppercase font-bold tracking-[0.16em] transition-all py-2 relative after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-0 hover:after:w-full after:h-0.5 after:bg-[#A4845B] after:transition-all after:duration-200 {{ request()->routeIs('home') ? 'text-[#8C6D46] after:w-full' : 'text-zinc-800 hover:text-black' }}">
                        Home
                    </a>

                    <a href="{{ route('shop.index', ['category' => 'mens-polo-t-shirts']) }}" 
                       class="text-xs uppercase font-bold tracking-[0.16em] transition-all py-2 relative after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-0 hover:after:w-full after:h-0.5 after:bg-[#A4845B] after:transition-all after:duration-200 {{ request('category') === 'mens-polo-t-shirts' ? 'text-[#8C6D46] after:w-full' : 'text-zinc-800 hover:text-black' }}">
                        Men's Polo
                    </a>

                    <a href="{{ route('shop.index', ['category' => 'round-neck-t-shirts']) }}" 
                       class="text-xs uppercase font-bold tracking-[0.16em] transition-all py-2 relative after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-0 hover:after:w-full after:h-0.5 after:bg-[#A4845B] after:transition-all after:duration-200 {{ request('category') === 'round-neck-t-shirts' ? 'text-[#8C6D46] after:w-full' : 'text-zinc-800 hover:text-black' }}">
                        Round Neck
                    </a>

                    <a href="{{ route('shop.index', ['category' => 'new-arrival-t-shirts']) }}" 
                       class="text-xs uppercase font-bold tracking-[0.16em] transition-all py-2 relative after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-0 hover:after:w-full after:h-0.5 after:bg-[#A4845B] after:transition-all after:duration-200 {{ request('category') === 'new-arrival-t-shirts' || request('collection') === 'new-arrivals' ? 'text-[#8C6D46] after:w-full' : 'text-zinc-800 hover:text-black' }}">
                        New Arrivals
                    </a>

                    <!-- Jacario Dedicated Customize Studio -->
                    <a href="{{ route('customize.index') }}" 
                       class="inline-flex items-center space-x-1.5 px-3.5 py-1 rounded-full text-xs uppercase font-extrabold tracking-wider transition-all {{ request()->routeIs('customize.*') ? 'bg-zinc-950 text-[#DFCAAB] shadow-xs' : 'bg-amber-500/10 text-zinc-900 hover:bg-zinc-950 hover:text-[#DFCAAB] border border-amber-600/30' }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#A4845B]"></span>
                        <span>Customize Studio</span>
                        <span class="text-[8px] font-black uppercase px-1 py-0.2 rounded bg-[#A4845B] text-white">HOT</span>
                    </a>

                    <a href="{{ route('shop.index', ['collection' => 'bestsellers']) }}" 
                       class="text-xs uppercase font-bold tracking-[0.16em] transition-all py-2 relative after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-0 hover:after:w-full after:h-0.5 after:bg-[#A4845B] after:transition-all after:duration-200 {{ request('collection') === 'bestsellers' ? 'text-[#8C6D46] after:w-full' : 'text-zinc-800 hover:text-black' }}">
                        Best Sellers
                    </a>

                    <a href="{{ route('about') }}" 
                       class="text-xs uppercase font-bold tracking-[0.16em] transition-all py-2 relative after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-0 hover:after:w-full after:h-0.5 after:bg-[#A4845B] after:transition-all after:duration-200 {{ request()->routeIs('about') ? 'text-[#8C6D46] after:w-full' : 'text-zinc-800 hover:text-black' }}">
                        The Atelier
                    </a>
                </nav>

            </div>
        </div>
    </div>

    <!-- ========================================================
         MOBILE SEARCH & HORIZONTAL SCROLL STRIP
         ======================================================== -->
    <div class="lg:hidden bg-[#FAF8F5] border-t border-zinc-200/90 px-3 py-2 space-y-2">
        <!-- Mobile Search Input -->
        <form action="{{ route('shop.index') }}" method="GET" class="relative">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}"
                   placeholder="Search luxury polos, round neck..." 
                   class="w-full pl-9 pr-4 py-2 text-xs bg-white text-zinc-900 rounded-xl border border-zinc-300 focus:border-[#A4845B] outline-none shadow-xs">
            <svg class="w-4 h-4 text-zinc-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </form>

        <!-- Horizontal Scroll Category Badges -->
        <div class="flex items-center space-x-1.5 overflow-x-auto no-scrollbar pb-0.5">
            <a href="{{ route('shop.index') }}" 
               class="px-3 py-1 rounded-full text-xs font-bold tracking-tight shrink-0 transition-colors {{ !request('collection') && !request('category') ? 'bg-zinc-950 text-white shadow-xs' : 'bg-white text-zinc-700 border border-zinc-200' }}">
                All Polos
            </a>
            <a href="{{ route('shop.index', ['category' => 'mens-polo-t-shirts']) }}" 
               class="px-3 py-1 rounded-full text-xs font-bold tracking-tight shrink-0 transition-colors {{ request('category') === 'mens-polo-t-shirts' ? 'bg-[#0B0D10] text-[#DFCAAB]' : 'bg-white text-zinc-700 border border-zinc-200' }}">
                Men's Polo
            </a>
            <a href="{{ route('shop.index', ['category' => 'round-neck-t-shirts']) }}" 
               class="px-3 py-1 rounded-full text-xs font-bold tracking-tight shrink-0 transition-colors {{ request('category') === 'round-neck-t-shirts' ? 'bg-[#0B0D10] text-[#DFCAAB]' : 'bg-white text-zinc-700 border border-zinc-200' }}">
                Round Neck
            </a>
            <a href="{{ route('shop.index', ['category' => 'new-arrival-t-shirts']) }}" 
               class="px-3 py-1 rounded-full text-xs font-bold tracking-tight shrink-0 transition-colors {{ request('category') === 'new-arrival-t-shirts' ? 'bg-[#0B0D10] text-[#DFCAAB]' : 'bg-white text-zinc-700 border border-zinc-200' }}">
                ✨ New Arrivals
            </a>
            <a href="{{ route('customize.index') }}" 
               class="px-3 py-1 rounded-full text-xs font-bold tracking-tight shrink-0 transition-colors bg-gradient-to-r from-amber-500/20 to-amber-600/20 text-amber-950 border border-amber-500/40">
                🎨 Customize Studio
            </a>
            <a href="{{ route('shop.index', ['collection' => 'bestsellers']) }}" 
               class="px-3 py-1 rounded-full text-xs font-bold tracking-tight shrink-0 transition-colors {{ request('collection') === 'bestsellers' ? 'bg-[#0B0D10] text-[#DFCAAB]' : 'bg-white text-zinc-700 border border-zinc-200' }}">
                🔥 Best Sellers
            </a>
        </div>
    </div>

    <!-- ========================================================
         MOBILE FULLSCREEN SLIDE-OUT DRAWER
         ======================================================== -->
    <div x-show="$store.nav.mobileMenuOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex lg:hidden"
         style="display: none;"
         @keydown.window.escape="$store.nav.closeMobile()">
        
        <!-- Backdrop Blur -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="$store.nav.closeMobile()"></div>

        <div x-show="$store.nav.mobileMenuOpen"
             x-transition:enter="transform transition ease-out duration-300"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transform transition ease-in duration-200"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="relative max-w-xs w-full bg-white text-zinc-900 h-full shadow-2xl flex flex-col justify-between z-10 overflow-y-auto border-r border-zinc-200">
            
            <div>
                <!-- User Header Card in Drawer -->
                <div class="bg-gradient-to-br from-zinc-950 via-zinc-900 to-zinc-950 text-white p-5">
                    <div class="flex items-center justify-between pb-3">
                        <img src="{{ asset('images/logo-horizontal-white.png') }}" alt="JACARIO" class="h-7 sm:h-8 w-auto object-contain opacity-95">
                        <button @click="$store.nav.closeMobile()" class="p-1.5 rounded-lg text-zinc-400 hover:text-white hover:bg-zinc-800 transition-colors" aria-label="Close Menu">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    @auth
                        <div class="flex items-center space-x-3 pt-2">
                            <div class="w-11 h-11 rounded-full bg-[#DFCAAB] text-zinc-950 flex items-center justify-center font-bold text-sm shadow-md">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center space-x-1.5">
                                    <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</p>
                                    <span class="text-[9px] font-bold text-amber-300 bg-amber-950/80 px-1.5 py-0.2 rounded border border-amber-500/40">VIP</span>
                                </div>
                                <p class="text-[10px] text-zinc-400 truncate">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                    @else
                        <div class="pt-2">
                            <p class="text-xs font-bold text-white uppercase tracking-wider">Welcome to JACARIO</p>
                            <p class="text-[11px] text-zinc-400 mt-0.5">Luxury Polo T-Shirts for Every Move</p>
                            <div class="mt-3">
                                <a href="{{ route('auth.google') }}" @click="$store.nav.closeMobile()" class="w-full flex items-center justify-center space-x-2 py-2.5 text-center bg-white hover:bg-zinc-100 text-zinc-950 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm transition-colors">
                                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24">
                                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                                    </svg>
                                    <span>Sign in with Google</span>
                                </a>
                            </div>
                        </div>
                    @endauth
                </div>

                <!-- Primary Category Navigation List -->
                <div class="p-4 space-y-1">
                    <!-- Featured Customize Studio Option -->
                    <div class="mb-3 p-1 rounded-2xl bg-gradient-to-r from-amber-500/20 via-amber-600/10 to-amber-700/20 border border-[#A4845B]/40">
                        <a href="{{ route('customize.index') }}" @click="$store.nav.closeMobile()" class="flex items-center justify-between p-3 bg-zinc-950 text-white rounded-xl shadow-md group">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-lg bg-[#A4845B] text-white flex items-center justify-center shadow-xs">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="flex items-center space-x-1.5">
                                        <span class="text-xs font-black uppercase tracking-widest text-[#DFCAAB]">Customize Studio</span>
                                        <span class="text-[8px] font-extrabold uppercase px-1.5 py-0.2 rounded bg-amber-400 text-zinc-950">HOT</span>
                                    </div>
                                    <p class="text-[10px] text-zinc-400">Polo & T-Shirt Bespoke Print</p>
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-[#A4845B] group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                    <p class="text-[10px] font-bold uppercase tracking-widest text-zinc-400 px-3 py-1">Shop Catalog</p>

                    <a href="{{ route('shop.index', ['category' => 'mens-polo-t-shirts']) }}" @click="$store.nav.closeMobile()" class="flex items-center justify-between px-3 py-3 text-xs font-bold tracking-wider uppercase text-zinc-900 hover:bg-zinc-50 rounded-xl transition-colors">
                        <div class="flex items-center space-x-3">
                            <span class="w-2 h-2 rounded-full bg-[#A4845B]"></span>
                            <span>Men's Polo T-Shirt</span>
                        </div>
                        <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    
                    <a href="{{ route('shop.index', ['category' => 'round-neck-t-shirts']) }}" @click="$store.nav.closeMobile()" class="flex items-center justify-between px-3 py-3 text-xs font-bold tracking-wider uppercase text-zinc-900 hover:bg-zinc-50 rounded-xl transition-colors">
                        <div class="flex items-center space-x-3">
                            <span class="w-2 h-2 rounded-full bg-zinc-800"></span>
                            <span>Round Neck T-Shirt</span>
                        </div>
                        <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>

                    <a href="{{ route('shop.index', ['category' => 'new-arrival-t-shirts']) }}" @click="$store.nav.closeMobile()" class="flex items-center justify-between px-3 py-3 text-xs font-bold tracking-wider uppercase text-zinc-900 hover:bg-zinc-50 rounded-xl transition-colors">
                        <div class="flex items-center space-x-3">
                            <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                            <span>New Arrival T-Shirt</span>
                        </div>
                        <span class="text-[9px] font-bold uppercase bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full">NEW</span>
                    </a>

                    <a href="{{ route('shop.index', ['collection' => 'bestsellers']) }}" @click="$store.nav.closeMobile()" class="flex items-center justify-between px-3 py-3 text-xs font-bold tracking-wider uppercase text-zinc-900 hover:bg-zinc-50 rounded-xl transition-colors">
                        <div class="flex items-center space-x-3">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            <span>Best Sellers</span>
                        </div>
                        <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                <!-- Secondary Customer Links -->
                <div class="p-4 pt-1 border-t border-zinc-100 space-y-1 text-xs font-medium text-zinc-700">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-zinc-400 px-3 py-1">Quick Services</p>

                    <a href="{{ route('orders.track') }}" @click="$store.nav.closeMobile()" class="flex items-center space-x-3 px-3 py-2.5 hover:bg-zinc-50 rounded-xl">
                        <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Track Delivery Status</span>
                    </a>

                    <a href="{{ route('wishlist.index') }}" @click="$store.nav.closeMobile()" class="flex items-center space-x-3 px-3 py-2.5 hover:bg-zinc-50 rounded-xl">
                        <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        <span>My Wishlist</span>
                    </a>

                    @auth
                        <a href="{{ route('account.orders') }}" @click="$store.nav.closeMobile()" class="flex items-center space-x-3 px-3 py-2.5 hover:bg-zinc-50 rounded-xl">
                            <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            <span>My Orders</span>
                        </a>

                        <a href="{{ route('account.addresses') }}" @click="$store.nav.closeMobile()" class="flex items-center space-x-3 px-3 py-2.5 hover:bg-zinc-50 rounded-xl">
                            <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            <span>Saved Addresses</span>
                        </a>
                    @endauth

                    <a href="{{ route('about') }}" @click="$store.nav.closeMobile()" class="flex items-center space-x-3 px-3 py-2.5 hover:bg-zinc-50 rounded-xl">
                        <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>The Atelier Heritage</span>
                    </a>
                </div>
            </div>

            <!-- Footer User Area in Mobile Drawer -->
            <div class="border-t border-zinc-100 p-4">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full py-2.5 text-center text-xs font-bold text-rose-600 hover:bg-rose-50 rounded-xl transition-colors">
                            Sign Out
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</header>
