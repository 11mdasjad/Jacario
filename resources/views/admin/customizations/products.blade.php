@extends('layouts.admin')

@section('title', 'Customizable Garments')
@section('header_title', 'Customizable Products Management')

@section('content')
<div class="p-6 sm:p-8 space-y-6 max-w-7xl mx-auto">
    
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold font-serif-luxury text-zinc-950">Customizable Products</h1>
            <p class="text-xs text-zinc-500 mt-1">Configure Polo T-Shirts and T-Shirts enabled for customer studio personalization.</p>
        </div>

        <a href="{{ route('admin.customizations.index') }}" class="px-4 py-2 rounded-xl bg-white border border-zinc-200 text-xs font-bold text-zinc-700 hover:bg-zinc-50">
            ← Back to Overview
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-zinc-200 shadow-sm overflow-hidden">
        <table class="w-full text-xs text-left">
            <thead class="bg-zinc-50 text-zinc-600 font-bold uppercase tracking-wider border-b border-zinc-200">
                <tr>
                    <th class="p-4">Garment</th>
                    <th class="p-4">Type</th>
                    <th class="p-4">SKU</th>
                    <th class="p-4">Base Price</th>
                    <th class="p-4">Effective Price</th>
                    <th class="p-4">Variants</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 text-zinc-800">
                @foreach($products as $product)
                    <tr class="hover:bg-zinc-50/80 transition-colors">
                        <td class="p-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-14 bg-zinc-50 rounded-lg border border-zinc-200 p-1 flex items-center justify-center flex-shrink-0">
                                    <img src="{{ asset($product->featured_image ?: 'images/placeholder-polo.svg') }}" class="w-full h-full object-contain">
                                </div>
                                <div>
                                    <span class="font-bold text-zinc-950 text-sm">{{ $product->name }}</span>
                                    <p class="text-[11px] text-zinc-500 line-clamp-1 max-w-xs">{{ $product->short_description }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $product->type === 'polo_tshirt' ? 'bg-amber-100 text-amber-900' : 'bg-blue-100 text-blue-900' }}">
                                {{ $product->type === 'polo_tshirt' ? 'Polo T-Shirt' : 'Crewneck T-Shirt' }}
                            </span>
                        </td>
                        <td class="p-4 font-mono font-bold text-zinc-600">{{ $product->sku }}</td>
                        <td class="p-4">₹{{ number_format($product->base_price, 2) }}</td>
                        <td class="p-4 font-bold text-zinc-950">₹{{ number_format($product->effective_price, 2) }}</td>
                        <td class="p-4">
                            <span class="font-semibold text-zinc-700">{{ $product->variants->count() }} size/color pairs</span>
                        </td>
                        <td class="p-4">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $product->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                {{ $product->is_active ? 'Active' : 'Disabled' }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('admin.customizations.products.edit', $product->id) }}" class="px-3 py-1.5 rounded-lg border border-zinc-300 text-zinc-700 font-bold hover:bg-zinc-100 text-[11px]">
                                    Edit
                                </a>
                                <a href="{{ route('admin.customizations.variants', $product->id) }}" class="px-3 py-1.5 rounded-lg border border-zinc-300 text-zinc-700 font-bold hover:bg-zinc-100 text-[11px]">
                                    Variants
                                </a>
                                <a href="{{ route('admin.customizations.settings', $product->id) }}" class="px-3 py-1.5 rounded-lg bg-zinc-950 text-[#DFCAAB] font-bold hover:bg-black text-[11px]">
                                    Rules & Fees
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
