@extends('layouts.admin')

@section('title', "Edit Coupon {$coupon->code}")
@section('header_title', "Edit Promotional Coupon")

@section('content')

<div class="max-w-2xl mx-auto space-y-6">
    
    <div class="flex items-center justify-between pb-4 border-b border-zinc-200">
        <div>
            <h1 class="text-xl font-serif-luxury font-bold text-zinc-950">Edit Coupon: {{ $coupon->code }}</h1>
            <p class="text-xs text-zinc-500 mt-0.5">Times Redeemed: <strong class="text-zinc-900">{{ $coupon->used_count ?: $coupon->times_used }}</strong></p>
        </div>
        <a href="{{ route('admin.coupons.index') }}" class="text-xs font-bold text-zinc-600 hover:text-black uppercase tracking-wider">
            ← Back to Coupons
        </a>
    </div>

    <form method="POST" action="{{ route('admin.coupons.update', $coupon->id) }}" class="p-6 bg-white rounded-2xl border border-zinc-200 shadow-xs space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Coupon Code *</label>
                <input type="text" name="code" value="{{ old('code', $coupon->code) }}" required class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 uppercase font-mono font-bold focus:outline-none focus:border-black">
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Discount Type *</label>
                <select name="type" required class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none focus:border-black">
                    <option value="percentage" {{ old('type', $coupon->discount_type ?: $coupon->type) === 'percentage' ? 'selected' : '' }}>Percentage Discount (%)</option>
                    <option value="fixed" {{ old('type', $coupon->discount_type ?: $coupon->type) === 'fixed' ? 'selected' : '' }}>Flat Cash Discount (₹)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Discount Value *</label>
                <input type="number" step="0.01" name="value" value="{{ old('value', $coupon->value) }}" required class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none focus:border-black">
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Minimum Spend (₹)</label>
                <input type="number" step="0.01" name="min_spend" value="{{ old('min_spend', $coupon->min_order_value ?: $coupon->min_spend) }}" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none focus:border-black">
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Maximum Uses</label>
                <input type="number" name="max_uses" value="{{ old('max_uses', $coupon->usage_limit ?: $coupon->max_uses) }}" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none focus:border-black">
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Expiry Date</label>
                <input type="date" name="expires_at" value="{{ old('expires_at', $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : '') }}" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none focus:border-black">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-zinc-700 mb-1">Description / Campaign Notes</label>
            <input type="text" name="description" value="{{ old('description', $coupon->description) }}" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none focus:border-black">
        </div>

        <div class="pt-2">
            <label class="flex items-center space-x-2 text-xs font-semibold text-zinc-800 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }} class="rounded text-black focus:ring-black">
                <span>Active for Checkout Immediately</span>
            </label>
        </div>

        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-zinc-100">
            <a href="{{ route('admin.coupons.index') }}" class="px-5 py-2.5 border border-zinc-300 text-zinc-700 hover:text-black rounded-xl text-xs font-bold uppercase tracking-wider">
                Cancel
            </a>
            <button type="submit" class="px-7 py-2.5 bg-zinc-950 hover:bg-black text-white rounded-xl text-xs font-bold uppercase tracking-widest transition-colors shadow-xs">
                Update Coupon
            </button>
        </div>
    </form>

</div>

@endsection
