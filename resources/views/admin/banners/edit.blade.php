@extends('layouts.admin')

@section('title', 'Edit Hero Banner #' . $banner->sort_order)
@section('header_title', 'Edit Banner')

@section('content')

<div class="max-w-3xl mx-auto space-y-6" x-data="{
    imageUrl: '{{ $banner->image_url }}',
    previewFile(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => { this.imageUrl = e.target.result; };
            reader.readAsDataURL(file);
        }
    }
}">
    
    <div class="flex items-center justify-between pb-4 border-b border-zinc-200">
        <div>
            <h1 class="text-xl font-serif-luxury font-bold text-zinc-950">Edit Banner #{{ $banner->sort_order }}</h1>
            <p class="text-xs text-zinc-500 mt-0.5">Update promotional imagery, title, and shopping link</p>
        </div>
        <a href="{{ route('admin.banners.index') }}" class="text-xs font-bold text-zinc-600 hover:text-black uppercase tracking-wider">
            ← Back to Banners
        </a>
    </div>

    <form method="POST" action="{{ route('admin.banners.update', $banner->id) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="p-6 sm:p-8 bg-white rounded-2xl border border-zinc-200 shadow-xs space-y-5">
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#8C6D46] pb-2 border-b border-zinc-100">
                Banner Content & Typography
            </h3>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Headline / Title *</label>
                <input type="text" name="title" value="{{ old('title', $banner->title) }}" required placeholder="e.g. The Polo, Perfected." class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none focus:border-black">
                @error('title') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Subtext / Description</label>
                <textarea name="subtitle" rows="2" placeholder="e.g. Premium Polo T-Shirts designed for effortless everyday style." class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none focus:border-black">{{ old('subtitle', $banner->subtitle) }}</textarea>
                @error('subtitle') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Eyebrow Badge (Optional)</label>
                    <input type="text" name="badge_text" value="{{ old('badge_text', $banner->badge_text) }}" placeholder="e.g. ✦ The Haute Collection" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none focus:border-black">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Carousel Sort Order *</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order) }}" required min="1" max="99" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 font-bold focus:outline-none focus:border-black">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">CTA Button Text *</label>
                    <input type="text" name="cta_text" value="{{ old('cta_text', $banner->cta_text) }}" required placeholder="e.g. Shop Now, Explore Collection" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 font-bold focus:outline-none focus:border-black">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">CTA Destination URL *</label>
                    <input type="text" name="cta_url" value="{{ old('cta_url', $banner->cta_url) }}" required placeholder="e.g. /shop or /shop?collection=new-arrivals" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none focus:border-black">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Banner Location / Placement *</label>
                    <select name="position" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 font-bold focus:outline-none focus:border-black">
                        <option value="hero" {{ old('position', $banner->position ?? 'hero') === 'hero' ? 'selected' : '' }}>✨ Homepage Hero Carousel (Full Bleed)</option>
                        <option value="shop" {{ old('position', $banner->position ?? 'hero') === 'shop' ? 'selected' : '' }}>🛍️ Shop Catalog Running Carousel (3-Banner Header)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Text Alignment *</label>
                    <select name="text_alignment" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none focus:border-black">
                        <option value="left" {{ old('text_alignment', $banner->text_alignment) === 'left' ? 'selected' : '' }}>Left Aligned (Editorial)</option>
                        <option value="center" {{ old('text_alignment', $banner->text_alignment) === 'center' ? 'selected' : '' }}>Center Aligned (Cinematic)</option>
                        <option value="right" {{ old('text_alignment', $banner->text_alignment) === 'right' ? 'selected' : '' }}>Right Aligned (Sartorial)</option>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Mobile Image URL (Optional)</label>
                    <input type="url" name="mobile_image_url" value="{{ old('mobile_image_url', $banner->mobile_image_path) }}" placeholder="https://... (falls back to desktop image)" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none focus:border-black">
                </div>
            </div>

            <!-- Active Status -->
            <div class="pt-2">
                <label class="flex items-center space-x-2 text-xs font-bold text-zinc-800 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $banner->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded text-black focus:ring-black">
                    <span>Banner is Active on Storefront</span>
                </label>
            </div>
        </div>

        <!-- Banner Photography Card -->
        <div class="p-6 sm:p-8 bg-white rounded-2xl border border-zinc-200 shadow-xs space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#8C6D46] pb-2 border-b border-zinc-100">
                Banner Photography
            </h3>

            <!-- Live Preview -->
            <div class="aspect-[16/9] rounded-2xl bg-zinc-100 border border-zinc-200 overflow-hidden relative flex items-center justify-center">
                <template x-if="imageUrl">
                    <img :src="imageUrl" alt="Preview" class="w-full h-full object-cover">
                </template>
                <template x-if="!imageUrl">
                    <div class="text-center p-6 text-zinc-400">
                        <svg class="w-10 h-10 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-xs font-bold">Image preview will appear here</p>
                    </div>
                </template>
            </div>

            <!-- Option 1: Direct Web URL -->
            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Option A: Update Image URL</label>
                <input type="url" name="image_url" x-model="imageUrl" placeholder="https://images.unsplash.com/... or CDN link" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none focus:border-black">
                @error('image_url') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Option 2: Upload File -->
            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Option B: Or Upload New Image File</label>
                <input type="file" name="image_file" accept="image/*" @change="previewFile($event)" class="w-full text-xs text-zinc-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-zinc-900 file:text-white hover:file:bg-black cursor-pointer">
                @error('image_file') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3 pt-2">
            <a href="{{ route('admin.banners.index') }}" class="px-6 py-3 bg-zinc-100 hover:bg-zinc-200 text-zinc-800 text-xs font-bold uppercase tracking-wider rounded-xl transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-8 py-3 bg-zinc-950 hover:bg-black text-[#DFCAAB] text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-md">
                Update Banner
            </button>
        </div>
    </form>

</div>

@endsection
