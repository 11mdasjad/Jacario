@extends('layouts.app')

@section('title', 'My Orders & Deliveries | JACARIO')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <div class="mb-8">
        <h1 class="text-3xl font-serif-luxury font-bold text-zinc-900 tracking-tight">Order History</h1>
        <p class="text-xs text-zinc-500 mt-1">Review past orders, track dispatch status, and download tax invoices</p>
    </div>

    <!-- Account Navigation Tabs -->
    <div class="flex items-center space-x-2 border-b border-zinc-200 mb-8 overflow-x-auto pb-px text-xs font-bold uppercase tracking-wider">
        <a href="{{ route('account.dashboard') }}" class="px-4 py-3 border-b-2 border-transparent text-zinc-500 hover:text-black hover:border-zinc-300 whitespace-nowrap">Overview</a>
        <a href="{{ route('account.orders') }}" class="px-4 py-3 border-b-2 border-black text-black whitespace-nowrap">Orders & Tracking</a>
        <a href="{{ route('account.addresses') }}" class="px-4 py-3 border-b-2 border-transparent text-zinc-500 hover:text-black hover:border-zinc-300 whitespace-nowrap">Saved Addresses</a>
        <a href="{{ route('account.profile') }}" class="px-4 py-3 border-b-2 border-transparent text-zinc-500 hover:text-black hover:border-zinc-300 whitespace-nowrap">Profile & Password</a>
        <a href="{{ route('account.reviews') }}" class="px-4 py-3 border-b-2 border-transparent text-zinc-500 hover:text-black hover:border-zinc-300 whitespace-nowrap">My Reviews</a>
        <a href="{{ route('wishlist.index') }}" class="px-4 py-3 border-b-2 border-transparent text-zinc-500 hover:text-black hover:border-zinc-300 whitespace-nowrap">Wishlist</a>
    </div>

    @if($orders->isEmpty())
        <div class="p-12 text-center bg-white rounded-2xl border border-zinc-200">
            <p class="text-xs text-zinc-500 mb-4">You have not placed any orders yet.</p>
            <a href="{{ route('shop.index') }}" class="inline-flex items-center px-6 py-2.5 bg-zinc-900 text-white rounded-lg text-xs font-bold uppercase tracking-wider">
                Explore Polo T-Shirts
            </a>
        </div>
    @else
        <div class="space-y-6">
            @foreach($orders as $ord)
                <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6 sm:p-8 space-y-6">
                    
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-zinc-100 gap-3">
                        <div class="space-y-1">
                            <span class="text-sm font-bold font-mono text-zinc-900">{{ $ord->order_number }}</span>
                            <p class="text-xs text-zinc-400">Placed on {{ $ord->created_at->format('F j, Y \a\t g:i A') }}</p>
                        </div>

                        <div class="flex items-center space-x-3">
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border {{ $ord->status_badge_color }}">
                                {{ str_replace('_', ' ', $ord->status) }}
                            </span>
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border {{ $ord->payment_status_badge_color }}">
                                {{ $ord->payment_status === 'captured' ? 'Paid' : ucfirst($ord->payment_status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Items List -->
                    <div class="divide-y divide-zinc-100">
                        @foreach($ord->items as $item)
                            <div class="py-3 flex items-center justify-between first:pt-0 last:pb-0">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-14 bg-zinc-50 rounded-lg border border-zinc-100 p-1 flex items-center justify-center flex-shrink-0">
                                        <img src="{{ $item->image_path ? asset(ltrim($item->image_path, '/')) : asset('images/placeholder-polo.svg') }}" alt="{{ $item->product_name }}" class="w-full h-full object-contain">
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-zinc-900">{{ $item->product_name }}</h4>
                                        <p class="text-[11px] text-zinc-500">Size: <strong class="text-zinc-800">{{ $item->size_name }}</strong> • Color: {{ $item->color_name }} • Qty: {{ $item->quantity }}</p>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-zinc-900">₹{{ number_format($item->subtotal, 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Total & Actions -->
                    <div class="pt-4 border-t border-zinc-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <span class="text-xs text-zinc-500">Total Amount:</span>
                            <span class="text-base font-bold text-zinc-950 ml-1">₹{{ number_format($ord->total_amount, 2) }}</span>
                        </div>

                        <div class="flex items-center space-x-3">
                            <a href="{{ route('account.orders.show', $ord->order_number) }}" class="px-5 py-2.5 bg-zinc-900 hover:bg-black text-[#DFCAAB] rounded-lg text-xs font-bold uppercase tracking-wider transition-colors shadow-sm">
                                View Order Timeline
                            </a>
                            <a href="{{ route('account.orders.invoice', $ord->order_number) }}" target="_blank" class="px-5 py-2.5 border border-zinc-300 hover:border-black text-zinc-800 rounded-lg text-xs font-bold uppercase tracking-wider transition-colors">
                                Invoice
                            </a>
                        </div>
                    </div>

                </div>
            @endforeach

            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        </div>
    @endif

</div>

@endsection
