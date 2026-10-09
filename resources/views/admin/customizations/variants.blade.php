@extends('layouts.admin')

@section('title', 'Manage Variants — ' . $product->name)
@section('header_title', 'Garment Variants & Inventory')

@section('content')
<div class="p-6 sm:p-8 space-y-6 max-w-7xl mx-auto">
    
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold font-serif-luxury text-zinc-950">Variants: {{ $product->name }}</h1>
            <p class="text-xs text-zinc-500 mt-1">Manage size/color availability, stock quantities, and custom price overrides.</p>
        </div>

        <a href="{{ route('admin.customizations.products') }}" class="px-4 py-2 rounded-xl bg-white border border-zinc-200 text-xs font-bold text-zinc-700 hover:bg-zinc-50">
            ← Back to Products
        </a>
    </div>

    <form method="POST" action="{{ route('admin.customizations.variants.update', $product->id) }}" class="bg-white rounded-3xl border border-zinc-200 shadow-sm p-6 sm:p-8 space-y-6">
        @csrf

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-zinc-50 text-zinc-600 font-bold uppercase tracking-wider border-b border-zinc-200">
                    <tr>
                        <th class="p-3">Color</th>
                        <th class="p-3">Size</th>
                        <th class="p-3">Available Stock</th>
                        <th class="p-3">Price Override (₹)</th>
                        <th class="p-3">Enabled</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 text-zinc-800">
                    @foreach($product->variants as $variant)
                        <tr class="hover:bg-zinc-50/80 transition-colors">
                            <td class="p-3">
                                <div class="flex items-center space-x-2">
                                    <span class="w-3.5 h-3.5 rounded-full border border-zinc-300" style="background-color: {{ $variant->color ? $variant->color->hex_code : '#000' }}"></span>
                                    <span class="font-bold">{{ $variant->color ? $variant->color->name : 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="p-3 font-bold">
                                {{ $variant->size ? $variant->size->name : 'N/A' }}
                            </td>
                            <td class="p-3">
                                <input type="number" min="0" max="9999" name="variants[{{ $variant->id }}][stock_quantity]" value="{{ $variant->stock_quantity }}" class="w-24 text-xs px-2.5 py-1.5 rounded-lg border border-zinc-300 focus:outline-none focus:border-black font-mono font-bold">
                            </td>
                            <td class="p-3">
                                <input type="number" step="0.01" name="variants[{{ $variant->id }}][price_override]" value="{{ $variant->price_override }}" placeholder="Default (₹{{ number_format($product->effective_price, 2) }})" class="w-36 text-xs px-2.5 py-1.5 rounded-lg border border-zinc-300 focus:outline-none focus:border-black">
                            </td>
                            <td class="p-3">
                                <input type="checkbox" name="variants[{{ $variant->id }}][is_active]" value="1" {{ $variant->is_active ? 'checked' : '' }} class="rounded border-zinc-300 text-zinc-900 focus:ring-black">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pt-4 border-t border-zinc-200 flex items-center justify-end space-x-3">
            <a href="{{ route('admin.customizations.products') }}" class="px-5 py-2.5 rounded-xl border border-zinc-300 text-xs font-bold text-zinc-700 hover:bg-zinc-50">Cancel</a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-zinc-950 hover:bg-black text-[#DFCAAB] text-xs font-bold uppercase tracking-wider shadow-md">Update All Variants</button>
        </div>
    </form>

</div>
@endsection
