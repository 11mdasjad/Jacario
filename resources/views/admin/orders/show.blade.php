@extends('layouts.admin')

@section('title', "Manage Order {$order->order_number}")
@section('header_title', "Order #{$order->order_number}")

@section('content')

<div class="space-y-6">
    
    <!-- Top Action Row -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-zinc-200 gap-4">
        <div>
            <div class="flex items-center space-x-3">
                <h1 class="text-xl font-serif-luxury font-bold text-zinc-950">Order #{{ $order->order_number }}</h1>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border {{ $order->status_badge_color }}">
                    {{ str_replace('_', ' ', $order->status) }}
                </span>
            </div>
            <p class="text-xs text-zinc-500 mt-1">Placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('orders.invoice', $order->order_number) }}" target="_blank" class="px-4 py-2 border border-zinc-300 hover:border-black text-zinc-800 rounded-xl text-xs font-bold uppercase tracking-wider transition-colors shadow-xs">
                Print Tax Invoice
            </a>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-zinc-600 hover:text-black uppercase tracking-wider">
                ← All Orders
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left: Order Items & Timeline (7 Cols) -->
        <div class="lg:col-span-7 space-y-6">
            
            <!-- Items Table -->
            <div class="p-6 bg-white rounded-2xl border border-zinc-200 shadow-xs space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#8C6D46]">Items Ordered ({{ $order->items->count() }})</h3>

                <div class="divide-y divide-zinc-100">
                    @foreach($order->items as $item)
                        <div class="py-4 flex items-center justify-between first:pt-0 last:pb-0">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-14 bg-zinc-100 rounded-xl overflow-hidden border border-zinc-200 flex items-center justify-center shrink-0">
                                    <img src="{{ $item->image_path ?: 'https://images.unsplash.com/photo-1586363104862-3a5e2ab60d99?w=400&auto=format&fit=crop&q=80' }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-zinc-950">{{ $item->product_name }}</h4>
                                    <p class="text-[11px] text-zinc-500">Size: <strong class="text-zinc-800">{{ $item->size_name }}</strong> • Color: {{ $item->color_name }}</p>
                                    <p class="text-[10px] text-zinc-400 font-mono">SKU: {{ $item->sku }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-bold text-zinc-950">₹{{ number_format($item->subtotal, 2) }}</span>
                                <p class="text-[10px] text-zinc-400">{{ $item->quantity }} × ₹{{ number_format($item->unit_price, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Financial Ledger -->
            <div class="p-6 bg-white rounded-2xl border border-zinc-200 shadow-xs space-y-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#8C6D46]">Financial Ledger</h3>

                <div class="space-y-2 text-xs text-zinc-600">
                    <div class="flex justify-between">
                        <span>Items Subtotal:</span>
                        <span class="text-zinc-900 font-semibold">₹{{ number_format($order->subtotal, 2) }}</span>
                    </div>

                    @if($order->discount_amount > 0)
                        <div class="flex justify-between text-emerald-700">
                            <span>Promo Discount ({{ $order->coupon_code }}):</span>
                            <span class="font-bold">- ₹{{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between">
                        <span>Express Delivery:</span>
                        <span class="text-zinc-900 font-semibold">{{ $order->shipping_amount == 0 ? 'Complimentary' : '₹' . number_format($order->shipping_amount, 2) }}</span>
                    </div>

                    <div class="border-t border-zinc-100 pt-2 flex justify-between text-base font-bold text-zinc-950">
                        <span>Grand Total:</span>
                        <span class="text-zinc-950">₹{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Customer Notes -->
            @if($order->notes)
                <div class="p-4 bg-zinc-50 rounded-xl border border-zinc-200 text-xs">
                    <span class="font-bold text-zinc-800 block mb-1">Customer Delivery Instructions:</span>
                    <p class="text-zinc-600">{{ $order->notes }}</p>
                </div>
            @endif

        </div>

        <!-- Right: Status Transition Controls & Delivery Address (5 Cols) -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- Transition Form -->
            <div class="p-6 bg-white rounded-2xl border border-zinc-200 shadow-xs space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#8C6D46]">Fulfillment Status Controller</h3>

                <form method="POST" action="{{ route('admin.orders.status', $order->id) }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 mb-1">Update Status</label>
                        <select name="status" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none focus:border-black">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending Confirmation</option>
                            <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed for Preparation</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Quality Checked & Packed</option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Dispatched / Shipped</option>
                            <option value="out_for_delivery" {{ $order->status === 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered to Client</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled & Stock Restored</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 mb-1">Courier Partner</label>
                        <input type="text" name="courier_name" value="{{ old('courier_name', $order->courier_name) }}" placeholder="e.g. BlueDart Express, DHL, Delhivery" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none focus:border-black">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 mb-1">AWB / Courier Tracking Number</label>
                        <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" placeholder="e.g. BD987654321IN" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 font-mono uppercase focus:outline-none focus:border-black">
                    </div>

                    <button type="submit" class="w-full py-3 bg-zinc-950 hover:bg-black text-white rounded-xl text-xs font-bold uppercase tracking-widest transition-colors shadow-xs">
                        Save Order State
                    </button>
                </form>
            </div>

            <!-- Client & Delivery Card -->
            <div class="p-6 bg-white rounded-2xl border border-zinc-200 shadow-xs space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#8C6D46]">Delivery Destination</h3>

                <div class="space-y-2 text-xs">
                    <p class="font-bold text-zinc-950 text-sm">{{ $order->customer_name }}</p>
                    <p class="text-zinc-600 font-medium">{{ $order->customer_email }}</p>
                    <p class="text-zinc-600 font-mono">{{ $order->customer_phone }}</p>

                    <div class="border-t border-zinc-100 pt-3 text-zinc-700 leading-relaxed font-light">
                        @if(is_array($order->shipping_address))
                            <p>{{ $order->shipping_address['address_line_1'] ?? '' }} {{ $order->shipping_address['address_line_2'] ?? '' }}</p>
                            <p>{{ $order->shipping_address['city'] ?? '' }}{{ !empty($order->shipping_address['state']) ? ', ' . $order->shipping_address['state'] : '' }} {{ !empty($order->shipping_address['postal_code']) ? '- ' . $order->shipping_address['postal_code'] : '' }}</p>
                            <p class="font-semibold text-zinc-900 mt-1 uppercase">{{ $order->shipping_address['country'] ?? 'India' }}</p>
                        @else
                            <p>{{ $order->formatted_shipping_address }}</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection
