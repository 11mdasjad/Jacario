@extends('layouts.admin')

@section('title', 'Add New Product')
@section('header_title', 'Create New Product')

@section('content')

<div class="max-w-5xl mx-auto space-y-6" x-data="{
    imageUrls: [''],
    primaryIndex: 0,
    filePreviews: [],
    
    addImageUrl() {
        this.imageUrls.push('');
    },
    removeImageUrl(index) {
        if (this.imageUrls.length > 1) {
            this.imageUrls.splice(index, 1);
            if (this.primaryIndex >= this.imageUrls.length) {
                this.primaryIndex = 0;
            }
        } else {
            this.imageUrls[0] = '';
        }
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
            <h1 class="text-xl font-serif-luxury font-bold text-zinc-950">Create Bespoke Silhouette</h1>
            <p class="text-xs text-zinc-500 mt-0.5">Define specifications, multi-angle imagery, variant stock matrix, and SEO</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="text-xs font-bold text-zinc-600 hover:text-black uppercase tracking-wider">
            ← Back to Catalog
        </a>
    </div>

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- General Information Card -->
        <div class="p-6 sm:p-8 bg-white rounded-2xl border border-zinc-200 shadow-xs space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#8C6D46] pb-2 border-b border-zinc-100">
                General Product Details
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Product Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. JACARIO Classic Piqué Polo - Obsidian" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none focus:border-black">
                    @error('name') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Base SKU Code *</label>
                    <input type="text" name="sku" value="{{ old('sku', 'JAC-' . strtoupper(Str::random(6))) }}" required placeholder="e.g. JAC-POLO-001" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 uppercase font-mono focus:outline-none focus:border-black">
                    @error('sku') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Style Category *</label>
                    <select name="category_id" required class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none focus:border-black">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Base Price (₹) *</label>
                    <input type="number" step="0.01" name="base_price" value="{{ old('base_price', '2499') }}" required placeholder="2499.00" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 font-bold focus:outline-none focus:border-black">
                    @error('base_price') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Promotional Sale Price (₹) (Optional)</label>
                    <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price') }}" placeholder="1999.00" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 font-bold focus:outline-none focus:border-black">
                    @error('sale_price') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Short Elevator Description</label>
                <input type="text" name="short_description" value="{{ old('short_description') }}" placeholder="Double-twisted American Supima® cotton with mother-of-pearl buttons." class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none focus:border-black">
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Editorial Craftsmanship Story *</label>
                <textarea name="description" rows="3" required placeholder="Detailed notes about fabric weave, collar stiffness, mother-of-pearl hardware..." class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none focus:border-black">{{ old('description', 'Meticulously crafted from extra-long staple American Supima® cotton with internal anti-curl collar interlining and hand-finished Australian mother-of-pearl buttons.') }}</textarea>
            </div>
        </div>

        <!-- Multiple Product Images Section (Add 3–4 Images with Live Preview & Delete) -->
        <div class="p-6 sm:p-8 bg-white rounded-2xl border border-zinc-200 shadow-xs space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-zinc-100 gap-2">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-[#8C6D46]">
                        Product Photography (Add 3–4 Images)
                    </h3>
                    <p class="text-xs text-zinc-500 mt-0.5">Upload image files or paste direct image URLs (Front, Side, Back, Model Look)</p>
                </div>
                <button type="button" @click="addImageUrl()" class="px-4 py-2 bg-zinc-100 hover:bg-zinc-950 hover:text-white text-zinc-900 rounded-xl text-xs font-bold uppercase tracking-wider transition-colors flex items-center space-x-1 self-start sm:self-auto">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Add Another Image URL</span>
                </button>
            </div>

            <!-- Image URL Rows with Live Preview and Delete -->
            <div class="space-y-3">
                <p class="text-[11px] font-bold uppercase tracking-wider text-zinc-600">Option 1: Paste Image Web URLs (Fast & High-Res)</p>

                <template x-for="(url, index) in imageUrls" :key="index">
                    <div class="p-3 bg-zinc-50 rounded-2xl border border-zinc-200 flex flex-col sm:flex-row items-start sm:items-center gap-3">
                        
                        <!-- Image Preview Thumbnail -->
                        <div class="w-16 h-16 rounded-xl bg-zinc-200 border border-zinc-300 overflow-hidden flex items-center justify-center shrink-0">
                            <template x-if="imageUrls[index] && imageUrls[index].trim().length > 5">
                                <img :src="imageUrls[index]" alt="Preview" class="w-full h-full object-cover" x-on:error="$el.src = 'https://via.placeholder.com/150?text=Invalid+Image'">
                            </template>
                            <template x-if="!imageUrls[index] || imageUrls[index].trim().length <= 5">
                                <svg class="w-6 h-6 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </template>
                        </div>

                        <!-- Input Field -->
                        <div class="flex-1 w-full">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[11px] font-semibold text-zinc-700" x-text="'Image #' + (index + 1) + (index === 0 ? ' (Main View)' : (index === 1 ? ' (Side Angle)' : (index === 2 ? ' (Fabric Detail)' : ' (Model Look)')))"></span>
                                
                                <label class="flex items-center space-x-1.5 text-[11px] text-zinc-600 cursor-pointer">
                                    <input type="radio" name="primary_image_index" :value="index" x-model="primaryIndex" class="text-black focus:ring-black">
                                    <span :class="primaryIndex == index ? 'font-bold text-zinc-950' : ''">Set as Primary</span>
                                </label>
                            </div>
                            <input type="url" name="image_urls[]" x-model="imageUrls[index]" placeholder="https://images.unsplash.com/... or Google Image URL" class="w-full text-xs bg-white border border-zinc-200 rounded-xl px-3 py-2 text-zinc-900 focus:outline-none focus:border-black shadow-2xs">
                        </div>

                        <!-- Delete Button -->
                        <button type="button" @click="removeImageUrl(index)" title="Remove Image" class="p-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 transition-colors shrink-0 self-end sm:self-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </template>
            </div>

            <!-- Option 2: Upload Files Directly from Computer -->
            <div class="pt-4 border-t border-zinc-100 space-y-3">
                <p class="text-[11px] font-bold uppercase tracking-wider text-zinc-600">Option 2: Upload Image Files from Device</p>

                <div class="border-2 border-dashed border-zinc-300 hover:border-black rounded-2xl p-6 text-center bg-zinc-50/60 transition-colors cursor-pointer relative">
                    <input type="file" name="image_files[]" multiple accept="image/*" @change="handleFileUpload($event)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <div class="space-y-1">
                        <svg class="w-8 h-8 mx-auto text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        <p class="text-xs font-bold text-zinc-900">Click to Browse or Drag & Drop Multiple Images</p>
                        <p class="text-[11px] text-zinc-500">PNG, JPG, WEBP, or SVG up to 10MB each</p>
                    </div>
                </div>

                <!-- Uploaded File Previews -->
                <div x-show="filePreviews.length > 0" class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2">
                    <template x-for="(file, i) in filePreviews" :key="i">
                        <div class="p-2 bg-white rounded-xl border border-zinc-200 shadow-xs relative group">
                            <div class="aspect-square rounded-lg overflow-hidden bg-zinc-100 mb-1.5">
                                <img :src="file.src" :alt="file.name" class="w-full h-full object-cover">
                            </div>
                            <p class="text-[10px] font-bold text-zinc-900 truncate" x-text="file.name"></p>
                            <span class="text-[9px] text-zinc-400" x-text="file.size"></span>
                        </div>
                    </template>
                </div>
            </div>

        </div>

        <!-- Sizing, Colors & Stock Inventory Matrix -->
        <div class="p-6 sm:p-8 bg-white rounded-2xl border border-zinc-200 shadow-xs space-y-5">
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#8C6D46] pb-2 border-b border-zinc-100">
                Sizing & Stock Inventory Generation
            </h3>

            <!-- Size Checkboxes -->
            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-2">Select Available Sizes (All active by default):</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($sizes as $size)
                        <label class="flex items-center space-x-2 px-3.5 py-2 rounded-xl bg-zinc-50 border border-zinc-200 hover:border-black cursor-pointer text-xs font-bold text-zinc-900 transition-colors">
                            <input type="checkbox" name="selected_sizes[]" value="{{ $size->id }}" checked class="rounded text-black focus:ring-black">
                            <span>{{ $size->name }} ({{ $size->code }})</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Color Selection -->
            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-2">Select Primary Colorway:</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($colors as $color)
                        <label class="flex items-center space-x-2 px-3.5 py-2 rounded-xl bg-zinc-50 border border-zinc-200 hover:border-black cursor-pointer text-xs font-bold text-zinc-900 transition-colors">
                            <input type="checkbox" name="selected_colors[]" value="{{ $color->id }}" {{ $loop->first ? 'checked' : '' }} class="rounded text-black focus:ring-black">
                            <span class="w-3 h-3 rounded-full border border-zinc-300" style="background-color: {{ $color->hex_code }}"></span>
                            <span>{{ $color->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Default Stock per Variant -->
            <div class="max-w-xs">
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Default Stock Quantity per Size/Color Variant</label>
                <input type="number" name="default_stock" value="25" min="0" required class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 font-bold focus:outline-none focus:border-black">
            </div>
        </div>

        <!-- Sartorial Specifications -->
        <div class="p-6 sm:p-8 bg-white rounded-2xl border border-zinc-200 shadow-xs space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#8C6D46] pb-2 border-b border-zinc-100">
                Sartorial Specifications
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Fabric Composition</label>
                    <input type="text" name="fabric" value="100% Supima® Cotton (240 GSM)" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Fit Silhouette</label>
                    <input type="text" name="fit" value="Tailored Regular Fit" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Collar Construction</label>
                    <input type="text" name="collar_type" value="Stay-Flat Ribbed Collar" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Sleeve Type</label>
                    <input type="text" name="sleeve_type" value="Short Sleeve with Ribbed Cuff" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none">
                </div>
            </div>
        </div>

        <!-- Visibility Flags -->
        <div class="p-6 sm:p-8 bg-white rounded-2xl border border-zinc-200 shadow-xs space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#8C6D46] pb-2 border-b border-zinc-100">
                Storefront Visibility
            </h3>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs font-semibold text-zinc-800">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded text-black focus:ring-black">
                    <span>Active in Store</span>
                </label>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="is_new_arrival" value="1" checked class="rounded text-black focus:ring-black">
                    <span>New Arrival</span>
                </label>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="is_bestseller" value="1" class="rounded text-black focus:ring-black">
                    <span>Best Seller</span>
                </label>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" class="rounded text-black focus:ring-black">
                    <span>Featured</span>
                </label>
            </div>
        </div>

        <!-- Action Buttons Bar -->
        <div class="flex items-center justify-end space-x-4 pt-4">
            <a href="{{ route('admin.products.index') }}" class="px-6 py-3 border border-zinc-300 text-zinc-700 hover:text-black rounded-xl text-xs font-bold uppercase tracking-wider">
                Cancel
            </a>
            <button type="submit" class="px-8 py-3 bg-zinc-950 hover:bg-black text-white rounded-xl text-xs font-bold uppercase tracking-widest transition-all shadow-md hover:shadow-lg flex items-center space-x-2">
                <svg class="w-4 h-4 text-[#C5A880]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Publish Product with Images</span>
            </button>
        </div>

    </form>

</div>

@endsection
