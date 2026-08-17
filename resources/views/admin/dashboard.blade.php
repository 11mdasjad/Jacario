@extends('layouts.admin')

@section('title', 'Performance Dashboard')
@section('header_title', 'Executive Performance Overview')

@section('content')

<div class="space-y-8">
    
    <!-- KPI Metric Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Total Revenue -->
        <div class="p-6 rounded-2xl bg-white border border-zinc-200 shadow-xs space-y-2">
            <div class="flex items-center justify-between text-zinc-500 text-xs">
                <span class="font-bold uppercase tracking-wider">Total Sales Revenue</span>
                <span class="w-8 h-8 rounded-xl bg-amber-50 text-[#8C6D46] border border-amber-200 flex items-center justify-center font-bold">₹</span>
            </div>
            <p class="text-2xl font-serif-luxury font-bold text-zinc-950">
                ₹{{ number_format($totalSales, 2) }}
            </p>
            <p class="text-[11px] text-zinc-500">
                Today's Sales: <strong class="text-zinc-900">₹{{ number_format($todaySales, 2) }}</strong>
            </p>
        </div>

        <!-- Orders Metric -->
        <div class="p-6 rounded-2xl bg-white border border-zinc-200 shadow-xs space-y-2">
            <div class="flex items-center justify-between text-zinc-500 text-xs">
                <span class="font-bold uppercase tracking-wider">Total Orders</span>
                <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-700 border border-blue-200 flex items-center justify-center font-bold">#</span>
            </div>
            <p class="text-2xl font-serif-luxury font-bold text-zinc-950">
                {{ $totalOrders }}
            </p>
            <div class="flex items-center space-x-2 text-[11px]">
                <span class="text-amber-700 font-bold">{{ $pendingOrders }} Pending</span>
                <span class="text-zinc-300">•</span>
                <span class="text-emerald-700 font-bold">{{ $deliveredOrders }} Delivered</span>
            </div>
        </div>

        <!-- Active Clients -->
        <div class="p-6 rounded-2xl bg-white border border-zinc-200 shadow-xs space-y-2">
            <div class="flex items-center justify-between text-zinc-500 text-xs">
                <span class="font-bold uppercase tracking-wider">Customer Accounts</span>
                <span class="w-8 h-8 rounded-xl bg-purple-50 text-purple-700 border border-purple-200 flex items-center justify-center font-bold">👤</span>
            </div>
            <p class="text-2xl font-serif-luxury font-bold text-zinc-950">
                {{ $totalCustomers }}
            </p>
            <p class="text-[11px] text-zinc-500">
                Active Client Accounts
            </p>
        </div>

        <!-- Product Catalog -->
        <div class="p-6 rounded-2xl bg-white border border-zinc-200 shadow-xs space-y-2">
            <div class="flex items-center justify-between text-zinc-500 text-xs">
                <span class="font-bold uppercase tracking-wider">Active Products</span>
                <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center justify-center font-bold">👕</span>
            </div>
            <p class="text-2xl font-serif-luxury font-bold text-zinc-950">
                {{ $totalProducts }}
            </p>
            <p class="text-[11px] text-amber-700 font-semibold">
                {{ $lowStockVariants->count() }} variants low on stock
            </p>
        </div>

    </div>

    <!-- 7-Day Revenue Trend Chart & Low Stock Alerts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Revenue Bar Chart (7 Cols) -->
        <div class="lg:col-span-7 p-6 rounded-2xl bg-white border border-zinc-200 shadow-xs space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-zinc-100">
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-zinc-950">7-Day Revenue Velocity</h3>
                    <p class="text-[11px] text-zinc-500 mt-0.5">Daily captured revenue across Razorpay & COD</p>
                </div>
            </div>

            <!-- Visual Bar Chart -->
            <div class="h-56 flex items-end justify-between gap-3 pt-6 px-2">
                @php
                    $maxAmount = max(collect($revenueTrend)->pluck('amount')->max() ?: 1, 1000);
                @endphp
                @foreach($revenueTrend as $trend)
                    @php
                        $heightPercent = max(6, round(($trend['amount'] / $maxAmount) * 100));
                    @endphp
                    <div class="flex-1 flex flex-col items-center gap-2 group">
                        <span class="text-[10px] font-mono font-semibold text-zinc-600 group-hover:text-black transition-colors">₹{{ number_format($trend['amount']) }}</span>
                        <div class="w-full bg-zinc-100 rounded-t-lg overflow-hidden flex items-end h-36 border border-zinc-200">
                            <div class="w-full bg-gradient-to-t from-[#8C6D46] to-[#C5A880] rounded-t transition-all duration-500 group-hover:brightness-110" style="height: {{ $heightPercent }}%;"></div>
                        </div>
                        <span class="text-[10px] font-medium text-zinc-500">{{ $trend['date'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Inventory Alerts Box (5 Cols) -->
        <div class="lg:col-span-5 p-6 rounded-2xl bg-white border border-zinc-200 shadow-xs space-y-4 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-zinc-100">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-zinc-950 flex items-center space-x-2">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                        <span>Inventory Alerts</span>
                    </h3>
                    <a href="{{ route('admin.products.index') }}" class="text-[11px] text-[#8C6D46] hover:underline font-bold">Manage Catalog</a>
                </div>

                <div class="mt-4 space-y-2.5 max-h-52 overflow-y-auto pr-1">
                    @forelse($lowStockVariants as $var)
                        <div class="p-3 bg-zinc-50 rounded-xl border border-zinc-200 flex items-center justify-between text-xs">
                            <div>
                                <p class="font-bold text-zinc-900">{{ $var->product->name }}</p>
                                <p class="text-[11px] text-zinc-500">{{ $var->size->name }} / {{ $var->color->name }} (SKU: {{ $var->sku }})</p>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-900 border border-amber-300">
                                {{ $var->stock_quantity }} left
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-xs font-medium text-zinc-500">All product variant stock levels are currently optimal.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="pt-3 border-t border-zinc-100 text-[11px] text-zinc-400">
                Automated threshold alerts when variant quantities drop below 5 units.
            </div>
        </div>

    </div>

    <!-- Recent Orders Table -->
    <div class="p-6 rounded-2xl bg-white border border-zinc-200 shadow-xs space-y-6">
        <div class="flex items-center justify-between pb-4 border-b border-zinc-100">
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-zinc-950">Recent Orders</h3>
                <p class="text-[11px] text-zinc-500 mt-0.5">Real-time transactions and fulfillment statuses</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold uppercase tracking-wider text-[#8C6D46] hover:underline">
                View All Orders →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead>
                    <tr class="border-b border-zinc-200 text-zinc-500 uppercase tracking-wider text-[10px] bg-zinc-50">
                        <th class="py-3 px-4 rounded-l-lg">Order Number</th>
                        <th class="py-3 px-4">Customer</th>
                        <th class="py-3 px-4">Items</th>
                        <th class="py-3 px-4">Amount</th>
                        <th class="py-3 px-4">Payment</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right rounded-r-lg">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-zinc-50/80 transition-colors">
                            <td class="py-3 px-4 font-mono font-bold text-zinc-950">{{ $order->order_number }}</td>
                            <td class="py-3 px-4">
                                <p class="font-bold text-zinc-900">{{ $order->customer_name }}</p>
                                <p class="text-[11px] text-zinc-500">{{ $order->customer_email }}</p>
                            </td>
                            <td class="py-3 px-4 text-zinc-600">{{ $order->items_count }} piece(s)</td>
                            <td class="py-3 px-4 font-bold text-zinc-950">₹{{ number_format($order->total_amount, 2) }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-zinc-100 text-zinc-700 border border-zinc-300' }}">
                                    {{ $order->payment_status }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $order->status_badge_class }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="px-3 py-1 bg-zinc-100 hover:bg-zinc-950 hover:text-white rounded-lg text-zinc-800 text-[11px] font-bold transition-colors">
                                    Inspect
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-zinc-400">
                                <p class="text-sm font-medium">No customer orders placed yet.</p>
                                <p class="text-xs text-zinc-400 mt-1">New store orders will appear here in real time.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
