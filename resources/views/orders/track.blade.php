@extends('layouts.app')

@section('title', 'Track Your Order | JACARIO Concierge')

@section('content')

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="bg-white rounded-2xl border border-zinc-200 shadow-xl p-8 sm:p-12 space-y-8">
        
        <div class="text-center space-y-2">
            <span class="text-xs font-bold uppercase tracking-[0.25em] text-[#A4845B]">Real-Time Logistics</span>
            <h1 class="text-3xl font-serif-luxury font-bold text-zinc-900">Track Your Order</h1>
            <p class="text-xs sm:text-sm text-zinc-500 max-w-md mx-auto">
                Enter your JACARIO order number and the email address used during checkout to view live parcel updates.
            </p>
        </div>

        <form method="GET" action="{{ route('orders.track') }}" class="space-y-4 max-w-md mx-auto">
            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Order Number *</label>
                <input type="text" name="order_number" value="{{ request('order_number') }}" required placeholder="e.g. JAC-2026-XXXXXX" class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-3 uppercase font-mono focus:outline-none focus:border-black">
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Billing / Delivery Email Address *</label>
                <input type="email" name="email" value="{{ request('email') }}" required placeholder="name@example.com" class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-3 focus:outline-none focus:border-black">
            </div>

            <button type="submit" class="w-full py-3.5 bg-[#0B0D10] hover:bg-black text-[#DFCAAB] text-xs font-bold uppercase tracking-widest rounded-lg transition-colors shadow-md">
                Track Order
            </button>
        </form>

        @if($order)
            <div class="mt-8 pt-8 border-t border-zinc-200 space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-zinc-100">
                    <div>
                        <span class="text-xs text-zinc-400">Order Reference:</span>
                        <h3 class="text-base font-bold font-mono text-zinc-900">{{ $order->order_number }}</h3>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border {{ $order->status_badge_color }}">
                        {{ str_replace('_', ' ', $order->status) }}
                    </span>
                </div>

                <!-- Timeline -->
                <div class="grid grid-cols-2 sm:grid-cols-6 gap-3 pt-2">
                    @foreach($order->timeline_steps as $step)
                        <div class="flex flex-col items-center text-center space-y-2">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold {{ $step['completed'] ? 'bg-zinc-950 text-[#DFCAAB]' : 'bg-zinc-100 text-zinc-400 border border-zinc-200' }}">
                                {{ $step['completed'] ? '✓' : '•' }}
                            </div>
                            <span class="text-[10px] font-bold {{ $step['completed'] ? 'text-zinc-900' : 'text-zinc-400' }}">{{ $step['label'] }}</span>
                        </div>
                    @endforeach
                </div>

                @if($order->courier_name && $order->tracking_number)
                    <div class="p-4 bg-zinc-50 rounded-xl border border-zinc-200 flex flex-col sm:flex-row justify-between text-xs gap-2">
                        <span>Courier: <strong class="text-zinc-900">{{ $order->courier_name }}</strong></span>
                        <span>Tracking AWB #: <strong class="text-zinc-900 font-mono">{{ $order->tracking_number }}</strong></span>
                    </div>
                @endif
            </div>
        @endif

    </div>
</div>

@endsection
