@extends('layouts.admin')

@section('title', "Edit {$product->name}")
@section('header_title', "Edit {$product->name}")

@section('content')

<div class="max-w-5xl mx-auto space-y-6" x-data="{
    newImageUrls: [],
    filePreviews: [],
    
    addImageUrl() {
        this.newImageUrls.push('');
    },
    removeImageUrl(index) {
        this.newImageUrls.splice(index, 1);
    },
    handleFileUpload(event) {
        const files = event.target.files;
        this.filePreviews = [];
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();
            reader.onload = (e) => {
                this.filePreviews.push({
                    name: file.name,
                    src: e.target.result,
                    size: (file.size / 1024).toFixed(1) + ' KB'
                });
            };
            reader.readAsDataURL(file);
        }
    }
}">
    
    <div class="flex items-center justify-between pb-4 border-b border-zinc-200">
        <div>
            <h1 class="text-xl font-serif-luxury font-bold text-zinc-950">Edit Product: {{ $product->name }}</h1>
            <p class="text-xs text-zinc-500 mt-0.5">SKU: <span class="font-mono font-bold text-zinc-800">{{ $product->sku }}</span></p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="text-xs font-bold text-zinc-600 hover:text-black uppercase tracking-wider">
            ← Back to Catalog
        </a>
    </div>

    <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- General Info -->
        <div class="p-6 sm:p-8 bg-white rounded-2xl border border-zinc-200 shadow-xs space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#8C6D46] pb-2 border-b border-zinc-100">
                General Information
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Product Name *</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none focus:border-black">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Base SKU Code *</label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 uppercase font-mono focus:outline-none focus:border-black">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Style Category *</label>
                    <select name="category_id" required class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Base Price (₹) *</label>
                    <input type="number" step="0.01" name="base_price" value="{{ old('base_price', $product->base_price) }}" required class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 font-bold focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Promotional Sale Price (₹)</label>
                    <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 font-bold focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Short Description</label>
                <input type="text" name="short_description" value="{{ old('short_description', $product->short_description) }}" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Detailed Editorial Description *</label>
                <textarea name="description" rows="3" required class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none">{{ old('description', $product->description) }}</textarea>
            </div>
        </div>

        <!-- Product Images Management (Existing + Add New + Delete) -->
        <div class="p-6 sm:p-8 bg-white rounded-2xl border border-zinc-200 shadow-xs space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-zinc-100 gap-2">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-[#8C6D46]">
                        Product Photography Gallery ({{ $product->images->count() }} Images)
                    </h3>
                    <p class="text-xs text-zinc-500 mt-0.5">Manage existing imagery, select primary thumbnail, delete or add new photos</p>
                </div>
                <button type="button" @click="addImageUrl()" class="px-4 py-2 bg-zinc-100 hover:bg-zinc-950 hover:text-white text-zinc-900 rounded-xl text-xs font-bold uppercase tracking-wider transition-colors flex items-center space-x-1 self-start sm:self-auto">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Add New Image URL</span>
                </button>
            </div>

            <!-- Existing Images Matrix -->
            @if($product->images->count() > 0)
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-zinc-600 mb-3">Current Active Photos:</p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($product->images as $img)
                            <div class="p-3 bg-zinc-50 rounded-2xl border border-zinc-200 flex items-center space-x-3 relative group">
                                <div class="w-16 h-16 rounded-xl bg-zinc-200 border border-zinc-300 overflow-hidden shrink-0">
                                    <img src="{{ $img->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                </div>

                                <div class="flex-1 min-w-0 space-y-1.5">
                                    <label class="flex items-center space-x-1.5 text-xs text-zinc-700 cursor-pointer">
                                        <input type="radio" name="primary_image_id" value="{{ $img->id }}" {{ $img->is_primary ? 'checked' : '' }} class="text-black focus:ring-black">
                                        <span class="{{ $img->is_primary ? 'font-bold text-zinc-950' : 'text-zinc-500' }}">Primary Cover</span>
                                    </label>

                                    <label class="flex items-center space-x-1.5 text-xs text-rose-600 cursor-pointer">
                                        <input type="checkbox" name="delete_image_ids[]" value="{{ $img->id }}" class="rounded text-rose-600 focus:ring-rose-500">
                                        <span class="font-semibold">Delete Image</span>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- New Image URLs List -->
            <div x-show="newImageUrls.length > 0" class="pt-4 border-t border-zinc-100 space-y-3">
                <p class="text-[11px] font-bold uppercase tracking-wider text-zinc-600">New Image URLs to Add:</p>

                <template x-for="(url, idx) in newImageUrls" :key="idx">
                    <div class="p-3 bg-zinc-50 rounded-2xl border border-zinc-200 flex items-center gap-3">
                        <div class="w-14 h-14 rounded-xl bg-zinc-200 overflow-hidden flex items-center justify-center shrink-0">
                            <template x-if="newImageUrls[idx] && newImageUrls[idx].trim().length > 5">
                                <img :src="newImageUrls[idx]" alt="Preview" class="w-full h-full object-cover" x-on:error="$el.src = 'https://via.placeholder.com/150?text=Invalid+Image'">
                            </template>
                            <template x-if="!newImageUrls[idx] || newImageUrls[idx].trim().length <= 5">
                                <svg class="w-5 h-5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </template>
                        </div>

                        <input type="url" name="image_urls[]" x-model="newImageUrls[idx]" placeholder="Paste photo URL (https://...)" class="flex-1 text-xs bg-white border border-zinc-200 rounded-xl px-3 py-2 text-zinc-900 focus:outline-none focus:border-black">

                        <button type="button" @click="removeImageUrl(idx)" class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl border border-rose-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </template>
            </div>

            <!-- Upload Additional Files -->
            <div class="pt-4 border-t border-zinc-100 space-y-3">
                <p class="text-[11px] font-bold uppercase tracking-wider text-zinc-600">Upload Additional Image Files from Device</p>

                <div class="border-2 border-dashed border-zinc-300 hover:border-black rounded-2xl p-6 text-center bg-zinc-50/60 transition-colors cursor-pointer relative">
                    <input type="file" name="image_files[]" multiple accept="image/*" @change="handleFileUpload($event)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <div class="space-y-1">
                        <svg class="w-7 h-7 mx-auto text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        <p class="text-xs font-bold text-zinc-900">Click to Browse or Drag & Drop Photos</p>
                        <p class="text-[11px] text-zinc-500">PNG, JPG, WEBP, or SVG up to 10MB each</p>
                    </div>
                </div>

                <div x-show="filePreviews.length > 0" class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2">
                    <template x-for="(file, i) in filePreviews" :key="i">
                        <div class="p-2 bg-white rounded-xl border border-zinc-200 shadow-xs">
                            <div class="aspect-square rounded-lg overflow-hidden bg-zinc-100 mb-1.5">
                                <img :src="file.src" :alt="file.name" class="w-full h-full object-cover">
                            </div>
                            <p class="text-[10px] font-bold text-zinc-900 truncate" x-text="file.name"></p>
                        </div>
                    </template>
                </div>
            </div>

        </div>

        <!-- Variant Stock Manager -->
        <div class="p-6 sm:p-8 bg-white rounded-2xl border border-zinc-200 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-zinc-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#8C6D46]">Variant Stock Matrix</h3>
                <span class="text-[11px] text-zinc-500 font-semibold">{{ $product->variants->count() }} active size/color combinations</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($product->variants as $variant)
                    <div class="p-3 bg-zinc-50 rounded-xl border border-zinc-200 flex items-center justify-between space-x-2">
                        <div>
                            <p class="text-xs font-bold text-zinc-900">{{ $variant->size->name }} / {{ $variant->color->name }}</p>
                            <p class="text-[10px] text-zinc-500 font-mono">{{ $variant->sku }}</p>
                        </div>
                        <div class="w-24">
                            <input type="number" name="variant_stock[{{ $variant->id }}]" value="{{ old("variant_stock.{$variant->id}", $variant->stock_quantity) }}" min="0" class="w-full text-xs bg-white border border-zinc-300 rounded-xl py-1.5 px-2.5 text-center text-zinc-900 font-bold focus:outline-none focus:border-black">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Visibility Flags -->
        <div class="p-6 sm:p-8 bg-white rounded-2xl border border-zinc-200 shadow-xs space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#8C6D46] pb-2 border-b border-zinc-100">Storefront Visibility</h3>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs font-semibold text-zinc-800">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="rounded text-black focus:ring-black">
                    <span>Active in Store</span>
                </label>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="rounded text-black focus:ring-black">
                    <span>Featured</span>
                </label>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="is_bestseller" value="1" {{ old('is_bestseller', $product->is_bestseller) ? 'checked' : '' }} class="rounded text-black focus:ring-black">
                    <span>Best Seller</span>
                </label>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="is_new_arrival" value="1" {{ old('is_new_arrival', $product->is_new_arrival) ? 'checked' : '' }} class="rounded text-black focus:ring-black">
                    <span>New Arrival</span>
                </label>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="flex items-center justify-end space-x-4 pt-4">
            <a href="{{ route('admin.products.index') }}" class="px-6 py-3 border border-zinc-300 text-zinc-700 hover:text-black rounded-xl text-xs font-bold uppercase tracking-wider">
                Cancel
            </a>
            <button type="submit" class="px-8 py-3 bg-zinc-950 hover:bg-black text-white rounded-xl text-xs font-bold uppercase tracking-widest transition-all shadow-md hover:shadow-lg flex items-center space-x-2">
                <svg class="w-4 h-4 text-[#C5A880]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Save Product Changes</span>
            </button>
        </div>

    </form>

</div>

@endsection
