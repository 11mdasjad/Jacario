@extends('layouts.admin')

@section('title', 'Customization Studio Management')
@section('header_title', 'Customization Studio & Bespoke Orders')

@section('content')
<div class="p-6 sm:p-8 space-y-8 max-w-7xl mx-auto">
    
    <!-- Top Action Hub -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold font-serif-luxury text-zinc-950">Customization Overview</h1>
            <p class="text-xs text-zinc-500 mt-1">Manage customizable products, studio upload rules, bulk pricing tiers, and production orders.</p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.customizations.products') }}" class="px-4 py-2.5 rounded-xl bg-white border border-zinc-200 hover:bg-zinc-50 text-xs font-bold text-zinc-800 transition-colors shadow-xs">
                Manage Products ({{ $products->count() }})
            </a>
            <a href="{{ route('admin.customizations.orders') }}" class="px-4 py-2.5 rounded-xl bg-zinc-950 hover:bg-[#8C6D46] text-[#DFCAAB] text-xs font-bold uppercase tracking-wider transition-colors shadow-md">
                View All Custom Orders
            </a>
        </div>
    </div>

    <!-- KPI Metric Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- 1. Total Customized Orders -->
        <div class="bg-white rounded-2xl p-6 border border-zinc-200/90 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-zinc-500">Customized Orders</p>
                <h3 class="text-2xl sm:text-3xl font-bold font-serif-luxury text-zinc-950 mt-1">{{ number_format($totalCustomizedOrders) }}</h3>
                <p class="text-[11px] text-zinc-400 mt-1">Lifetime customer bespoke orders</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-[#A4845B] border border-amber-200/60 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
        </div>

        <!-- 2. Pending Production -->
        <div class="bg-white rounded-2xl p-6 border border-zinc-200/90 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-amber-600">Pending Production</p>
                <h3 class="text-2xl sm:text-3xl font-bold font-serif-luxury text-amber-950 mt-1">{{ number_format($pendingProductionCount) }}</h3>
                <p class="text-[11px] text-zinc-400 mt-1">Awaiting printing or stitching</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-100/60 text-amber-800 border border-amber-300 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- 3. Completed Production -->
        <div class="bg-white rounded-2xl p-6 border border-zinc-200/90 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-emerald-600">Completed Orders</p>
                <h3 class="text-2xl sm:text-3xl font-bold font-serif-luxury text-emerald-950 mt-1">{{ number_format($completedProductionCount) }}</h3>
                <p class="text-[11px] text-zinc-400 mt-1">Dispatched or fulfilled</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- 4. Customization Revenue -->
        <div class="bg-white rounded-2xl p-6 border border-zinc-200/90 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-zinc-500">Custom Revenue</p>
                <h3 class="text-2xl sm:text-3xl font-bold font-serif-luxury text-zinc-950 mt-1">₹{{ number_format($customizationRevenue) }}</h3>
                <p class="text-[11px] text-zinc-400 mt-1">
                    Top: {{ $mostCustomized && $mostCustomized->customizableProduct ? $mostCustomized->customizableProduct->name : 'Polo T-Shirt' }}
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-zinc-900 text-[#DFCAAB] flex items-center justify-center">
                <span class="text-lg font-bold">₹</span>
            </div>
        </div>

    </div>

    <!-- Active Customizable Products Section -->
    <div class="bg-white rounded-3xl border border-zinc-200 shadow-sm p-6 sm:p-8">
        <div class="flex items-center justify-between pb-6 border-b border-zinc-100">
            <div>
                <h2 class="text-base sm:text-lg font-bold font-serif-luxury text-zinc-950">Customizable Garments (Active Scope)</h2>
                <p class="text-xs text-zinc-500">Currently configured for Polo T-Shirts & Crewneck T-Shirts.</p>
            </div>
            <a href="{{ route('customize.index') }}" target="_blank" class="text-xs font-bold text-[#A4845B] hover:underline flex items-center space-x-1">
                <span>View Live Studio</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            @foreach($products as $product)
                <div class="p-5 rounded-2xl border border-zinc-200 bg-zinc-50/50 hover:bg-white hover:border-amber-300 transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[10px] font-extrabold uppercase px-2.5 py-0.5 rounded-full {{ $product->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                {{ $product->is_active ? 'Active in Studio' : 'Disabled' }}
                            </span>
                            <span class="text-xs font-mono text-zinc-400">SKU: {{ $product->sku }}</span>
                        </div>

                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-16 h-20 bg-white rounded-xl border border-zinc-200 p-1 flex items-center justify-center flex-shrink-0">
                                <img src="{{ asset($product->featured_image ?: 'images/placeholder-polo.svg') }}" class="w-full h-full object-contain">
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-zinc-950">{{ $product->name }}</h3>
                                <p class="text-xs text-zinc-500 line-clamp-2 mt-0.5">{{ $product->short_description }}</p>
                                <p class="text-xs font-bold text-zinc-900 mt-1">Base Price: {{ $product->formatted_price }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 pt-4 border-t border-zinc-200/70">
                        <a href="{{ route('admin.customizations.products.edit', $product->id) }}" class="flex-1 py-2 text-center rounded-xl bg-white border border-zinc-300 hover:bg-zinc-100 text-xs font-bold text-zinc-800 transition-colors">
                            Edit Product
                        </a>
                        <a href="{{ route('admin.customizations.variants', $product->id) }}" class="flex-1 py-2 text-center rounded-xl bg-white border border-zinc-300 hover:bg-zinc-100 text-xs font-bold text-zinc-800 transition-colors">
                            Variants & Stock
                        </a>
                        <a href="{{ route('admin.customizations.settings', $product->id) }}" class="flex-1 py-2 text-center rounded-xl bg-zinc-950 text-[#DFCAAB] text-xs font-bold uppercase tracking-wider hover:bg-black transition-colors">
                            Studio Rules
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Custom Orders Table -->
    <div class="bg-white rounded-3xl border border-zinc-200 shadow-sm p-6 sm:p-8">
        <div class="flex items-center justify-between pb-6 border-b border-zinc-100">
            <div>
                <h2 class="text-base sm:text-lg font-bold font-serif-luxury text-zinc-950">Recent Customized Orders</h2>
                <p class="text-xs text-zinc-500">Orders requiring artwork inspection or production printing.</p>
            </div>
            <a href="{{ route('admin.customizations.orders') }}" class="text-xs font-bold text-zinc-700 hover:text-black">View All →</a>
        </div>

        @if($recentCustomOrders->isEmpty())
            <div class="py-12 text-center text-zinc-400">
                <svg class="w-10 h-10 mx-auto mb-2 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <p class="text-xs font-semibold">No customized orders placed yet.</p>
            </div>
        @else
            <div class="overflow-x-auto mt-4">
                <table class="w-full text-xs text-left">
                    <thead class="bg-zinc-50 text-zinc-600 font-bold uppercase tracking-wider border-b border-zinc-200">
                        <tr>
                            <th class="p-3">Order #</th>
                            <th class="p-3">Customer</th>
                            <th class="p-3">Custom Item</th>
                            <th class="p-3">Qty & Amount</th>
                            <th class="p-3">Artwork / Preview</th>
                            <th class="p-3">Production Status</th>
                            <th class="p-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 text-zinc-800">
                        @foreach($recentCustomOrders as $order)
                            @foreach($order->items->where('is_customized', true) as $cItem)
                                <tr class="hover:bg-zinc-50/80 transition-colors">
                                    <td class="p-3 font-bold font-mono text-zinc-950">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="hover:underline">{{ $order->order_number }}</a>
                                    </td>
                                    <td class="p-3">
                                        <div class="font-bold text-zinc-900">{{ $order->customer_name }}</div>
                                        <div class="text-[10px] text-zinc-500">{{ $order->customer_phone }}</div>
                                    </td>
                                    <td class="p-3">
                                        <div class="font-bold">{{ $cItem->product_name }}</div>
                                        <div class="text-[10px] text-zinc-500">{{ $cItem->size_name }} / {{ $cItem->color_name }}</div>
                                    </td>
                                    <td class="p-3">
                                        <div class="font-bold">₹{{ number_format($cItem->subtotal) }}</div>
                                        <div class="text-[10px] text-zinc-500">{{ $cItem->quantity }} units</div>
                                    </td>
                                    <td class="p-3">
                                        <div class="flex items-center space-x-2">
                                            @if($cItem->customization_preview_path)
                                                <img src="{{ asset($cItem->customization_preview_path) }}" class="w-9 h-11 object-contain rounded border border-zinc-200 bg-white">
                                            @endif
                                            @if($cItem->artwork_file_path)
                                                <a href="{{ route('admin.customizations.orders.download-artwork', $cItem->id) }}" class="text-[10px] font-bold text-[#A4845B] hover:underline" title="Download High-Res Artwork">
                                                    Download Art
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="p-3">
                                        <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full {{ $cItem->custom_production_status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-900' }}">
                                            {{ ucfirst(str_replace('_', ' ', $cItem->custom_production_status ?? 'Pending')) }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-right">
                                        <a href="{{ route('admin.customizations.orders.item', $cItem->id) }}" class="px-3 py-1.5 rounded-lg bg-zinc-950 text-white text-[11px] font-bold hover:bg-[#8C6D46] transition-colors">
                                            Inspect Item
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection
