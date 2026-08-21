<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Console') | JACARIO Maison</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-full text-zinc-900 flex overflow-hidden bg-[#F8FAFC] font-sans" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden" 
         @click="sidebarOpen = false"></div>

    <!-- Sidebar Navigation -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-zinc-200 flex flex-col justify-between transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 shadow-xs">
        
        <div>
            <div class="h-20 flex items-center justify-between px-6 border-b border-zinc-200">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                    <img src="{{ asset('images/logo.png') }}" alt="JACARIO" class="h-9 sm:h-10 w-auto object-contain">
                </a>
                <span class="text-[9px] font-bold uppercase tracking-widest bg-zinc-100 text-zinc-700 border border-zinc-200 px-2 py-0.5 rounded">Console</span>
            </div>

            <!-- Staff Identity Card -->
            <div class="p-4 mx-4 mt-4 rounded-xl bg-zinc-50 border border-zinc-200 flex items-center space-x-3">
                <div class="w-9 h-9 rounded-full bg-[#A4845B] text-white flex items-center justify-center font-bold text-xs shadow-xs">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-zinc-900 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-[#8C6D46] uppercase tracking-wider font-bold">{{ str_replace('_', ' ', Auth::user()->role) }}</p>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="mt-6 px-4 space-y-1 text-xs font-semibold">
                
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-zinc-950 text-white font-bold shadow-sm' : 'text-zinc-600 hover:text-zinc-950 hover:bg-zinc-100' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.products.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.products.*') ? 'bg-zinc-950 text-white font-bold shadow-sm' : 'text-zinc-600 hover:text-zinc-950 hover:bg-zinc-100' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <span>Products & Inventory</span>
                </a>

                <a href="{{ route('admin.banners.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.banners.*') ? 'bg-zinc-950 text-white font-bold shadow-sm' : 'text-zinc-600 hover:text-zinc-950 hover:bg-zinc-100' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Hero Banners (5 Carousel)</span>
                </a>

                <a href="{{ route('admin.orders.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.orders.*') ? 'bg-zinc-950 text-white font-bold shadow-sm' : 'text-zinc-600 hover:text-zinc-950 hover:bg-zinc-100' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    <span>Orders & Fulfillment</span>
                </a>

                <a href="{{ route('admin.customers.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.customers.*') ? 'bg-zinc-950 text-white font-bold shadow-sm' : 'text-zinc-600 hover:text-zinc-950 hover:bg-zinc-100' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>Customer Accounts</span>
                </a>

                <a href="{{ route('admin.coupons.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.coupons.*') ? 'bg-zinc-950 text-white font-bold shadow-sm' : 'text-zinc-600 hover:text-zinc-950 hover:bg-zinc-100' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    <span>Coupons & Promos</span>
                </a>

                <a href="{{ route('admin.reviews.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.reviews.*') ? 'bg-zinc-950 text-white font-bold shadow-sm' : 'text-zinc-600 hover:text-zinc-950 hover:bg-zinc-100' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    <span>Review Moderation</span>
                </a>

                <a href="{{ route('admin.settings.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.settings.*') ? 'bg-zinc-950 text-white font-bold shadow-sm' : 'text-zinc-600 hover:text-zinc-950 hover:bg-zinc-100' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Store Settings & Policies</span>
                </a>

            </nav>
        </div>

        <!-- Footer / Storefront Quick Link -->
        <div class="p-4 border-t border-zinc-200 space-y-2 bg-white">
            <a href="{{ route('home') }}" target="_blank" class="flex items-center justify-center space-x-2 w-full py-2.5 rounded-xl bg-zinc-100 hover:bg-zinc-200 text-xs font-bold text-zinc-900 transition-colors border border-zinc-200">
                <span>View Storefront</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-center py-2 text-xs font-semibold text-rose-600 hover:text-rose-700 transition-colors">
                    Sign Out
                </button>
            </form>
        </div>

    </aside>

    <!-- Main Content Container -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-[#F8FAFC]">
        
        <!-- Admin Top Navigation Header -->
        <header class="h-20 bg-white border-b border-zinc-200 flex items-center justify-between px-6 z-10 shrink-0 shadow-xs">
            <div class="flex items-center space-x-3">
                <button @click="sidebarOpen = true" class="lg:hidden text-zinc-500 hover:text-zinc-900 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h2 class="text-lg font-serif-luxury font-bold text-zinc-950 tracking-wide">
                    @yield('header_title', 'Administrative Console')
                </h2>
            </div>

            <div class="flex items-center space-x-3" 
                 x-data="{
                    currentDate: '',
                    currentTime: '',
                    init() {
                        this.updateClock();
                        setInterval(() => this.updateClock(), 1000);
                    },
                    updateClock() {
                        const now = new Date();
                        const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                        this.currentDate = now.toLocaleDateString('en-US', dateOptions);
                        
                        let hours = now.getHours();
                        const minutes = String(now.getMinutes()).padStart(2, '0');
                        const seconds = String(now.getSeconds()).padStart(2, '0');
                        const ampm = hours >= 12 ? 'PM' : 'AM';
                        hours = hours % 12;
                        hours = hours ? hours : 12; // 0 should be 12
                        const formattedHours = String(hours).padStart(2, '0');
                        this.currentTime = `${formattedHours}:${minutes}:${seconds} ${ampm}`;
                    }
                 }">
                <!-- Live System Indicator Badge -->
                <div class="hidden md:flex items-center space-x-2 px-3 py-1.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-[11px] font-bold">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Live Storefront Online</span>
                </div>

                <!-- Real-Time Date & Clock Card -->
                <div class="flex items-center space-x-2.5 px-3.5 py-1.5 rounded-xl bg-zinc-50 border border-zinc-200 shadow-xs">
                    <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-2">
                        <span class="text-xs font-semibold text-zinc-800" x-text="currentDate">{{ date('l, F j, Y') }}</span>
                        <span class="hidden sm:inline text-zinc-300">•</span>
                        <span class="text-xs font-mono font-bold text-zinc-950 bg-white px-2 py-0.5 rounded border border-zinc-200" x-text="currentTime">--:--:--</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Scrollable Body Area -->
        <main class="flex-1 overflow-y-auto p-6 sm:p-8 bg-[#F8FAFC] text-zinc-900">
            
            <!-- Flash Message Alerts -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-semibold flex items-center justify-between shadow-xs">
                    <div class="flex items-center space-x-2">
                        <span class="text-emerald-600">✓</span>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 text-xs font-semibold flex items-center justify-between shadow-xs">
                    <div class="flex items-center space-x-2">
                        <span class="text-rose-600">✕</span>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

    </div>

    @include('components.toast')

    @stack('scripts')
</body>
</html>
