@extends('layouts.app')

@section('title', 'Frequently Asked Questions | JACARIO')

@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    
    <div class="text-center space-y-3 mb-12">
        <span class="text-xs font-bold uppercase tracking-[0.25em] text-[#A4845B]">Help & Guidance</span>
        <h1 class="text-3xl sm:text-4xl font-serif-luxury font-bold text-zinc-900">Frequently Asked Questions</h1>
        <p class="text-xs sm:text-sm text-zinc-500 font-light max-w-md mx-auto">
            Everything you need to know about our luxury Polo T-Shirts, doorstep exchanges, sizing, and international logistics.
        </p>
    </div>

    @php
        $faqItems = [
            [
                'q' => 'What makes JACARIO Polo T-Shirts distinct from conventional brands?',
                'a' => 'We specialize exclusively in Polo T-Shirts. We do not manufacture jeans, hoodies, or other apparel. Every ounce of our engineering is poured into double-twisted American Supima® cotton, Mulberry silk blends, and anti-curl collar interlinings that stay crisp through years of wear.'
            ],
            [
                'q' => 'How does your 15-Day Complimentary Doorstep Exchange work?',
                'a' => 'If you require a different size or color, simply initiate an exchange from your account portal or contact concierge@jacario.com. Our express courier partner will pick up the unwashed polo from your doorstep and deliver the replacement at zero additional charge.'
            ],
            [
                'q' => 'What payment methods do you accept?',
                'a' => 'We process secure 256-bit SSL encrypted payments via Razorpay supporting UPI (Google Pay, PhonePe, Paytm), Visa, Mastercard, AMEX, and Net Banking. Cash on Delivery (COD) is also available across all serviced PIN codes.'
            ],
            [
                'q' => 'Will JACARIO Polos shrink after machine washing?',
                'a' => 'No. All our fabrics undergo a proprietary thermal pre-shrinking and stabilization process. When washed in cold water according to the care label, dimensional shrinkage is under 1%.'
            ],
            [
                'q' => 'Are your buttons synthetic or natural mother-of-pearl?',
                'a' => 'Every JACARIO button is authentic Australian mother-of-pearl carved from real oyster shells, featuring natural iridescent luster and cross-anchored with heat-sealed threading.'
            ],
            [
                'q' => 'How long will delivery take?',
                'a' => 'Orders are dispatched within 24 hours from our Mumbai atelier. Metro deliveries typically arrive within 2–3 business days, while nationwide deliveries take 3–5 business days.'
            ]
        ];
    @endphp

    <div class="space-y-4" x-data="{ activeAccordion: null }">
        @foreach($faqItems as $index => $faq)
            <div class="p-6 bg-white rounded-2xl border border-zinc-200 shadow-sm cursor-pointer" @click="activeAccordion === {{ $index }} ? activeAccordion = null : activeAccordion = {{ $index }}">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm sm:text-base font-bold text-zinc-900">{{ $faq['q'] }}</h3>
                    <span class="text-zinc-400 text-lg font-bold" x-text="activeAccordion === {{ $index }} ? '−' : '+'"></span>
                </div>
                <div x-show="activeAccordion === {{ $index }}" x-collapse class="mt-3 text-xs sm:text-sm text-zinc-600 font-light leading-relaxed pt-2 border-t border-zinc-100">
                    {{ $faq['a'] }}
                </div>
            </div>
        @endforeach
    </div>

</div>

@endsection
