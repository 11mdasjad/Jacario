<div x-show="$store.cartDrawer.isOpen" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 overflow-hidden" 
     style="display: none;"
     @keydown.window.escape="$store.cartDrawer.close()">
    
    <!-- Backdrop Blur Overlay -->
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="$store.cartDrawer.close()"></div>

    <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
        <div x-show="$store.cartDrawer.isOpen"
             x-transition:enter="transform transition ease-out duration-300"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transform transition ease-in duration-200"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             class="w-screen max-w-md bg-white shadow-2xl flex flex-col justify-between">
            
            <!-- Drawer Header -->
            <div class="p-6 border-b border-zinc-100 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <h3 class="text-base font-serif-luxury font-bold tracking-wider text-zinc-900">Your Shopping Bag</h3>
                    <span class="text-xs font-semibold px-2 py-0.5 bg-zinc-100 text-zinc-700 rounded-full" x-text="$store.cartDrawer.cartData.item_count + ' items'"></span>
                </div>
                <button @click="$store.cartDrawer.close()" class="p-2 text-zinc-400 hover:text-zinc-900 transition-colors" aria-label="Close Bag">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Free Shipping Progress Bar -->
            <div class="px-6 py-3 bg-[#F4EFEA] border-b border-[#E8DFD5]">
                <div class="flex items-center justify-between text-xs mb-1.5">
                    <span class="font-medium text-zinc-800" x-show="$store.cartDrawer.cartData.remaining_for_free_shipping > 0">
                        Add <strong class="text-zinc-950" x-text="'₹' + Number($store.cartDrawer.cartData.remaining_for_free_shipping).toLocaleString('en-IN')"></strong> for Complimentary Express Shipping
                    </span>
                    <span class="font-semibold text-emerald-800 flex items-center space-x-1" x-show="$store.cartDrawer.cartData.remaining_for_free_shipping == 0">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span>You unlocked Free Express Shipping!</span>
                    </span>
                </div>
                <div class="w-full bg-zinc-200/80 rounded-full h-1.5 overflow-hidden">
                    <div class="bg-[#0B0D10] h-1.5 rounded-full transition-all duration-500" :style="'width: ' + $store.cartDrawer.cartData.free_shipping_progress + '%'"></div>
                </div>
            </div>

            <!-- Items List -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6">
                <!-- Empty State -->
                <div x-show="!$store.cartDrawer.cartData.items || $store.cartDrawer.cartData.items.length === 0" class="py-16 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-zinc-100 flex items-center justify-center text-zinc-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                    <h4 class="text-base font-serif-luxury font-bold text-zinc-900 mb-1">Your bag is empty</h4>
                    <p class="text-xs text-zinc-500 mb-6">Discover our bespoke collection of premium Polo T-Shirts.</p>
                    <a href="{{ route('shop.index') }}" @click="$store.cartDrawer.close()" class="inline-flex items-center justify-center px-6 py-2.5 bg-zinc-900 text-white rounded-lg text-xs font-semibold uppercase tracking-widest hover:bg-black transition-colors">
                        Explore Collection
                    </a>
                </div>

                <!-- Bag Items List -->
                <template x-for="item in $store.cartDrawer.cartData.items" :key="item.id">
                    <div class="flex space-x-4 pb-6 border-b border-zinc-100 last:border-0 last:pb-0">
                        <div class="w-20 h-24 bg-zinc-50 rounded-lg overflow-hidden flex-shrink-0 border border-zinc-100 flex items-center justify-center p-1">
                            <img :src="item.image_url" :alt="item.product_name" class="w-full h-full object-contain">
                        </div>
                        <div class="flex-1 min-w-0 flex flex-col justify-between">
                            <div>
                                <div class="flex items-start justify-between">
                                    <h4 class="text-xs font-bold text-zinc-900 truncate pr-2" x-text="item.product_name"></h4>
                                    <button @click="$store.cartDrawer.removeItem(item.id)" class="text-zinc-400 hover:text-rose-600 transition-colors" title="Remove item">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                                <div class="flex items-center space-x-2 text-[11px] text-zinc-500 mt-1">
                                    <span class="inline-flex items-center space-x-1">
                                        <span class="w-2.5 h-2.5 rounded-full border border-zinc-300 inline-block" :style="'background-color: ' + item.color_hex"></span>
                                        <span x-text="item.color"></span>
                                    </span>
                                    <span>•</span>
                                    <span>Size: <strong class="text-zinc-800" x-text="item.size"></strong></span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mt-3">
                                <!-- Quantity Selector -->
                                <div class="flex items-center border border-zinc-200 rounded-lg bg-zinc-50">
                                    <button @click="$store.cartDrawer.updateQuantity(item.id, item.quantity - 1)" class="w-7 h-7 flex items-center justify-center text-zinc-600 hover:text-black focus:outline-none" :disabled="item.quantity <= 1">
                                        -
                                    </button>
                                    <span class="w-8 text-center text-xs font-bold text-zinc-900" x-text="item.quantity"></span>
                                    <button @click="$store.cartDrawer.updateQuantity(item.id, item.quantity + 1)" class="w-7 h-7 flex items-center justify-center text-zinc-600 hover:text-black focus:outline-none" :disabled="item.quantity >= item.max_stock">
                                        +
                                    </button>
                                </div>

                                <div class="text-right">
                                    <span class="text-xs font-bold text-zinc-950" x-text="'₹' + Number(item.subtotal).toLocaleString('en-IN')"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Drawer Footer with Subtotal & Checkout CTA -->
            <div class="p-6 border-t border-zinc-100 bg-zinc-50/50 space-y-4" x-show="$store.cartDrawer.cartData.items && $store.cartDrawer.cartData.items.length > 0">
                <div class="space-y-1.5 text-xs text-zinc-600">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span class="font-bold text-zinc-900" x-text="'₹' + Number($store.cartDrawer.cartData.subtotal).toLocaleString('en-IN')"></span>
                    </div>
                    <div class="flex justify-between" x-show="$store.cartDrawer.cartData.discount_amount > 0">
                        <span class="text-emerald-700">Promo Discount</span>
                        <span class="font-bold text-emerald-700" x-text="'- ₹' + Number($store.cartDrawer.cartData.discount_amount).toLocaleString('en-IN')"></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Estimated Shipping</span>
                        <span class="font-medium" x-text="$store.cartDrawer.cartData.shipping_amount == 0 ? 'Complimentary' : '₹' + $store.cartDrawer.cartData.shipping_amount"></span>
                    </div>
                    <div class="border-t border-zinc-200 pt-2 flex justify-between text-sm font-bold text-zinc-900">
                        <span>Total</span>
                        <span x-text="'₹' + Number($store.cartDrawer.cartData.total).toLocaleString('en-IN')"></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('cart.index') }}" @click="$store.cartDrawer.close()" class="w-full text-center py-3 border border-zinc-300 hover:border-zinc-800 text-zinc-800 rounded-lg text-xs font-bold tracking-widest uppercase transition-colors">
                        View Bag
                    </a>
                    <a href="{{ route('checkout.index') }}" @click="$store.cartDrawer.close()" class="w-full text-center py-3 bg-[#0B0D10] hover:bg-black text-[#DFCAAB] rounded-lg text-xs font-bold tracking-widest uppercase shadow-md transition-colors flex items-center justify-center space-x-1">
                        <span>Checkout</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>

                <p class="text-[10px] text-center text-zinc-400">
                    Complimentary doorstep exchange & return guarantee
                </p>
            </div>

        </div>
    </div>
</div>
