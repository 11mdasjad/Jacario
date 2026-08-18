@extends('layouts.app')

@section('title', 'Your Shopping Bag | JACARIO')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ couponInput: '' }">
    
    <h1 class="text-3xl font-serif-luxury font-bold text-zinc-900 tracking-tight mb-8">
        Your Shopping Bag
    </h1>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        <!-- Cart Items Table / List (8 Cols) -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- Empty State -->
            <template x-if="!$store.cartDrawer.cartData.items || $store.cartDrawer.cartData.items.length === 0">
                <div class="p-12 text-center bg-white rounded-2xl border border-zinc-200 shadow-sm">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-zinc-100 flex items-center justify-center text-zinc-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                    <h3 class="text-lg font-serif-luxury font-bold text-zinc-900 mb-1">Your bag is presently empty</h3>
                    <p class="text-xs text-zinc-500 mb-6">Explore our curated collection of luxury Polo T-Shirts.</p>
                    <a href="{{ route('shop.index') }}" class="inline-flex items-center px-8 py-3 bg-[#0B0D10] text-[#DFCAAB] rounded-lg text-xs font-bold uppercase tracking-widest hover:bg-black transition-colors">
                        Explore Collection
                    </a>
                </div>
            </template>

            <!-- Free Shipping Progress -->
            <div class="p-4 bg-[#F4EFEA] rounded-xl border border-[#E5DCD0]" x-show="$store.cartDrawer.cartData.items && $store.cartDrawer.cartData.items.length > 0">
                <div class="flex items-center justify-between text-xs mb-2">
                    <span class="font-medium text-zinc-800" x-show="$store.cartDrawer.cartData.remaining_for_free_shipping > 0">
                        Add <strong class="text-zinc-950" x-text="'₹' + Number($store.cartDrawer.cartData.remaining_for_free_shipping).toLocaleString('en-IN')"></strong> more for Complimentary Express Shipping
                    </span>
                    <span class="font-bold text-emerald-800 flex items-center space-x-1" x-show="$store.cartDrawer.cartData.remaining_for_free_shipping == 0">
                        <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span>You have unlocked Complimentary Express Shipping!</span>
                    </span>
                </div>
                <div class="w-full bg-zinc-200/80 rounded-full h-2 overflow-hidden">
                    <div class="bg-[#0B0D10] h-2 rounded-full transition-all duration-500" :style="'width: ' + $store.cartDrawer.cartData.free_shipping_progress + '%'"></div>
                </div>
            </div>

            <!-- Items List -->
            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm divide-y divide-zinc-100 overflow-hidden" x-show="$store.cartDrawer.cartData.items && $store.cartDrawer.cartData.items.length > 0">
                <template x-for="item in $store.cartDrawer.cartData.items" :key="item.id">
                    <div class="p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                        
                        <div class="flex items-center space-x-4">
                            <a :href="'/products/' + item.slug" class="w-20 h-24 bg-zinc-50 rounded-xl border border-zinc-100 p-2 flex-shrink-0 flex items-center justify-center">
                                <img :src="item.image_url" :alt="item.product_name" class="w-full h-full object-contain">
                            </a>

                            <div class="space-y-1">
                                <h4 class="text-sm font-bold text-zinc-900">
                                    <a :href="'/products/' + item.slug" x-text="item.product_name" class="hover:text-[#A4845B] transition-colors"></a>
                                </h4>
                                <div class="flex items-center space-x-3 text-xs text-zinc-500">
                                    <span class="inline-flex items-center space-x-1">
                                        <span class="w-2.5 h-2.5 rounded-full border border-zinc-300 inline-block" :style="'background-color: ' + item.color_hex"></span>
                                        <span x-text="item.color"></span>
                                    </span>
                                    <span>•</span>
                                    <span>Size: <strong class="text-zinc-800" x-text="item.size"></strong></span>
                                </div>
                                <p class="text-xs text-zinc-400 font-mono" x-text="'SKU: ' + item.sku"></p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between w-full sm:w-auto sm:space-x-8">
                            
                            <!-- Quantity Controls -->
                            <div class="flex items-center border border-zinc-200 rounded-lg bg-zinc-50">
                                <button type="button" @click="$store.cartDrawer.updateQuantity(item.id, item.quantity - 1)" class="w-8 h-8 flex items-center justify-center text-zinc-600 hover:text-black font-bold focus:outline-none" :disabled="item.quantity <= 1">-</button>
                                <span class="w-8 text-center text-xs font-bold text-zinc-900" x-text="item.quantity"></span>
                                <button type="button" @click="$store.cartDrawer.updateQuantity(item.id, item.quantity + 1)" class="w-8 h-8 flex items-center justify-center text-zinc-600 hover:text-black font-bold focus:outline-none" :disabled="item.quantity >= item.max_stock">+</button>
                            </div>

                            <!-- Price -->
                            <div class="text-right">
                                <p class="text-sm font-bold text-zinc-950" x-text="'₹' + Number(item.subtotal).toLocaleString('en-IN')"></p>
                                <p class="text-[10px] text-zinc-400" x-text="'₹' + Number(item.unit_price).toLocaleString('en-IN') + ' each'"></p>
                            </div>

                            <!-- Remove Button -->
                            <button type="button" @click="$store.cartDrawer.removeItem(item.id)" class="text-zinc-400 hover:text-rose-600 transition-colors p-1" title="Remove Item">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>

                        </div>

                    </div>
                </template>
            </div>

        </div>

        <!-- Order Summary Sidebar (4 Cols) -->
        <div class="lg:col-span-4 space-y-6" x-show="$store.cartDrawer.cartData.items && $store.cartDrawer.cartData.items.length > 0">
            
            <div class="p-6 bg-white rounded-2xl border border-zinc-200 shadow-sm space-y-6">
                <h3 class="text-base font-serif-luxury font-bold text-zinc-900 pb-4 border-b border-zinc-100">
                    Order Summary
                </h3>

                <!-- Promo Coupon Form -->
                <div>
                    <template x-if="!$store.cartDrawer.cartData.coupon_code">
                        <div class="flex space-x-2">
                            <input type="text" 
                                   x-model="couponInput" 
                                   placeholder="Promo Code (e.g. FIRSTPOLO)" 
                                   class="flex-1 uppercase text-xs bg-zinc-50 border border-zinc-200 rounded-lg px-3 py-2.5 focus:outline-none focus:border-black font-mono">
                            <button type="button" 
                                    @click="
                                        fetch('{{ route('cart.coupon.apply') }}', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                'Accept': 'application/json'
                                            },
                                            body: JSON.stringify({ code: couponInput })
                                        })
                                        .then(res => res.json())
                                        .then(data => {
                                            if (data.success) {
                                                $store.cartDrawer.cartData = data.cart;
                                                window.toast(data.message, 'success');
                                                couponInput = '';
                                            } else {
                                                window.toast(data.message, 'error');
                                            }
                                        });
                                    " 
                                    class="px-4 py-2.5 bg-zinc-900 hover:bg-black text-white text-xs font-bold uppercase tracking-wider rounded-lg transition-colors">
                                Apply
                            </button>
                        </div>
                    </template>

                    <template x-if="$store.cartDrawer.cartData.coupon_code">
                        <div class="flex items-center justify-between p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-xs">
                            <div class="flex items-center space-x-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span class="font-mono font-bold text-emerald-800" x-text="$store.cartDrawer.cartData.coupon_code"></span>
                                <span class="text-emerald-700">applied</span>
                            </div>
                            <button type="button" 
                                    @click="
                                        fetch('{{ route('cart.coupon.remove') }}', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                'Accept': 'application/json'
                                            }
                                        })
                                        .then(res => res.json())
                                        .then(data => {
                                            $store.cartDrawer.cartData = data.cart;
                                            window.toast('Coupon removed', 'info');
                                        });
                                    " 
                                    class="text-xs font-bold text-rose-600 hover:underline">
                                Remove
                            </button>
                        </div>
                    </template>
                </div>

                <!-- Financial Breakdown -->
                <div class="space-y-3 text-xs text-zinc-600 pt-2 border-t border-zinc-100">
                    <div class="flex justify-between">
                        <span>Bag Subtotal</span>
                        <span class="font-bold text-zinc-900" x-text="'₹' + Number($store.cartDrawer.cartData.subtotal).toLocaleString('en-IN')"></span>
                    </div>

                    <div class="flex justify-between" x-show="$store.cartDrawer.cartData.discount_amount > 0">
                        <span class="text-emerald-700">Promo Discount</span>
                        <span class="font-bold text-emerald-700" x-text="'- ₹' + Number($store.cartDrawer.cartData.discount_amount).toLocaleString('en-IN')"></span>
                    </div>

                    <div class="flex justify-between">
                        <span>Express Delivery</span>
                        <span class="font-medium text-zinc-900" x-text="$store.cartDrawer.cartData.shipping_amount == 0 ? 'Complimentary' : '₹' + $store.cartDrawer.cartData.shipping_amount"></span>
                    </div>

                    <div class="border-t border-zinc-200 pt-3 flex justify-between text-base font-bold text-zinc-950">
                        <span>Grand Total</span>
                        <span x-text="'₹' + Number($store.cartDrawer.cartData.total).toLocaleString('en-IN')"></span>
                    </div>
                </div>

                <!-- Proceed to Checkout Button -->
                <a href="{{ route('checkout.index') }}" class="w-full py-4 bg-[#0B0D10] hover:bg-black text-[#DFCAAB] text-xs font-bold uppercase tracking-[0.2em] rounded-xl text-center shadow-lg transition-all flex items-center justify-center space-x-2">
                    <span>Proceed to Checkout</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>

                <div class="space-y-2 pt-2 text-[11px] text-zinc-500 text-center">
                    <p class="flex items-center justify-center space-x-1">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span>100% Encrypted & Authenticated Transaction</span>
                    </p>
                    <p>Complimentary 15-day doorstep size exchanges</p>
                </div>

            </div>

        </div>

    </div>

    <!-- Myntra-Style Mobile Sticky Bottom Checkout Bar -->
    <div class="fixed bottom-0 inset-x-0 bg-white border-t border-zinc-200 px-4 py-3 z-40 lg:hidden shadow-[0_-4px_20px_rgba(0,0,0,0.12)] flex items-center justify-between pb-safe"
         x-show="$store.cartDrawer.cartData.items && $store.cartDrawer.cartData.items.length > 0">
        <div>
            <span class="text-[10px] text-zinc-500 block uppercase tracking-wider font-semibold">Total Amount</span>
            <span class="text-base font-bold text-zinc-950" x-text="'₹' + Number($store.cartDrawer.cartData.total).toLocaleString('en-IN')"></span>
        </div>
        <a href="{{ route('checkout.index') }}" 
           class="px-6 py-3 bg-[#0B0D10] text-[#DFCAAB] text-xs font-bold uppercase tracking-wider rounded-xl shadow-md flex items-center space-x-1.5 active:scale-95 transition-all">
            <span>Place Order</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </a>
    </div>

</div>

@endsection
