@extends('layouts.app')

@section('title', 'Secure Checkout | JACARIO Haute Apparel')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ 
    useSavedAddress: {{ $savedAddresses->isNotEmpty() ? 'true' : 'false' }},
    selectedAddressId: '{{ $savedAddresses->firstWhere('is_default', true)?->id ?? $savedAddresses->first()?->id ?? '' }}',
    paymentMethod: 'razorpay'
}">
    
    <div class="mb-8">
        <h1 class="text-3xl font-serif-luxury font-bold text-zinc-900 tracking-tight">Express Checkout</h1>
        <p class="text-xs text-zinc-500 mt-1">Direct from our Mumbai atelier to your doorstep with full tracking</p>
    </div>

    <form method="POST" action="{{ route('checkout.order') }}">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <!-- Left Form Area (7 Cols) -->
            <div class="lg:col-span-7 space-y-8">
                
                <!-- 1. Customer Contact Details -->
                <div class="p-6 bg-white rounded-2xl border border-zinc-200 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-zinc-100">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-zinc-900 flex items-center space-x-2">
                            <span class="w-5 h-5 rounded-full bg-zinc-900 text-white text-[10px] flex items-center justify-center">1</span>
                            <span>Client Information</span>
                        </h3>
                        @guest
                            <a href="{{ route('login', ['redirect' => route('checkout.index')]) }}" class="text-xs text-[#A4845B] hover:underline font-semibold">
                                Already have an account? Sign in
                            </a>
                        @endguest
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-zinc-700 mb-1">Full Legal Name *</label>
                            <input type="text" name="customer_name" value="{{ old('customer_name', $user?->name) }}" required class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-3 focus:outline-none focus:border-black">
                            @error('customer_name') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 mb-1">Email Address (for order receipts & tracking) *</label>
                            <input type="email" name="customer_email" value="{{ old('customer_email', $user?->email) }}" required class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-3 focus:outline-none focus:border-black">
                            @error('customer_email') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 mb-1">Mobile Phone (for delivery SMS) *</label>
                            <input type="tel" name="customer_phone" value="{{ old('customer_phone', $user?->phone) }}" required placeholder="+91 98200 12345" class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-3 focus:outline-none focus:border-black">
                            @error('customer_phone') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- 2. Shipping Delivery Address -->
                <div class="p-6 bg-white rounded-2xl border border-zinc-200 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-zinc-100">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-zinc-900 flex items-center space-x-2">
                            <span class="w-5 h-5 rounded-full bg-zinc-900 text-white text-[10px] flex items-center justify-center">2</span>
                            <span>Shipping Address</span>
                        </h3>
                    </div>

                    @if($savedAddresses->isNotEmpty())
                        <!-- Saved Address Picker -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-semibold text-zinc-700">Choose from Saved Addresses:</span>
                                <button type="button" @click="useSavedAddress = !useSavedAddress" class="text-[#A4845B] hover:underline font-bold" x-text="useSavedAddress ? '+ Add New Address' : 'Use Saved Address'"></button>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" x-show="useSavedAddress">
                                @foreach($savedAddresses as $addr)
                                    <label class="p-4 rounded-xl border cursor-pointer transition-all flex flex-col justify-between"
                                           :class="selectedAddressId == '{{ $addr->id }}' ? 'border-zinc-950 ring-2 ring-zinc-950 bg-zinc-50/60' : 'border-zinc-200 bg-white hover:border-zinc-400'">
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-center space-x-2">
                                                <input type="radio" name="address_id" value="{{ $addr->id }}" x-model="selectedAddressId" class="text-zinc-900 focus:ring-black">
                                                <span class="text-xs font-bold text-zinc-900">{{ $addr->full_name }}</span>
                                            </div>
                                            <span class="text-[10px] uppercase font-semibold px-2 py-0.5 rounded bg-zinc-200 text-zinc-700">{{ $addr->address_type }}</span>
                                        </div>
                                        <p class="text-xs text-zinc-600 mt-2 leading-relaxed">{{ $addr->formatted_address }}</p>
                                        <p class="text-xs text-zinc-500 mt-1 font-mono">{{ $addr->phone }}</p>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Manual New Address Form -->
                    <div x-show="!useSavedAddress || {{ $savedAddresses->isEmpty() ? 'true' : 'false' }}" class="space-y-4 pt-2">
                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 mb-1">Street Address / House / Apartment / Suite *</label>
                            <input type="text" name="address_line_1" value="{{ old('address_line_1') }}" placeholder="Flat/House No., Building Name, Street" class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-3 focus:outline-none focus:border-black">
                            @error('address_line_1') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-zinc-700 mb-1">Area / Locality / Sector</label>
                                <input type="text" name="address_line_2" value="{{ old('address_line_2') }}" placeholder="e.g. Bandra West" class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-3 focus:outline-none focus:border-black">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-zinc-700 mb-1">Prominent Landmark</label>
                                <input type="text" name="landmark" value="{{ old('landmark') }}" placeholder="e.g. Near AC Market" class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-3 focus:outline-none focus:border-black">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-zinc-700 mb-1">City *</label>
                                <input type="text" name="city" value="{{ old('city') }}" placeholder="Mumbai" class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-3 focus:outline-none focus:border-black">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-zinc-700 mb-1">State *</label>
                                <input type="text" name="state" value="{{ old('state') }}" placeholder="Maharashtra" class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-3 focus:outline-none focus:border-black">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-zinc-700 mb-1">Postal Code (PIN) *</label>
                                <input type="text" name="postal_code" value="{{ old('postal_code') }}" placeholder="400050" maxlength="6" class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-3 focus:outline-none focus:border-black">
                            </div>
                        </div>

                        @auth
                            <div class="pt-2">
                                <label class="flex items-center space-x-2 text-xs text-zinc-700 cursor-pointer">
                                    <input type="checkbox" name="save_address" value="1" checked class="rounded border-zinc-300 text-zinc-900 focus:ring-black">
                                    <span>Save this address to my JACARIO address book for future orders</span>
                                </label>
                            </div>
                        @endauth
                    </div>
                </div>

                <!-- 3. Payment Method -->
                <div class="p-6 bg-white rounded-2xl border border-zinc-200 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-zinc-100">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-zinc-900 flex items-center space-x-2">
                            <span class="w-5 h-5 rounded-full bg-zinc-900 text-white text-[10px] flex items-center justify-center">3</span>
                            <span>Payment Method</span>
                        </h3>
                        <span class="text-xs text-emerald-700 font-semibold flex items-center space-x-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <span>Encrypted Gateway</span>
                        </span>
                    </div>

                    <div class="space-y-3">
                        <!-- Razorpay Gateway Option -->
                        <label class="p-4 rounded-xl border cursor-pointer transition-all flex items-center justify-between"
                               :class="paymentMethod === 'razorpay' ? 'border-zinc-950 ring-2 ring-zinc-950 bg-zinc-50/60' : 'border-zinc-200 bg-white hover:border-zinc-300'">
                            <div class="flex items-center space-x-3">
                                <input type="radio" name="payment_method" value="razorpay" x-model="paymentMethod" class="text-zinc-900 focus:ring-black">
                                <div>
                                    <p class="text-xs font-bold text-zinc-900">Razorpay (Cards, UPI, Net Banking, Wallets)</p>
                                    <p class="text-[11px] text-zinc-500">Google Pay, PhonePe, Paytm, Visa, Mastercard, AMEX & Net Banking</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-zinc-700 bg-zinc-200 px-2.5 py-1 rounded">Instant</span>
                        </label>

                        <!-- Cash on Delivery Option -->
                        <label class="p-4 rounded-xl border cursor-pointer transition-all flex items-center justify-between"
                               :class="paymentMethod === 'cod' ? 'border-zinc-950 ring-2 ring-zinc-950 bg-zinc-50/60' : 'border-zinc-200 bg-white hover:border-zinc-300'">
                            <div class="flex items-center space-x-3">
                                <input type="radio" name="payment_method" value="cod" x-model="paymentMethod" class="text-zinc-900 focus:ring-black">
                                <div>
                                    <p class="text-xs font-bold text-zinc-900">Cash on Delivery (COD)</p>
                                    <p class="text-[11px] text-zinc-500">Pay cash or UPI upon delivery inspection at your doorstep</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-zinc-700 bg-zinc-200 px-2.5 py-1 rounded">Pay at Door</span>
                        </label>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 mb-1">Special Delivery Instructions (Optional)</label>
                        <input type="text" name="notes" placeholder="e.g. Leave with building security concierge or call before delivery" class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-2.5 focus:outline-none focus:border-black">
                    </div>
                </div>

            </div>

            <!-- Right Summary Area (5 Cols) -->
            <div class="lg:col-span-5 space-y-6">
                
                <div class="p-6 bg-white rounded-2xl border border-zinc-200 shadow-sm space-y-6 sticky top-28">
                    <h3 class="text-base font-serif-luxury font-bold text-zinc-900 pb-3 border-b border-zinc-100">
                        Order Review ({{ $cartSummary['item_count'] }} items)
                    </h3>

                    <!-- Order Items Preview -->
                    <div class="space-y-4 max-h-64 overflow-y-auto divide-y divide-zinc-100 pr-1">
                        @foreach($cartSummary['items'] as $item)
                            <div class="flex items-center space-x-3 pt-3 first:pt-0">
                                <div class="w-14 h-16 bg-zinc-50 rounded-lg border border-zinc-100 p-1 flex-shrink-0 flex items-center justify-center">
                                    <img src="{{ $item['image_url'] }}" alt="{{ $item['product_name'] }}" class="w-full h-full object-contain">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-xs font-bold text-zinc-900 truncate">{{ $item['product_name'] }}</h4>
                                    <p class="text-[11px] text-zinc-500">Size: <strong class="text-zinc-800">{{ $item['size'] }}</strong> • Color: {{ $item['color'] }}</p>
                                    <p class="text-[11px] text-zinc-500">Qty: {{ $item['quantity'] }} × ₹{{ number_format($item['unit_price']) }}</p>
                                </div>
                                <span class="text-xs font-bold text-zinc-900">₹{{ number_format($item['subtotal']) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Financials -->
                    <div class="space-y-2 text-xs text-zinc-600 pt-4 border-t border-zinc-100">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span class="font-bold text-zinc-900">₹{{ number_format($cartSummary['subtotal']) }}</span>
                        </div>

                        @if($cartSummary['discount_amount'] > 0)
                            <div class="flex justify-between text-emerald-700">
                                <span>Promo Discount ({{ $cartSummary['coupon_code'] }})</span>
                                <span class="font-bold">- ₹{{ number_format($cartSummary['discount_amount']) }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between">
                            <span>Express Shipping</span>
                            <span class="font-medium text-zinc-900">{{ $cartSummary['shipping_amount'] == 0 ? 'Complimentary' : '₹' . $cartSummary['shipping_amount'] }}</span>
                        </div>

                        <div class="border-t border-zinc-200 pt-3 flex justify-between text-lg font-bold text-zinc-950">
                            <span>Total Payable</span>
                            <span>₹{{ number_format($cartSummary['total']) }}</span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-4 bg-[#0B0D10] hover:bg-black text-[#DFCAAB] text-xs font-bold uppercase tracking-[0.2em] rounded-xl text-center shadow-xl transition-all duration-200 hover:scale-[1.01] flex items-center justify-center space-x-2">
                        <span x-text="paymentMethod === 'cod' ? 'Place Cash on Delivery Order' : 'Proceed to Payment (₹{{ number_format($cartSummary['total']) }})'"></span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>

                    <div class="text-[11px] text-zinc-400 text-center space-y-1">
                        <p>By placing this order, you agree to JACARIO terms of service and complimentary return policies.</p>
                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection
