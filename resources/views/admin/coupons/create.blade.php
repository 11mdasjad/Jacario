@extends('layouts.admin')

@section('title', 'Create Promo Code')
@section('header_title', 'Create Promotional Coupon')

@section('content')

<div class="max-w-2xl mx-auto space-y-6">
    
    <div class="flex items-center justify-between pb-4 border-b border-zinc-200">
        <div>
            <h1 class="text-xl font-serif-luxury font-bold text-zinc-950">New Promotional Voucher</h1>
            <p class="text-xs text-zinc-500 mt-0.5">Generate campaign codes with discount rules and minimum spends</p>
        </div>
        <a href="{{ route('admin.coupons.index') }}" class="text-xs font-bold text-zinc-600 hover:text-black uppercase tracking-wider">
            ← Back to Coupons
        </a>
    </div>

    <form method="POST" action="{{ route('admin.coupons.store') }}" class="p-6 bg-white rounded-2xl border border-zinc-200 shadow-xs space-y-4">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Coupon Code *</label>
                <input type="text" name="code" value="{{ old('code') }}" required placeholder="e.g. SUMMER300" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 uppercase font-mono font-bold focus:outline-none focus:border-black">
                @error('code') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Discount Type *</label>
                <select name="type" required class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none focus:border-black">
                    <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>Percentage Discount (%)</option>
                    <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>Flat Cash Discount (₹)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Discount Value *</label>
                <input type="number" step="0.01" name="value" value="{{ old('value') }}" required placeholder="10 or 300" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none focus:border-black">
                @error('value') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Minimum Spend (₹)</label>
                <input type="number" step="0.01" name="min_spend" value="{{ old('min_spend') }}" placeholder="1499.00" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none focus:border-black">
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Maximum Uses</label>
                <input type="number" name="max_uses" value="{{ old('max_uses') }}" placeholder="100 (Optional)" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none focus:border-black">
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Expiry Date</label>
                <input type="date" name="expires_at" value="{{ old('expires_at') }}" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none focus:border-black">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-zinc-700 mb-1">Description / Campaign Notes</label>
            <input type="text" name="description" value="{{ old('description') }}" placeholder="e.g. VIP client reward" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none focus:border-black">
        </div>

        <div class="pt-2">
            <label class="flex items-center space-x-2 text-xs font-semibold text-zinc-800 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded text-black focus:ring-black">
                <span>Active for Checkout Immediately</span>
            </label>
        </div>

        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-zinc-100">
            <a href="{{ route('admin.coupons.index') }}" class="px-5 py-2.5 border border-zinc-300 text-zinc-700 hover:text-black rounded-xl text-xs font-bold uppercase tracking-wider">
                Cancel
            </a>
            <button type="submit" class="px-7 py-2.5 bg-zinc-950 hover:bg-black text-white rounded-xl text-xs font-bold uppercase tracking-widest transition-colors shadow-xs">
                Create Coupon
            </button>
        </div>
    </form>

</div>

@endsection
