@extends('layouts.app')

@section('title', "Order #{$order->order_number} Details & Tracking | JACARIO")

@section('content')

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ cancelModal: false }">
    
    <!-- Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-zinc-200 gap-4 mb-8">
        <div>
            <div class="flex items-center space-x-3">
                <h1 class="text-2xl sm:text-3xl font-serif-luxury font-bold text-zinc-900 tracking-tight">
                    Order #{{ $order->order_number }}
                </h1>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border {{ $order->status_badge_color }}">
                    {{ str_replace('_', ' ', $order->status) }}
                </span>
            </div>
            <p class="text-xs text-zinc-500 mt-1">Placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
        </div>

        <div class="flex items-center space-x-3">
            @if($order->can_be_cancelled)
                <button type="button" @click="cancelModal = true" class="px-4 py-2 border border-rose-300 text-rose-700 hover:bg-rose-50 rounded-lg text-xs font-bold uppercase tracking-wider transition-colors">
                    Cancel Order
                </button>
            @endif

            <a href="{{ route('account.orders.invoice', $order->order_number) }}" target="_blank" class="px-4 py-2 bg-zinc-900 hover:bg-black text-[#DFCAAB] rounded-lg text-xs font-bold uppercase tracking-wider transition-colors shadow-sm">
                Print Tax Invoice
            </a>
        </div>
    </div>

    <!-- Live Fulfillment Progress Timeline -->
    <div class="p-8 bg-white rounded-2xl border border-zinc-200 shadow-sm mb-8 space-y-6">
        <h3 class="text-sm font-bold uppercase tracking-wider text-zinc-900">
            Fulfillment & Delivery Progress
        </h3>

        @if($order->status === 'cancelled')
            <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-800">
                <p class="font-bold">This order has been cancelled.</p>
                <p class="mt-0.5">Reason: {{ $order->cancelled_reason ?? 'Customer request' }}</p>
            </div>
        @else
            <div class="relative">
                <div class="grid grid-cols-2 sm:grid-cols-6 gap-4">
                    @foreach($order->timeline_steps as $step)
                        <div class="flex flex-col items-center text-center space-y-2 relative z-10">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold {{ $step['completed'] ? 'bg-zinc-950 text-[#DFCAAB] ring-4 ring-zinc-100 shadow-md' : 'bg-zinc-100 text-zinc-400 border border-zinc-200' }}">
                                @if($step['completed'])
                                    ✓
                                @else
                                    •
                                @endif
                            </div>
                            <span class="text-xs font-bold {{ $step['completed'] ? 'text-zinc-900' : 'text-zinc-400' }}">{{ $step['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            @if($order->courier_name && $order->tracking_number)
                <div class="p-4 bg-zinc-50 rounded-xl border border-zinc-200 flex flex-col sm:flex-row sm:items-center justify-between text-xs gap-2">
                    <div>
                        <span class="text-zinc-500">Express Courier Partner:</span>
                        <strong class="text-zinc-900 ml-1">{{ $order->courier_name }}</strong>
                    </div>
                    <div>
                        <span class="text-zinc-500">Live Tracking AWB #:</span>
                        <strong class="text-zinc-900 font-mono ml-1">{{ $order->tracking_number }}</strong>
                    </div>
                </div>
            @endif
        @endif
    </div>

    <!-- Order Items & Shipping Breakdown Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left: Items (7 Cols) -->
        <div class="lg:col-span-7 space-y-6">
            <div class="p-6 bg-white rounded-2xl border border-zinc-200 shadow-sm space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-zinc-900 pb-3 border-b border-zinc-100">
                    Polo T-Shirts in this Order
                </h3>

                <div class="divide-y divide-zinc-100">
                    @foreach($order->items as $item)
                        <div class="py-4 flex items-center justify-between first:pt-0 last:pb-0">
                            <div class="flex items-center space-x-3">
                                <div class="w-14 h-16 bg-zinc-50 rounded-lg border border-zinc-100 p-1 flex items-center justify-center flex-shrink-0">
                                    <img src="{{ $item->image_path ? asset(ltrim($item->image_path, '/')) : asset('images/placeholder-polo.svg') }}" alt="{{ $item->product_name }}" class="w-full h-full object-contain">
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-zinc-900">{{ $item->product_name }}</h4>
                                    <p class="text-[11px] text-zinc-500">Size: <strong class="text-zinc-800">{{ $item->size_name }}</strong> • Color: {{ $item->color_name }}</p>
                                    <p class="text-[11px] text-zinc-400 font-mono">SKU: {{ $item->sku }}</p>
                                    <p class="text-[11px] text-zinc-600">Qty: {{ $item->quantity }} × ₹{{ number_format($item->unit_price, 2) }}</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-zinc-900">₹{{ number_format($item->subtotal, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right: Address & Financials (5 Cols) -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- Delivery Address Card -->
            <div class="p-6 bg-white rounded-2xl border border-zinc-200 shadow-sm space-y-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-900 pb-2 border-b border-zinc-100">
                    Shipping & Delivery Address
                </h3>
                <div class="text-xs text-zinc-600 space-y-1">
                    <p class="font-bold text-zinc-900">{{ $order->customer_name }}</p>
                    <p>{{ $order->shipping_address['address_line_1'] ?? '' }}</p>
                    @if(!empty($order->shipping_address['address_line_2']))
                        <p>{{ $order->shipping_address['address_line_2'] }}</p>
                    @endif
                    <p>{{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} - {{ $order->shipping_address['postal_code'] ?? '' }}</p>
                    <p class="text-zinc-500 font-mono pt-1">Phone: {{ $order->customer_phone }}</p>
                </div>
            </div>

            <!-- Financial Summary -->
            <div class="p-6 bg-white rounded-2xl border border-zinc-200 shadow-sm space-y-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-900 pb-2 border-b border-zinc-100">
                    Payment Summary
                </h3>

                <div class="space-y-2 text-xs text-zinc-600">
                    <div class="flex justify-between">
                        <span>Items Subtotal</span>
                        <span class="font-semibold text-zinc-900">₹{{ number_format($order->subtotal, 2) }}</span>
                    </div>

                    @if($order->discount_amount > 0)
                        <div class="flex justify-between text-emerald-700">
                            <span>Promo Discount ({{ $order->coupon_code }})</span>
                            <span class="font-bold">- ₹{{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between">
                        <span>Express Delivery</span>
                        <span class="font-semibold text-zinc-900">{{ $order->shipping_amount == 0 ? 'Complimentary' : '₹' . number_format($order->shipping_amount, 2) }}</span>
                    </div>

                    <div class="border-t border-zinc-200 pt-2 flex justify-between text-sm font-bold text-zinc-950">
                        <span>Grand Total</span>
                        <span>₹{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>

                <div class="pt-2 text-xs text-zinc-500 border-t border-zinc-100">
                    <span class="block">Payment Mode: <strong class="text-zinc-800 uppercase">{{ $order->payment_method }}</strong></span>
                    <span class="block mt-0.5">Payment Status: <strong class="text-zinc-800 uppercase">{{ $order->payment_status }}</strong></span>
                </div>
            </div>

        </div>

    </div>

    <!-- Cancellation Modal -->
    <div x-show="cancelModal" 
         x-transition
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="cancelModal = false"></div>

        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative max-w-md w-full bg-white rounded-2xl shadow-2xl p-6 border border-zinc-200" @click.stop>
                <h3 class="text-base font-serif-luxury font-bold text-zinc-900 mb-2">Cancel Order #{{ $order->order_number }}?</h3>
                <p class="text-xs text-zinc-500 mb-4">
                    Items will be returned to atelier inventory immediately. Are you sure you wish to cancel?
                </p>

                <form method="POST" action="{{ route('account.orders.cancel', $order->order_number) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 mb-1">Reason for cancellation:</label>
                        <select name="reason" class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-2.5">
                            <option value="Changed my mind">Changed my mind</option>
                            <option value="Ordered incorrect size or color">Ordered incorrect size or color</option>
                            <option value="Found alternative piece">Found alternative piece</option>
                            <option value="Delivery time delay">Delivery time delay</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="flex space-x-3 pt-2">
                        <button type="button" @click="cancelModal = false" class="w-1/2 py-2.5 border border-zinc-300 text-zinc-700 rounded-lg text-xs font-bold uppercase">Keep Order</button>
                        <button type="submit" class="w-1/2 py-2.5 bg-rose-600 text-white rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-rose-700">Confirm Cancellation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection
