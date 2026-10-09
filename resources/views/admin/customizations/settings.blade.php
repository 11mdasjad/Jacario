@extends('layouts.admin')

@section('title', 'Studio Settings & Pricing Rules — ' . $product->name)
@section('header_title', 'Studio Customization Settings & Pricing')

@section('content')
<div class="p-6 sm:p-8 space-y-6 max-w-5xl mx-auto" x-data="{
    bulkTiers: {{ json_encode($settings->bulk_pricing ?: [
        ['min_qty' => 5, 'discount_percent' => 10],
        ['min_qty' => 15, 'discount_percent' => 18],
        ['min_qty' => 30, 'discount_percent' => 25],
        ['min_qty' => 50, 'discount_percent' => 35]
    ]) }},
    addTier() {
        this.bulkTiers.push({ min_qty: 10, discount_percent: 15 });
    },
    removeTier(idx) {
        this.bulkTiers.splice(idx, 1);
    }
}">
    
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold font-serif-luxury text-zinc-950">Studio Rules & Pricing: {{ $product->name }}</h1>
            <p class="text-xs text-zinc-500 mt-1">Configure artwork upload constraints, typography font lists, add-on fees, and quantity bulk discount tiers.</p>
        </div>

        <a href="{{ route('admin.customizations.products') }}" class="px-4 py-2 rounded-xl bg-white border border-zinc-200 text-xs font-bold text-zinc-700 hover:bg-zinc-50">
            ← Back to Products
        </a>
    </div>

    <form method="POST" action="{{ route('admin.customizations.settings.update', $product->id) }}" class="space-y-6">
        @csrf

        <!-- 1. Customization Scope & Upload Constraints -->
        <div class="bg-white rounded-3xl border border-zinc-200 shadow-sm p-6 sm:p-8 space-y-5">
            <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-950 pb-3 border-b border-zinc-100 flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-[#A4845B]"></span>
                <span>Artwork & Upload Constraints</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-900 mb-1">Allowed Image Formats (comma-separated)</label>
                    <input type="text" name="allowed_file_types" value="{{ old('allowed_file_types', implode(', ', $settings->allowed_file_types ?: ['png', 'jpg', 'jpeg', 'svg', 'webp'])) }}" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-zinc-300 focus:outline-none focus:border-black font-mono">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-900 mb-1">Max Upload File Size (MB)</label>
                    <input type="number" min="1" max="50" name="max_file_size_mb" value="{{ old('max_file_size_mb', $settings->max_file_size_mb ?? 15) }}" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-zinc-300 focus:outline-none focus:border-black">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-900 mb-1">Max Number of Artwork Uploads per Shirt</label>
                    <input type="number" min="1" max="10" name="max_images" value="{{ old('max_images', $settings->max_images ?? 4) }}" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-zinc-300 focus:outline-none focus:border-black">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-900 mb-1">Max Text Character Length</label>
                    <input type="number" min="5" max="100" name="max_text_len" value="{{ old('max_text_len', $settings->max_text_len ?? 50) }}" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-zinc-300 focus:outline-none focus:border-black">
                    <input type="hidden" name="min_text_len" value="1">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-900 mb-1">Allowed Google Fonts in Studio (comma-separated)</label>
                    <input type="text" name="allowed_fonts" value="{{ old('allowed_fonts', implode(', ', $settings->allowed_fonts ?: ['Cinzel', 'Plus Jakarta Sans', 'Inter', 'Playfair Display', 'Montserrat', 'Bebas Neue', 'Outfit', 'Space Grotesk'])) }}" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-zinc-300 focus:outline-none focus:border-black font-medium">
                </div>

                <div class="sm:col-span-2 pt-2 flex items-center space-x-6">
                    <label class="flex items-center space-x-2 text-xs font-bold text-zinc-800 cursor-pointer">
                        <input type="checkbox" name="allow_front" value="1" {{ $settings->allow_front ? 'checked' : '' }} class="rounded border-zinc-300 text-zinc-900 focus:ring-black">
                        <span>Front Customization Enabled</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- 2. Additional Customization Fees -->
        <div class="bg-white rounded-3xl border border-zinc-200 shadow-sm p-6 sm:p-8 space-y-5">
            <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-950 pb-3 border-b border-zinc-100 flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-[#A4845B]"></span>
                <span>Customization Fee Breakdown</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-900 mb-1">Base Customization Charge (₹)</label>
                    <input type="number" step="0.01" min="0" name="base_customization_fee" value="{{ old('base_customization_fee', $settings->base_customization_fee ?? 0.00) }}" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-zinc-300 focus:outline-none focus:border-black">
                    <p class="text-[10px] text-zinc-400 mt-1">Flat setup charge (or 0 for included)</p>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-900 mb-1">Extra Logo / Image Charge (₹)</label>
                    <input type="number" step="0.01" min="0" name="extra_image_fee" value="{{ old('extra_image_fee', $settings->extra_image_fee ?? 99.00) }}" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-zinc-300 focus:outline-none focus:border-black">
                    <p class="text-[10px] text-zinc-400 mt-1">Charged for each logo beyond the 1st</p>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-900 mb-1">Extra Text Layer Charge (₹)</label>
                    <input type="number" step="0.01" min="0" name="extra_text_fee" value="{{ old('extra_text_fee', $settings->extra_text_fee ?? 49.00) }}" required class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-zinc-300 focus:outline-none focus:border-black">
                    <p class="text-[10px] text-zinc-400 mt-1">Charged for each text block beyond the 1st</p>
                </div>
            </div>
        </div>

        <!-- 3. Quantity Bulk Pricing Tiers -->
        <div class="bg-white rounded-3xl border border-zinc-200 shadow-sm p-6 sm:p-8 space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100">
                <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-950 flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-[#A4845B]"></span>
                    <span>Quantity Bulk Discount Tiers</span>
                </h2>

                <button type="button" @click="addTier()" class="px-3 py-1.5 rounded-lg bg-zinc-100 hover:bg-zinc-200 text-xs font-bold text-zinc-800 transition-colors">
                    + Add Tier
                </button>
            </div>

            <div class="space-y-3">
                <template x-for="(tier, idx) in bulkTiers" :key="idx">
                    <div class="flex items-center space-x-4 p-3 rounded-2xl bg-zinc-50 border border-zinc-200">
                        <div class="flex-1">
                            <label class="block text-[10px] font-bold text-zinc-500 uppercase mb-0.5">Min Units Quantity</label>
                            <input type="number" min="1" max="1000" :name="'bulk_min_qty[' + idx + ']'" x-model.number="tier.min_qty" class="w-full text-xs px-3 py-1.5 rounded-lg border border-zinc-300 bg-white font-bold">
                        </div>

                        <div class="flex-1">
                            <label class="block text-[10px] font-bold text-zinc-500 uppercase mb-0.5">Discount Percentage (%)</label>
                            <input type="number" min="1" max="90" step="0.5" :name="'bulk_discount[' + idx + ']'" x-model.number="tier.discount_percent" class="w-full text-xs px-3 py-1.5 rounded-lg border border-zinc-300 bg-white font-bold text-emerald-700">
                        </div>

                        <div class="pt-4">
                            <button type="button" @click="removeTier(idx)" class="p-2 rounded-lg text-rose-600 hover:bg-rose-50 font-bold" title="Delete Tier">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('admin.customizations.products') }}" class="px-5 py-2.5 rounded-xl border border-zinc-300 text-xs font-bold text-zinc-700 hover:bg-zinc-50">Cancel</a>
            <button type="submit" class="px-8 py-3 rounded-xl bg-zinc-950 hover:bg-black text-[#DFCAAB] text-xs font-bold uppercase tracking-widest shadow-xl">
                Save Studio Settings & Pricing
            </button>
        </div>

    </form>

</div>
@endsection
