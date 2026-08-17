@extends('layouts.app')

@section('title', 'Payment Failed | JACARIO')

@section('content')

<div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="bg-white rounded-2xl border border-zinc-200 shadow-xl p-8 text-center space-y-6">
        
        <div class="w-16 h-16 mx-auto rounded-full bg-rose-50 border border-rose-200 flex items-center justify-center text-rose-600">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </div>

        <div class="space-y-2">
            <h1 class="text-2xl font-serif-luxury font-bold text-zinc-900">Payment Could Not Be Completed</h1>
            <p class="text-xs text-zinc-500 max-w-sm mx-auto">
                {{ request('reason') ?? 'The payment gateway could not authenticate the transaction or was cancelled. No charges were made.' }}
            </p>
        </div>

        @if($order)
            <div class="p-4 bg-zinc-50 rounded-xl border border-zinc-200 text-xs text-left">
                <p>Order Reference: <strong class="font-mono text-zinc-900">{{ $order->order_number }}</strong></p>
                <p class="text-zinc-500 mt-0.5">Amount: ₹{{ number_format($order->total_amount, 2) }}</p>
            </div>
        @endif

        <div class="flex flex-col sm:flex-row gap-3 pt-2">
            <a href="{{ route('checkout.index') }}" class="w-full sm:w-1/2 py-3 bg-zinc-900 text-white text-xs font-bold uppercase tracking-wider rounded-lg hover:bg-black transition-colors">
                Retry Checkout
            </a>
            <a href="{{ route('cart.index') }}" class="w-full sm:w-1/2 py-3 border border-zinc-300 text-zinc-800 text-xs font-bold uppercase tracking-wider rounded-lg hover:border-black transition-colors">
                Return to Bag
            </a>
        </div>

    </div>
</div>

@endsection
