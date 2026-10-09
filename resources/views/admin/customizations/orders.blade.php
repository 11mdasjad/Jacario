@extends('layouts.admin')

@section('title', 'Bespoke Customized Orders')
@section('header_title', 'Customized Order Production Queue')

@section('content')
<div class="p-6 sm:p-8 space-y-6 max-w-7xl mx-auto">
    
    <!-- Top Filter Bar -->
    <div class="bg-white rounded-3xl border border-zinc-200 shadow-sm p-6">
        <form method="GET" action="{{ route('admin.customizations.orders') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-[10px] font-bold text-zinc-500 uppercase mb-1">Search Customer / Order #</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Order #, name, phone..." class="w-full text-xs px-3.5 py-2 rounded-xl border border-zinc-300 focus:outline-none focus:border-black">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-zinc-500 uppercase mb-1">Production Status</label>
                <select name="status" class="w-full text-xs px-3.5 py-2 rounded-xl border border-zinc-300 focus:outline-none focus:border-black bg-white">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="artwork_approved" {{ request('status') === 'artwork_approved' ? 'selected' : '' }}>Artwork Approved</option>
                    <option value="in_printing" {{ request('status') === 'in_printing' ? 'selected' : '' }}>In Printing</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-zinc-500 uppercase mb-1">Garment Type</label>
                <select name="product_id" class="w-full text-xs px-3.5 py-2 rounded-xl border border-zinc-300 focus:outline-none focus:border-black bg-white">
                    <option value="">All Garments</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" {{ request('product_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center space-x-2">
                <button type="submit" class="flex-1 py-2 rounded-xl bg-zinc-950 text-[#DFCAAB] text-xs font-bold uppercase tracking-wider hover:bg-black transition-colors">
                    Filter Queue
                </button>
                <a href="{{ route('admin.customizations.orders') }}" class="px-3.5 py-2 rounded-xl border border-zinc-300 text-xs font-bold text-zinc-600 hover:bg-zinc-100">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-3xl border border-zinc-200 shadow-sm overflow-hidden">
        @if($items->isEmpty())
            <div class="py-16 text-center text-zinc-400">
                <svg class="w-12 h-12 mx-auto mb-2 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <p class="text-sm font-bold text-zinc-800">No customized orders matching filters.</p>
                <p class="text-xs text-zinc-500 mt-1">Orders with custom logos or text will appear here.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-zinc-50 text-zinc-600 font-bold uppercase tracking-wider border-b border-zinc-200">
                        <tr>
                            <th class="p-4">Item & Preview</th>
                            <th class="p-4">Order #</th>
                            <th class="p-4">Customer Details</th>
                            <th class="p-4">Garment Specs</th>
                            <th class="p-4">Qty & Price</th>
                            <th class="p-4">Production Stage</th>
                            <th class="p-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 text-zinc-800">
                        @foreach($items as $item)
                            <tr class="hover:bg-zinc-50/80 transition-colors">
                                <td class="p-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-14 h-16 bg-zinc-50 rounded-xl border border-zinc-200 p-1 flex items-center justify-center flex-shrink-0">
                                            @if($item->customization_preview_path)
                                                <img src="{{ asset($item->customization_preview_path) }}" class="w-full h-full object-contain">
                                            @else
                                                <img src="{{ asset('images/placeholder-polo.svg') }}" class="w-full h-full object-contain">
                                            @endif
                                        </div>
                                        <div>
                                            <span class="font-bold text-zinc-950">{{ $item->product_name }}</span>
                                            <p class="text-[10px] text-zinc-400 font-mono">Item #{{ $item->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 font-mono font-bold text-zinc-950">
                                    @if($item->order)
                                        <a href="{{ route('admin.orders.show', $item->order->id) }}" class="hover:underline text-[#A4845B]">
                                            {{ $item->order->order_number }}
                                        </a>
                                        <p class="text-[10px] text-zinc-400 font-sans font-normal">{{ $item->order->created_at->format('M d, Y') }}</p>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="p-4">
                                    @if($item->order)
                                        <div class="font-bold text-zinc-900">{{ $item->order->customer_name }}</div>
                                        <div class="text-[11px] text-zinc-500 font-mono">{{ $item->order->customer_phone }}</div>
                                        <div class="text-[10px] text-zinc-400 truncate max-w-[150px]">{{ $item->order->customer_email }}</div>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center space-x-1.5">
                                        <span class="w-3 h-3 rounded-full border border-zinc-300" style="background-color: {{ $item->color_hex }}"></span>
                                        <span class="font-bold">{{ $item->color_name }}</span>
                                    </div>
                                    <div class="text-[11px] text-zinc-500 mt-0.5">Size: <strong class="text-zinc-800">{{ $item->size_name }}</strong></div>
                                </td>
                                <td class="p-4">
                                    <div class="font-bold text-zinc-950">₹{{ number_format($item->subtotal) }}</div>
                                    <div class="text-[10px] text-zinc-500">{{ $item->quantity }} units (₹{{ number_format($item->unit_price) }}/unit)</div>
                                </td>
                                <td class="p-4">
                                    <span class="text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-full {{ $item->custom_production_status === 'completed' ? 'bg-emerald-100 text-emerald-800' : ($item->custom_production_status === 'in_printing' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-900') }}">
                                        {{ ucfirst(str_replace('_', ' ', $item->custom_production_status ?? 'Pending')) }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        @if($item->artwork_file_path)
                                            <a href="{{ route('admin.customizations.orders.download-artwork', $item->id) }}" class="p-2 rounded-lg bg-amber-50 text-[#8C6D46] hover:bg-amber-100 border border-amber-200" title="Download High-Res Artwork">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.customizations.orders.item', $item->id) }}" class="px-3.5 py-1.5 rounded-lg bg-zinc-950 text-white font-bold text-[11px] hover:bg-[#8C6D46] transition-colors">
                                            Inspect Item
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-zinc-200">
                {{ $items->withQueryString()->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
