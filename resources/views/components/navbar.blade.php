<header class="sticky top-0 z-40 w-full transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-zinc-200/80 shadow-xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20 sm:h-24">
            
            <!-- Left on Mobile: Hamburger Menu Button -->
            <div class="flex items-center space-x-1 lg:hidden">
                <button @click="$store.nav.toggleMobile()" type="button" class="p-2.5 rounded-xl text-zinc-800 hover:bg-zinc-100 hover:text-black focus:outline-none transition-colors" aria-label="Open Navigation Menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <!-- Brand Logo -->
            <div class="flex-shrink-0 flex items-center py-1">
                <a href="{{ route('home') }}" class="group flex items-center focus:outline-none">
                    <img src="{{ asset('images/logo.png') }}" alt="JACARIO" class="h-10 sm:h-14 md:h-16 w-auto object-contain transition-transform duration-200 group-hover:scale-105">
                </a>
            </div>

            <!-- Desktop Navigation Links (Strictly 3 Header Categories) -->
            <nav class="hidden lg:flex items-center space-x-10">
                
                <!-- 1. Men's Polo T-Shirt -->
                <a href="{{ route('shop.index', ['category' => 'mens-polo-t-shirts']) }}" 
                   class="text-xs uppercase font-bold tracking-[0.2em] transition-all py-2 relative after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-0 hover:after:w-full after:h-0.5 after:bg-[#A4845B] after:transition-all after:duration-200 {{ request('category') === 'mens-polo-t-shirts' ? 'text-[#A4845B] after:w-full' : 'text-zinc-800 hover:text-black' }}">
                    Men's Polo T-Shirt
                </a>

                <!-- 2. Round Neck T-Shirt -->
                <a href="{{ route('shop.index', ['category' => 'round-neck-t-shirts']) }}" 
                   class="text-xs uppercase font-bold tracking-[0.2em] transition-all py-2 relative after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-0 hover:after:w-full after:h-0.5 after:bg-[#A4845B] after:transition-all after:duration-200 {{ request('category') === 'round-neck-t-shirts' ? 'text-[#A4845B] after:w-full' : 'text-zinc-800 hover:text-black' }}">
                    Round Neck T-Shirt
                </a>

                <!-- 3. New Arrival T-Shirt -->
                <a href="{{ route('shop.index', ['category' => 'new-arrival-t-shirts']) }}" 
                   class="text-xs uppercase font-bold tracking-[0.2em] transition-all py-2 relative after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-0 hover:after:w-full after:h-0.5 after:bg-[#A4845B] after:transition-all after:duration-200 {{ request('category') === 'new-arrival-t-shirts' || request('collection') === 'new-arrivals' ? 'text-[#A4845B] after:w-full' : 'text-zinc-800 hover:text-black' }}">
                    New Arrival T-Shirt
                </a>

            </nav>

            <!-- Actions (Search, Three-Dot Quick Menu, Account, Wishlist, Cart) -->
            <div class="flex items-center space-x-1 sm:space-x-3 text-zinc-800">
                
                <!-- Search Button -->
                <button @click="$store.nav.searchOpen = true" type="button" class="p-2 sm:p-2.5 rounded-xl text-zinc-700 hover:text-black hover:bg-zinc-100 transition-colors focus:outline-none" aria-label="Search Collection">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>

                <!-- Mobile Three-Dot Quick Menu Button (High Precision on Mobile) -->
                <div class="relative lg:hidden">
                    <button @click="$store.nav.toggleQuickMenu()" 
                            type="button" 
                            class="p-2 rounded-xl text-zinc-800 hover:bg-zinc-100 hover:text-black transition-colors focus:outline-none flex items-center justify-center" 
                            aria-label="More Options">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                        </svg>
                    </button>

                    <!-- Three-Dot Dropdown Card for Mobile -->
                    <div x-show="$store.nav.quickMenuOpen" 
                         @click.away="$store.nav.closeQuickMenu()"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-2xl border border-zinc-200 p-3 z-50 text-zinc-900 space-y-1"
                         style="display: none;">
                        
                        <div class="px-3 py-2 border-b border-zinc-100 flex items-center justify-between">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-400">Quick Navigation</span>
                            <button @click="$store.nav.closeQuickMenu()" class="text-zinc-400 hover:text-black text-xs">✕</button>
                        </div>

                        <a href="{{ route('shop.index', ['category' => 'mens-polo-t-shirts']) }}" @click="$store.nav.closeQuickMenu()" class="flex items-center space-x-2.5 px-3 py-2 text-xs font-semibold text-zinc-800 hover:bg-zinc-50 rounded-xl transition-colors">
                            <span class="w-2 h-2 rounded-full bg-[#A4845B]"></span>
                            <span>Men's Polo T-Shirt</span>
                        </a>

                        <a href="{{ route('shop.index', ['category' => 'round-neck-t-shirts']) }}" @click="$store.nav.closeQuickMenu()" class="flex items-center space-x-2.5 px-3 py-2 text-xs font-semibold text-zinc-800 hover:bg-zinc-50 rounded-xl transition-colors">
                            <span class="w-2 h-2 rounded-full bg-zinc-800"></span>
                            <span>Round Neck T-Shirt</span>
                        </a>

                        <a href="{{ route('shop.index', ['category' => 'new-arrival-t-shirts']) }}" @click="$store.nav.closeQuickMenu()" class="flex items-center space-x-2.5 px-3 py-2 text-xs font-semibold text-zinc-800 hover:bg-zinc-50 rounded-xl transition-colors">
                            <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                            <span>New Arrival T-Shirt</span>
                        </a>

                        <div class="border-t border-zinc-100 my-1"></div>

                        <a href="{{ route('orders.track') }}" @click="$store.nav.closeQuickMenu()" class="flex items-center space-x-2 px-3 py-2 text-xs font-medium text-zinc-700 hover:bg-zinc-50 rounded-xl transition-colors">
                            <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Track My Order</span>
                        </a>

                        <a href="{{ route('wishlist.index') }}" @click="$store.nav.closeQuickMenu()" class="flex items-center space-x-2 px-3 py-2 text-xs font-medium text-zinc-700 hover:bg-zinc-50 rounded-xl transition-colors">
                            <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            <span>My Wishlist</span>
                        </a>

                        @auth
                            <a href="{{ route('account.dashboard') }}" @click="$store.nav.closeQuickMenu()" class="flex items-center space-x-2 px-3 py-2 text-xs font-bold text-zinc-950 bg-zinc-50 hover:bg-zinc-100 rounded-xl transition-colors">
                                <svg class="w-4 h-4 text-zinc-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span>My Account</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" @click="$store.nav.closeQuickMenu()" class="block text-center py-2 bg-zinc-950 text-white rounded-xl text-xs font-bold uppercase tracking-wider mt-1">
                                Sign In / Register
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Desktop Customer Account Dropdown -->
                <div class="relative hidden sm:block" x-data="{ userMenuOpen: false }">
                    @auth
                        <button @click="userMenuOpen = !userMenuOpen" @click.away="userMenuOpen = false" type="button" class="flex items-center space-x-1.5 p-2 rounded-xl text-zinc-800 hover:bg-zinc-100 hover:text-black focus:outline-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span class="hidden md:inline text-xs font-bold tracking-wider text-zinc-900">{{ Str::words(Auth::user()->name, 1, '') }}</span>
                        </button>

                        <div x-show="userMenuOpen" x-transition class="absolute right-0 mt-2 w-56 rounded-2xl shadow-2xl bg-white border border-zinc-200 p-2 z-50 text-zinc-900">
                            <div class="px-3 py-2 border-b border-zinc-100">
                                <p class="text-[11px] text-zinc-500 font-medium">Signed in as</p>
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
                        <a href="{{ route('login') }}" class="p-2 rounded-xl text-zinc-800 hover:bg-zinc-100 hover:text-black transition-colors focus:outline-none flex items-center space-x-1.5" aria-label="Sign In">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span class="hidden md:inline text-xs font-bold tracking-wider text-zinc-900">Sign In</span>
                        </a>
                    @endauth
                </div>

                <!-- Wishlist Icon (Desktop) -->
                <a href="{{ route('wishlist.index') }}" class="relative hidden sm:flex p-2 rounded-xl text-zinc-800 hover:bg-zinc-100 hover:text-black transition-colors" aria-label="Wishlist">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    @auth
                        @php $wishlistCount = Auth::user()->wishlists()->count(); @endphp
                        @if($wishlistCount > 0)
                            <span class="absolute top-1 right-1 bg-zinc-950 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center shadow-xs">
                                {{ $wishlistCount }}
                            </span>
                        @endif
                    @endauth
                </a>

                <!-- Shopping Bag / Cart Drawer Button -->
                <button @click="$store.cartDrawer.open()" type="button" class="relative p-2 sm:p-2.5 rounded-xl text-zinc-800 hover:bg-zinc-100 hover:text-black transition-colors focus:outline-none" aria-label="Shopping Bag">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span x-show="$store.cartDrawer.cartData.item_count > 0" 
                          x-text="$store.cartDrawer.cartData.item_count" 
                          class="absolute top-1 right-1 bg-[#A4845B] text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center shadow-xs">
                    </span>
                </button>

            </div>
        </div>
    </div>

    <!-- Mobile Fullscreen Slide-Out Drawer -->
    <div x-show="$store.nav.mobileMenuOpen" 
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
             class="relative max-w-xs w-full bg-white text-zinc-900 h-full shadow-2xl flex flex-col justify-between py-6 px-6 z-10 overflow-y-auto border-r border-zinc-200">
            
            <div>
                <!-- Mobile Drawer Header -->
                <div class="flex items-center justify-between pb-6 border-b border-zinc-100">
                    <img src="{{ asset('images/logo.png') }}" alt="JACARIO" class="h-10 w-auto object-contain">
                    <button @click="$store.nav.closeMobile()" class="p-2 rounded-xl text-zinc-400 hover:text-black hover:bg-zinc-100 transition-colors" aria-label="Close Menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Three Primary Header Categories on Mobile -->
                <div class="mt-6 space-y-2">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-zinc-400 px-3 pb-1">Bespoke Collections</p>

                    <a href="{{ route('shop.index', ['category' => 'mens-polo-t-shirts']) }}" @click="$store.nav.closeMobile()" class="flex items-center justify-between px-3 py-3 text-xs font-bold tracking-[0.15em] uppercase text-zinc-900 bg-zinc-50 hover:bg-[#A4845B]/10 rounded-xl transition-colors">
                        <span>Men's Polo T-Shirt</span>
                        <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    
                    <a href="{{ route('shop.index', ['category' => 'round-neck-t-shirts']) }}" @click="$store.nav.closeMobile()" class="flex items-center justify-between px-3 py-3 text-xs font-bold tracking-[0.15em] uppercase text-zinc-900 bg-zinc-50 hover:bg-[#A4845B]/10 rounded-xl transition-colors">
                        <span>Round Neck T-Shirt</span>
                        <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>

                    <a href="{{ route('shop.index', ['category' => 'new-arrival-t-shirts']) }}" @click="$store.nav.closeMobile()" class="flex items-center justify-between px-3 py-3 text-xs font-bold tracking-[0.15em] uppercase text-zinc-900 bg-zinc-50 hover:bg-[#A4845B]/10 rounded-xl transition-colors">
                        <span>New Arrival T-Shirt</span>
                        <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                <!-- Secondary Client Links -->
                <div class="mt-6 pt-4 border-t border-zinc-100 space-y-1 text-xs font-medium text-zinc-700">
                    <a href="{{ route('orders.track') }}" @click="$store.nav.closeMobile()" class="flex items-center space-x-2.5 px-3 py-2 hover:bg-zinc-50 rounded-xl">
                        <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Track Delivery Status</span>
                    </a>

                    <a href="{{ route('wishlist.index') }}" @click="$store.nav.closeMobile()" class="flex items-center space-x-2.5 px-3 py-2 hover:bg-zinc-50 rounded-xl">
                        <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        <span>My Wishlist</span>
                    </a>

                    <a href="{{ route('about') }}" @click="$store.nav.closeMobile()" class="flex items-center space-x-2.5 px-3 py-2 hover:bg-zinc-50 rounded-xl">
                        <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>The Atelier Heritage</span>
                    </a>
                </div>
            </div>

            <!-- Footer User Area in Mobile Drawer -->
            <div class="border-t border-zinc-100 pt-6">
                @auth
                    <div class="flex items-center space-x-3 mb-4 p-3 bg-zinc-50 rounded-xl">
                        <div class="w-10 h-10 rounded-full bg-zinc-900 text-[#DFCAAB] flex items-center justify-center font-bold text-sm">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-zinc-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-zinc-500 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <a href="{{ route('account.orders') }}" @click="$store.nav.closeMobile()" class="block w-full text-center py-2.5 bg-zinc-950 text-white rounded-xl text-xs font-bold uppercase tracking-wider">
                            My Orders & Invoices
                        </a>
                        <a href="{{ route('account.dashboard') }}" @click="$store.nav.closeMobile()" class="block w-full text-center py-2.5 border border-zinc-300 text-zinc-800 rounded-xl text-xs font-bold uppercase tracking-wider">
                            Account Dashboard
                        </a>
                    </div>
                @else
                    <a href="{{ route('login') }}" @click="$store.nav.closeMobile()" class="block w-full text-center py-3 bg-zinc-950 text-white rounded-xl text-xs font-bold uppercase tracking-wider mb-2 shadow-md">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" @click="$store.nav.closeMobile()" class="block w-full text-center py-2.5 border border-zinc-300 text-zinc-800 hover:border-black rounded-xl text-xs font-bold uppercase tracking-wider">
                        Create Private Account
                    </a>
                @endauth
            </div>
        </div>
    </div>
</header>
