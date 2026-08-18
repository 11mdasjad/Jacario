@php
    $currentRoute = Route::currentRouteName();
    $currentCategory = request('category');
    $currentCollection = request('collection');
    $wishlistInitialCount = Auth::check() ? Auth::user()->wishlists()->count() : 0;
@endphp

<!-- Myntra-Style Floating / Fixed Mobile Bottom Navigation Bar -->
<nav class="fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-lg border-t border-zinc-200/90 shadow-[0_-4px_20px_rgba(0,0,0,0.06)] lg:hidden transition-transform duration-300 pb-safe"
     x-data="{ 
        init() {
            if ($store.wishlist) {
                $store.wishlist.count = {{ $wishlistInitialCount }};
            }
        }
     }">
    <div class="grid grid-cols-5 h-16 items-center px-1 max-w-lg mx-auto">
        
        <!-- 1. Home Tab -->
        <a href="{{ route('home') }}" 
           class="flex flex-col items-center justify-center py-1 group relative transition-all {{ $currentRoute === 'home' ? 'text-[#8C6D46]' : 'text-zinc-500 hover:text-zinc-900' }}">
            <div class="relative flex items-center justify-center">
                <svg class="w-5 h-5 transition-transform duration-200 group-active:scale-90 {{ $currentRoute === 'home' ? 'stroke-[2.2]' : 'stroke-[1.8]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                @if($currentRoute === 'home')
                    <span class="absolute -top-1 right-0 w-1.5 h-1.5 rounded-full bg-[#8C6D46] active-tab-dot"></span>
                @endif
            </div>
            <span class="text-[10px] font-bold tracking-tight mt-1 {{ $currentRoute === 'home' ? 'font-extrabold text-[#8C6D46]' : 'font-medium' }}">Home</span>
        </a>

        <!-- 2. Categories Tab -->
        <button type="button" 
                @click="$store.nav.toggleMobile()" 
                class="flex flex-col items-center justify-center py-1 group relative transition-all {{ $currentRoute === 'shop.index' && !request('collection') ? 'text-[#8C6D46]' : 'text-zinc-500 hover:text-zinc-900' }}">
            <div class="relative flex items-center justify-center">
                <svg class="w-5 h-5 transition-transform duration-200 group-active:scale-90 stroke-[1.8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
            </div>
            <span class="text-[10px] font-bold tracking-tight mt-1 font-medium">Categories</span>
        </button>

        <!-- 3. Studio / Trends Tab -->
        <a href="{{ route('shop.index', ['collection' => 'new-arrivals']) }}" 
           class="flex flex-col items-center justify-center py-1 group relative transition-all {{ $currentCollection === 'new-arrivals' ? 'text-[#8C6D46]' : 'text-zinc-500 hover:text-zinc-900' }}">
            <div class="relative flex items-center justify-center">
                <svg class="w-5 h-5 transition-transform duration-200 group-active:scale-90 {{ $currentCollection === 'new-arrivals' ? 'stroke-[2.2]' : 'stroke-[1.8]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
                @if($currentCollection === 'new-arrivals')
                    <span class="absolute -top-1 right-0 w-1.5 h-1.5 rounded-full bg-[#8C6D46] active-tab-dot"></span>
                @endif
            </div>
            <span class="text-[10px] font-bold tracking-tight mt-1 {{ $currentCollection === 'new-arrivals' ? 'font-extrabold text-[#8C6D46]' : 'font-medium' }}">Studio</span>
        </a>

        <!-- 4. Wishlist Tab -->
        <a href="{{ route('wishlist.index') }}" 
           class="flex flex-col items-center justify-center py-1 group relative transition-all {{ $currentRoute === 'wishlist.index' ? 'text-[#8C6D46]' : 'text-zinc-500 hover:text-zinc-900' }}">
            <div class="relative flex items-center justify-center">
                <svg class="w-5 h-5 transition-transform duration-200 group-active:scale-90 {{ $currentRoute === 'wishlist.index' ? 'fill-rose-500 text-rose-500 stroke-[1.5]' : 'stroke-[1.8]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <span x-show="$store.wishlist && $store.wishlist.count > 0" 
                      x-text="$store.wishlist.count" 
                      class="absolute -top-1.5 -right-2.5 bg-rose-600 text-white text-[9px] font-black min-w-[15px] h-[15px] px-1 rounded-full flex items-center justify-center shadow-xs"
                      style="{{ $wishlistInitialCount > 0 ? '' : 'display: none;' }}">
                    {{ $wishlistInitialCount }}
                </span>
            </div>
            <span class="text-[10px] font-bold tracking-tight mt-1 {{ $currentRoute === 'wishlist.index' ? 'font-extrabold text-[#8C6D46]' : 'font-medium' }}">Wishlist</span>
        </a>

        <!-- 5. Profile Tab -->
        <a href="{{ Auth::check() ? route('account.dashboard') : route('login') }}" 
           class="flex flex-col items-center justify-center py-1 group relative transition-all {{ str_starts_with($currentRoute, 'account.') || $currentRoute === 'login' || $currentRoute === 'register' ? 'text-[#8C6D46]' : 'text-zinc-500 hover:text-zinc-900' }}">
            <div class="relative flex items-center justify-center">
                @auth
                    <div class="w-5 h-5 rounded-full bg-zinc-900 text-[#DFCAAB] flex items-center justify-center text-[10px] font-bold border border-zinc-300">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @else
                    <svg class="w-5 h-5 transition-transform duration-200 group-active:scale-90 stroke-[1.8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                @endauth
            </div>
            <span class="text-[10px] font-bold tracking-tight mt-1 {{ str_starts_with($currentRoute, 'account.') ? 'font-extrabold text-[#8C6D46]' : 'font-medium' }}">
                {{ Auth::check() ? 'Profile' : 'Profile' }}
            </span>
        </a>

    </div>
</nav>
