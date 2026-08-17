@extends('layouts.app')

@section('title', 'Client Concierge & Contact | JACARIO')

@section('content')

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        
        <!-- Left: Coordinates (5 Cols) -->
        <div class="lg:col-span-5 space-y-8">
            <div>
                <span class="text-xs font-bold uppercase tracking-[0.25em] text-[#A4845B]">Client Concierge</span>
                <h1 class="text-3xl font-serif-luxury font-bold text-zinc-900 tracking-tight mt-1">
                    Connect with Our Atelier
                </h1>
                <p class="text-xs sm:text-sm text-zinc-500 mt-2 font-light leading-relaxed">
                    Whether you require bespoke sizing consultation, corporate gifting, or order assistance, our atelier specialists are at your disposal.
                </p>
            </div>

            <div class="space-y-6 pt-4 border-t border-zinc-200 text-xs">
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 rounded-lg bg-zinc-100 flex items-center justify-center text-zinc-700 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-zinc-900">Flagship Atelier</p>
                        <p class="text-zinc-600 mt-0.5">{{ \App\Models\Setting::get('store_address', '42 Heritage Boulevard, Bandra West, Mumbai 400050') }}</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 rounded-lg bg-zinc-100 flex items-center justify-center text-zinc-700 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-zinc-900">Direct Email</p>
                        <p class="text-zinc-600 mt-0.5">{{ \App\Models\Setting::get('contact_email', 'concierge@jacario.com') }}</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 rounded-lg bg-zinc-100 flex items-center justify-center text-zinc-700 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-zinc-900">Telephone Assistance</p>
                        <p class="text-zinc-600 mt-0.5">{{ \App\Models\Setting::get('contact_phone', '+91 (0) 22 8900 1200') }}</p>
                        <p class="text-[10px] text-zinc-400 mt-0.5">{{ \App\Models\Setting::get('support_hours', 'Mon – Sat: 9:00 AM – 8:00 PM IST') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Contact Form (7 Cols) -->
        <div class="lg:col-span-7 bg-white p-8 sm:p-10 rounded-2xl border border-zinc-200 shadow-xl space-y-6">
            <h3 class="text-lg font-serif-luxury font-bold text-zinc-900">Send an Inquiry</h3>

            <form method="POST" action="{{ route('contact.post') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 mb-1">Your Name *</label>
                        <input type="text" name="name" required class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-3 focus:outline-none focus:border-black">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 mb-1">Email Address *</label>
                        <input type="email" name="email" required class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-3 focus:outline-none focus:border-black">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Subject *</label>
                    <input type="text" name="subject" required placeholder="e.g. Sizing consultation / Order inquiry" class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-3 focus:outline-none focus:border-black">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Message *</label>
                    <textarea name="message" rows="5" required placeholder="How may our concierge assist you today?" class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-3 focus:outline-none focus:border-black"></textarea>
                </div>

                <button type="submit" class="w-full py-3.5 bg-[#0B0D10] hover:bg-black text-[#DFCAAB] text-xs font-bold uppercase tracking-widest rounded-xl transition-colors shadow-lg">
                    Send Message to Concierge
                </button>
            </form>
        </div>

    </div>
</div>

@endsection
