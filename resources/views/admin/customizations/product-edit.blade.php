@extends('layouts.admin')

@section('title', 'Edit ' . $product->name)
@section('header_title', 'Edit Customizable Garment')

@section('content')
<div class="p-6 sm:p-8 space-y-6 max-w-4xl mx-auto">
    
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold font-serif-luxury text-zinc-950">Edit {{ $product->name }}</h1>
            <p class="text-xs text-zinc-500 mt-1">Update basic info, pricing, and active studio status.</p>
        </div>

        <a href="{{ route('admin.customizations.products') }}" class="px-4 py-2 rounded-xl bg-white border border-zinc-200 text-xs font-bold text-zinc-700 hover:bg-zinc-50">
            ← Back to Products
        </a>
    </div>

    <form method="POST" action="{{ route('admin.customizations.products.update', $product->id) }}" class="bg-white rounded-3xl border border-zinc-200 shadow-sm p-6 sm:p-8 space-y-6">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-zinc-900 mb-1">Product Name *</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-zinc-300 focus:outline-none focus:border-black">
                @error('name') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-zinc-900 mb-1">SKU *</label>
                <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-zinc-300 focus:outline-none focus:border-black font-mono">
                @error('sku') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-zinc-900 mb-1">Garment Type</label>
                <input type="text" disabled value="{{ $product->type === 'polo_tshirt' ? 'Polo T-Shirt' : 'Crewneck T-Shirt' }}" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-zinc-200 bg-zinc-50 text-zinc-500 font-bold">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-zinc-900 mb-1">Base Price (₹) *</label>
                <input type="number" step="0.01" name="base_price" value="{{ old('base_price', $product->base_price) }}" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-zinc-300 focus:outline-none focus:border-black">
                @error('base_price') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-zinc-900 mb-1">Discount Price (₹)</label>
                <input type="number" step="0.01" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}" placeholder="Optional sale price" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-zinc-300 focus:outline-none focus:border-black">
                @error('discount_price') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-zinc-900 mb-1">Short Description</label>
                <textarea name="short_description" rows="2" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-zinc-300 focus:outline-none focus:border-black">{{ old('short_description', $product->short_description) }}</textarea>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-zinc-900 mb-1">Detailed Description</label>
                <textarea name="description" rows="4" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-zinc-300 focus:outline-none focus:border-black">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="sm:col-span-2 pt-2">
                <label class="flex items-center space-x-2 text-xs font-bold text-zinc-800 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="rounded border-zinc-300 text-zinc-900 focus:ring-black">
                    <span>Enable this garment for customer personalization in Jacario Studio</span>
                </label>
            </div>
        </div>

        <div class="pt-4 border-t border-zinc-200 flex items-center justify-end space-x-3">
            <a href="{{ route('admin.customizations.products') }}" class="px-5 py-2.5 rounded-xl border border-zinc-300 text-xs font-bold text-zinc-700 hover:bg-zinc-50">Cancel</a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-zinc-950 hover:bg-black text-[#DFCAAB] text-xs font-bold uppercase tracking-wider shadow-md">Save Changes</button>
        </div>
    </form>

</div>
@endsection
