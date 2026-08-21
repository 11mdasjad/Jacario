<!-- Global Quick View Modal -->
<div x-data="{
    open: false,
    product: null,
    selectedSizeId: null,
    selectedColorId: null,
    selectedImage: '',
    quantity: 1,
    loading: false,

    init() {
        window.addEventListener('open-quick-view', (e) => {
            this.product = e.detail;
            this.selectedImage = this.product.image;
            this.selectedSizeId = this.product.sizes[0] ? this.product.sizes[0].id : null;
            this.selectedColorId = this.product.colors[0] ? this.product.colors[0].id : null;
            this.quantity = 1;
            this.open = true;
            document.body.style.overflow = 'hidden';
        });
    },
    close() {
        this.open = false;
        document.body.style.overflow = '';
    },
    getSelectedVariantId() {
        if (!this.product || !this.product.variants) return null;
        const found = this.product.variants.find(v => v.size_id == this.selectedSizeId);
        return found ? found.id : (this.product.variants[0] ? this.product.variants[0].id : null);
    },
    addToCart() {
        const varId = this.getSelectedVariantId();
        if (!varId) {
            window.toast('Please select an available size.', 'error');
            return;
        }
        $store.cartDrawer.addItem(varId, this.quantity);
        this.close();
    }
}"
x-show="open" 
x-transition:enter="transition ease-out duration-300"
x-transition:enter-start="opacity-0"
x-transition:enter-end="opacity-100"
x-transition:leave="transition ease-in duration-200"
x-transition:leave-start="opacity-100"
x-transition:leave-end="opacity-0"
class="fixed inset-0 z-50 overflow-y-auto"
style="display: none;"
@keydown.window.escape="close()">

    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/70 backdrop-blur-sm" @click="close()"></div>

    <div class="relative min-h-screen flex items-center justify-center p-3 sm:p-4">
        <div class="relative max-w-2xl w-full bg-white rounded-3xl shadow-2xl overflow-hidden border border-zinc-200" @click.stop x-show="product">
            
            <!-- Close Button -->
            <button @click="close()" type="button" class="absolute top-4 right-4 z-20 w-8 h-8 rounded-full bg-white/90 backdrop-blur-md border border-zinc-200 flex items-center justify-center text-zinc-600 hover:text-black transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <template x-if="product">
                <div class="grid grid-cols-1 sm:grid-cols-2">
                    
                    <!-- Left: Product Image Stage -->
                    <div class="relative aspect-[4/5] bg-zinc-100 overflow-hidden">
                        <img :src="selectedImage" :alt="product.name" class="w-full h-full object-cover">
                        
                        <!-- Badges -->
                        <div class="absolute top-3 left-3 flex flex-col space-y-1">
                            <template x-if="product.has_discount">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-[#0B0D10] text-[#DFCAAB] border border-zinc-700 shadow-xs" x-text="product.discount_percent + '% OFF'"></span>
                            </template>
                        </div>
                    </div>

                    <!-- Right: Product Details & Size Picker -->
                    <div class="p-6 sm:p-8 flex flex-col justify-between space-y-4">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-[#8C6D46]" x-text="product.category_name"></span>
                                <span class="text-xs font-bold text-zinc-500">★ <span x-text="product.rating"></span></span>
                            </div>

                            <h3 class="text-lg sm:text-xl font-serif-luxury font-bold text-zinc-950 leading-snug" x-text="product.name"></h3>

                            <!-- Pricing -->
                            <div class="flex items-baseline space-x-2">
                                <span class="text-lg font-bold text-zinc-950" x-text="'₹' + product.effective_price"></span>
                                <template x-if="product.has_discount">
                                    <span class="text-xs text-zinc-400 line-through" x-text="'₹' + product.base_price"></span>
                                </template>
                            </div>

                            <p class="text-xs text-zinc-600 font-light leading-relaxed line-clamp-3" x-text="product.description"></p>

                            <!-- Size Selector -->
                            <div class="pt-2">
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-zinc-800">Select Size:</span>
                                </div>
                                <div class="grid grid-cols-5 gap-1.5">
                                    <template x-for="sz in product.sizes" :key="sz.id">
                                        <button type="button" 
                                                @click="selectedSizeId = sz.id"
                                                :class="selectedSizeId == sz.id ? 'bg-zinc-950 text-white border-zinc-950' : 'bg-zinc-50 text-zinc-800 border-zinc-200 hover:border-black'"
                                                class="py-2 text-xs font-bold rounded-xl border transition-all text-center"
                                                x-text="sz.code">
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="space-y-2 pt-2">
                            <button type="button" 
                                    @click="addToCart()" 
                                    class="w-full py-3 bg-[#0B0D10] hover:bg-black text-[#DFCAAB] text-xs font-bold uppercase tracking-widest rounded-xl transition-all shadow-md flex items-center justify-center space-x-2 active:scale-98">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                <span>Add to Bag</span>
                            </button>

                            <a :href="'/products/' + product.slug" class="block text-center py-2 text-xs font-semibold text-zinc-600 hover:text-black uppercase tracking-wider transition-colors">
                                View Full Product Details →
                            </a>
                        </div>

                    </div>
                </div>
            </template>

        </div>
    </div>
</div>
