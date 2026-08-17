@extends('layouts.app')

@section('title', 'Order Confirmed — Congratulations | JACARIO')

@section('content')

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="bg-white rounded-3xl border border-zinc-200 shadow-xl p-8 sm:p-12 text-center space-y-8">
        
        <!-- Animated Success Badge -->
        <div class="w-20 h-20 mx-auto rounded-full bg-emerald-50 border-2 border-emerald-500 flex items-center justify-center text-emerald-600 shadow-inner">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        </div>

        <div class="space-y-3">
            <span class="text-xs font-bold uppercase tracking-[0.25em] text-[#8C6D46]">Congratulations! Order Confirmed</span>
            <h1 class="text-3xl sm:text-4xl font-serif-luxury font-bold text-zinc-950">
                Thank You, {{ $order->customer_name }}!
            </h1>
            <p class="text-xs sm:text-sm text-zinc-600 max-w-lg mx-auto font-light leading-relaxed">
                Your order <strong class="text-zinc-900 font-mono">{{ $order->order_number }}</strong> has been successfully placed. We have sent a confirmation dispatch receipt to <strong class="text-zinc-900">{{ $order->customer_email }}</strong>.
            </p>
        </div>

        @if($order->payment_method === 'cod')
            <!-- COD Special Note -->
            <div class="p-4 sm:p-5 bg-amber-50/80 rounded-2xl border border-amber-200 text-left flex items-start space-x-3">
                <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-900 flex items-center justify-center font-bold text-sm shrink-0 mt-0.5">
                    ₹
                </div>
                <div>
                    <h4 class="text-xs font-bold text-amber-950 uppercase tracking-wider">Cash on Delivery Confirmed</h4>
                    <p class="text-xs text-amber-900/90 mt-1 font-light leading-relaxed">
                        Please keep the exact cash amount of <strong class="font-bold text-amber-950">₹{{ number_format($order->total_amount, 2) }}</strong> ready at the time of doorstep delivery. Our delivery partner will collect payment upon handover.
                    </p>
                </div>
            </div>
        @endif

        <!-- Order Information Card -->
        <div class="p-6 bg-zinc-50 rounded-2xl border border-zinc-200 text-left space-y-4">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                <div>
                    <span class="text-zinc-400 block text-[10px] uppercase font-bold">Order Number</span>
                    <span class="font-bold text-zinc-950 font-mono">{{ $order->order_number }}</span>
                </div>
                <div>
                    <span class="text-zinc-400 block text-[10px] uppercase font-bold">Order Date</span>
                    <span class="font-semibold text-zinc-800">{{ $order->created_at->format('M j, Y') }}</span>
                </div>
                <div>
                    <span class="text-zinc-400 block text-[10px] uppercase font-bold">Payment Method</span>
                    <span class="font-semibold text-zinc-800 uppercase">{{ $order->payment_method === 'cod' ? 'Cash on Delivery' : $order->payment_method }}</span>
                </div>
                <div>
                    <span class="text-zinc-400 block text-[10px] uppercase font-bold">Total Amount</span>
                    <span class="font-bold text-zinc-950">₹{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>

            <!-- Items Snapshot -->
            <div class="border-t border-zinc-200 pt-4 space-y-3">
                <p class="text-xs font-bold uppercase tracking-wider text-zinc-700">Purchased Items ({{ $order->items->count() }}):</p>
                <div class="space-y-2">
                    @foreach($order->items as $item)
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-zinc-800 font-medium">{{ $item->product_name }} ({{ $item->size_name }} / {{ $item->color_name }}) × {{ $item->quantity }}</span>
                            <span class="font-bold text-zinc-950">₹{{ number_format($item->subtotal, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Action CTAs -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
            @auth
                <a href="{{ route('account.orders') }}" class="w-full sm:w-auto px-7 py-3.5 bg-zinc-950 hover:bg-black text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-all shadow-md hover:shadow-lg flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4 text-[#C5A880]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span>My Orders</span>
                </a>
            @else
                <a href="{{ route('orders.track', ['order_number' => $order->order_number, 'email' => $order->customer_email]) }}" class="w-full sm:w-auto px-7 py-3.5 bg-zinc-950 hover:bg-black text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-all shadow-md hover:shadow-lg flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4 text-[#C5A880]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    <span>Track Order</span>
                </a>
            @endauth

            <a href="{{ route('orders.invoice', $order->order_number) }}" target="_blank" class="w-full sm:w-auto px-7 py-3.5 bg-white border border-zinc-300 hover:border-black text-zinc-900 text-xs font-bold uppercase tracking-widest rounded-xl transition-colors flex items-center justify-center space-x-2 shadow-xs">
                <svg class="w-4 h-4 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>View / Print Invoice</span>
            </a>

            <a href="{{ route('shop.index') }}" class="w-full sm:w-auto px-7 py-3.5 bg-zinc-100 hover:bg-zinc-200 text-zinc-800 text-xs font-bold uppercase tracking-widest rounded-xl transition-colors">
                Continue Shopping
            </a>
        </div>

    </div>
</div>

@endsection
