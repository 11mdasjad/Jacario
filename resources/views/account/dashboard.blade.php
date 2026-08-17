@extends('layouts.app')

@section('title', 'My Private Account | JACARIO')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <!-- Top Account Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between pb-6 border-b border-zinc-200 gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-serif-luxury font-bold text-zinc-900 tracking-tight">
                Private Client Portal
            </h1>
            <p class="text-xs text-zinc-500 mt-1">Welcome back, <strong class="text-zinc-800">{{ $user->name }}</strong> ({{ $user->email }})</p>
        </div>

        <div class="flex items-center space-x-3">
            @if($user->isStaff())
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-xs font-bold uppercase tracking-wider transition-colors flex items-center space-x-1.5 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Staff Management Console</span>
                </a>
            @endif

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-4 py-2 border border-zinc-300 hover:border-black text-zinc-700 hover:text-black rounded-lg text-xs font-bold uppercase tracking-wider transition-colors">
                    Sign Out
                </button>
            </form>
        </div>
    </div>

    <!-- Account Navigation Tabs -->
    <div class="flex items-center space-x-2 border-b border-zinc-200 mb-8 overflow-x-auto pb-px text-xs font-bold uppercase tracking-wider">
        <a href="{{ route('account.dashboard') }}" class="px-4 py-3 border-b-2 border-black text-black whitespace-nowrap">Overview</a>
        <a href="{{ route('account.orders') }}" class="px-4 py-3 border-b-2 border-transparent text-zinc-500 hover:text-black hover:border-zinc-300 whitespace-nowrap">Orders & Tracking</a>
        <a href="{{ route('account.addresses') }}" class="px-4 py-3 border-b-2 border-transparent text-zinc-500 hover:text-black hover:border-zinc-300 whitespace-nowrap">Saved Addresses</a>
        <a href="{{ route('account.profile') }}" class="px-4 py-3 border-b-2 border-transparent text-zinc-500 hover:text-black hover:border-zinc-300 whitespace-nowrap">Profile & Password</a>
        <a href="{{ route('account.reviews') }}" class="px-4 py-3 border-b-2 border-transparent text-zinc-500 hover:text-black hover:border-zinc-300 whitespace-nowrap">My Reviews</a>
        <a href="{{ route('wishlist.index') }}" class="px-4 py-3 border-b-2 border-transparent text-zinc-500 hover:text-black hover:border-zinc-300 whitespace-nowrap">Wishlist ({{ $wishlistCount }})</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left: Recent Orders (8 Cols) -->
        <div class="lg:col-span-8 space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-serif-luxury font-bold text-zinc-900">Recent Bespoke Orders</h3>
                <a href="{{ route('account.orders') }}" class="text-xs text-[#A4845B] hover:underline font-bold uppercase tracking-wider">View All</a>
            </div>

            @if($recentOrders->isEmpty())
                <div class="p-8 text-center bg-white rounded-2xl border border-zinc-200">
                    <p class="text-xs text-zinc-500 mb-4">You have not placed any JACARIO orders yet.</p>
                    <a href="{{ route('shop.index') }}" class="inline-flex items-center px-6 py-2.5 bg-zinc-900 text-white rounded-lg text-xs font-bold uppercase tracking-wider">Shop Polos</a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($recentOrders as $ord)
                        <div class="p-6 bg-white rounded-2xl border border-zinc-200 shadow-sm space-y-4 hover:border-zinc-300 transition-colors">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-zinc-100 gap-2">
                                <div>
                                    <span class="text-xs font-mono font-bold text-zinc-900">{{ $ord->order_number }}</span>
                                    <span class="text-zinc-400 text-xs ml-2">{{ $ord->created_at->format('M j, Y') }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-2.5 py-1 rounded text-[10px] font-bold uppercase tracking-wider border {{ $ord->status_badge_color }}">
                                        {{ str_replace('_', ' ', $ord->status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between text-xs">
                                <span class="text-zinc-600">{{ $ord->items->count() }} Polo Silhouette(s)</span>
                                <span class="text-sm font-bold text-zinc-950">₹{{ number_format($ord->total_amount, 2) }}</span>
                            </div>

                            <div class="flex items-center space-x-3 pt-2">
                                <a href="{{ route('account.orders.show', $ord->order_number) }}" class="px-4 py-2 bg-zinc-900 hover:bg-black text-[#DFCAAB] text-xs font-bold uppercase tracking-wider rounded-lg transition-colors">
                                    Track & Details
                                </a>
                                <a href="{{ route('account.orders.invoice', $ord->order_number) }}" target="_blank" class="px-4 py-2 border border-zinc-300 hover:border-black text-zinc-700 text-xs font-bold uppercase tracking-wider rounded-lg transition-colors">
                                    Invoice Receipt
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Right: Profile Overview & Default Address (4 Cols) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Default Address Card -->
            <div class="p-6 bg-white rounded-2xl border border-zinc-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-zinc-100">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-900">Default Delivery Address</h3>
                    <a href="{{ route('account.addresses') }}" class="text-xs text-[#A4845B] hover:underline font-semibold">Manage</a>
                </div>

                @if($defaultAddress)
                    <div class="space-y-1 text-xs text-zinc-600">
                        <p class="font-bold text-zinc-900">{{ $defaultAddress->full_name }}</p>
                        <p class="leading-relaxed">{{ $defaultAddress->formatted_address }}</p>
                        <p class="font-mono text-zinc-500 pt-1">Phone: {{ $defaultAddress->phone }}</p>
                    </div>
                @else
                    <p class="text-xs text-zinc-400">No default address saved yet.</p>
                    <a href="{{ route('account.addresses') }}" class="inline-block text-xs font-bold text-black underline">+ Add an address</a>
                @endif
            </div>

            <!-- Client Concierge Card -->
            <div class="p-6 bg-gradient-to-br from-[#F5EFEB] to-[#E8DDD1] text-zinc-900 rounded-2xl border border-[#DDCFBF] shadow-xs space-y-3">
                <span class="text-[10px] font-bold uppercase tracking-widest text-[#8C6D46]">Dedicated Support</span>
                <h4 class="text-base font-serif-luxury font-bold text-zinc-950">Need Fitting or Sizing Assistance?</h4>
                <p class="text-xs text-zinc-600 leading-relaxed font-light">
                    Our atelier concierge is available to guide you on sizing, fabric care, and order tracking.
                </p>
                <a href="{{ route('contact') }}" class="inline-block text-xs font-bold uppercase tracking-wider text-zinc-950 hover:text-[#8C6D46] hover:underline pt-1">
                    Contact Atelier Concierge →
                </a>
            </div>

        </div>

    </div>

</div>

@endsection
