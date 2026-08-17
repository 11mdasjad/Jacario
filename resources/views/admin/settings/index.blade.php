@extends('layouts.admin')

@section('title', 'Store Settings & Policies')
@section('header_title', 'Maison Configuration & Policy Management')

@section('content')

<div class="max-w-4xl space-y-8">
    
    <div>
        <h1 class="text-xl font-serif-luxury font-bold text-zinc-950">Store Settings & Policies</h1>
        <p class="text-xs text-zinc-500 mt-0.5">Configure store shipping thresholds, contact information, and customer legal policies</p>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
        @csrf

        <!-- General Store Settings -->
        <div class="p-6 rounded-2xl bg-white border border-zinc-200 shadow-xs space-y-4">
            <h3 class="text-sm font-bold uppercase tracking-wider text-zinc-950 pb-3 border-b border-zinc-100">
                Commercial & Logistics Parameters
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Free Shipping Threshold (₹)</label>
                    <input type="number" name="free_shipping_threshold" value="{{ \App\Models\Setting::get('free_shipping_threshold', 1999) }}" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl px-3 py-2 text-zinc-900 focus:outline-none focus:border-black">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Standard Flat Shipping Fee (₹)</label>
                    <input type="number" name="standard_shipping_fee" value="{{ \App\Models\Setting::get('standard_shipping_fee', 150) }}" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl px-3 py-2 text-zinc-900 focus:outline-none focus:border-black">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Concierge Support Email</label>
                    <input type="email" name="contact_email" value="{{ \App\Models\Setting::get('contact_email', 'concierge@jacario.com') }}" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl px-3 py-2 text-zinc-900 focus:outline-none focus:border-black">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Concierge Telephone</label>
                    <input type="text" name="contact_phone" value="{{ \App\Models\Setting::get('contact_phone', '+91 (0) 22 8900 1200') }}" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl px-3 py-2 text-zinc-900 focus:outline-none focus:border-black">
                </div>
            </div>
        </div>

        <!-- Policies -->
        <div class="p-6 rounded-2xl bg-white border border-zinc-200 shadow-xs space-y-4">
            <h3 class="text-sm font-bold uppercase tracking-wider text-zinc-950 pb-3 border-b border-zinc-100">
                Customer Care & Legal Policies
            </h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Shipping & Delivery Policy</label>
                    <textarea name="shipping_policy" rows="3" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none focus:border-black font-mono leading-relaxed">{{ \App\Models\Setting::get('shipping_policy', 'All orders are dispatched within 24 business hours.') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Return & Doorstep Exchange Policy</label>
                    <textarea name="return_policy" rows="3" class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl p-3 text-zinc-900 focus:outline-none focus:border-black font-mono leading-relaxed">{{ \App\Models\Setting::get('return_policy', '15-Day hassle-free doorstep returns and size exchanges.') }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-8 py-3 bg-zinc-950 hover:bg-black text-white rounded-xl text-xs font-bold uppercase tracking-widest transition-colors shadow-sm">
                Save All Settings
            </button>
        </div>
    </form>

</div>

@endsection
