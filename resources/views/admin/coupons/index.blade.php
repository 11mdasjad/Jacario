@extends('layouts.admin')

@section('title', 'Promotional Coupons')
@section('header_title', 'Coupons & Promotional Codes')

@section('content')

<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-serif-luxury font-bold text-zinc-950">Promotional Coupons</h1>
            <p class="text-xs text-zinc-500 mt-0.5">Manage percentage discounts, flat fee reductions, and validity rules</p>
        </div>

        <a href="{{ route('admin.coupons.create') }}" class="px-5 py-2.5 bg-zinc-950 hover:bg-black text-white rounded-xl text-xs font-bold uppercase tracking-wider transition-colors flex items-center space-x-1.5 shadow-xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Create Promo Code</span>
        </a>
    </div>

    <!-- Coupons Table -->
    <div class="bg-white rounded-2xl border border-zinc-200 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead>
                    <tr class="border-b border-zinc-200 text-zinc-500 uppercase tracking-wider text-[10px] bg-zinc-50">
                        <th class="p-4">Coupon Code</th>
                        <th class="p-4">Discount Type</th>
                        <th class="p-4">Value</th>
                        <th class="p-4">Min. Spend</th>
                        <th class="p-4">Usage Count</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @forelse($coupons as $coupon)
                        <tr class="hover:bg-zinc-50/80 transition-colors">
                            <td class="p-4">
                                <span class="font-mono font-bold text-[#8C6D46] text-sm">{{ $coupon->code }}</span>
                                <p class="text-[10px] text-zinc-500">{{ $coupon->description }}</p>
                            </td>
                            <td class="p-4 uppercase text-zinc-700 font-semibold">{{ $coupon->discount_type ?: $coupon->type }}</td>
                            <td class="p-4 font-bold text-zinc-950">
                                {{ ($coupon->discount_type ?: $coupon->type) === 'percentage' ? $coupon->value . '%' : '₹' . number_format($coupon->value) }}
                            </td>
                            <td class="p-4 text-zinc-700">
                                {{ ($coupon->min_order_value ?: $coupon->min_spend) ? '₹' . number_format($coupon->min_order_value ?: $coupon->min_spend) : 'No Minimum' }}
                            </td>
                            <td class="p-4 font-mono text-zinc-700">
                                {{ $coupon->used_count ?: $coupon->times_used }} {{ ($coupon->usage_limit ?: $coupon->max_uses) ? '/ ' . ($coupon->usage_limit ?: $coupon->max_uses) : 'times' }}
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $coupon->is_active ? 'bg-emerald-50 text-emerald-800 border border-emerald-300' : 'bg-zinc-100 text-zinc-600 border border-zinc-300' }}">
                                    {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="px-2.5 py-1 bg-zinc-100 hover:bg-zinc-200 text-zinc-800 rounded-lg text-xs font-semibold">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.coupons.destroy', $coupon->id) }}" onsubmit="return confirm('Delete coupon code?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs font-semibold">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center text-zinc-400">
                                <p class="text-sm font-medium">No promotional coupons configured.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($coupons->hasPages())
            <div class="p-4 border-t border-zinc-200 bg-white">
                {{ $coupons->links() }}
            </div>
        @endif
    </div>

</div>

@endsection
