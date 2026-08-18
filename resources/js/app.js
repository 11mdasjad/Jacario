import './bootstrap';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import persist from '@alpinejs/persist';

Alpine.plugin(collapse);
Alpine.plugin(persist);

// Global Toast Manager
window.toast = function (message, type = 'success') {
    window.dispatchEvent(new CustomEvent('notify', {
        detail: { message, type }
    }));
};

// Global Store Initializations
document.addEventListener('alpine:init', () => {
    
    // Global Navigation & Mobile Menu Store
    Alpine.store('nav', {
        mobileMenuOpen: false,
        searchOpen: false,
        quickMenuOpen: false,

        toggleMobile() {
            this.mobileMenuOpen = !this.mobileMenuOpen;
            document.body.style.overflow = this.mobileMenuOpen ? 'hidden' : '';
        },

        closeMobile() {
            this.mobileMenuOpen = false;
            document.body.style.overflow = '';
        },

        toggleQuickMenu() {
            this.quickMenuOpen = !this.quickMenuOpen;
        },

        closeQuickMenu() {
            this.quickMenuOpen = false;
        }
    });

    // Global Cart Drawer & State Manager
    Alpine.store('cartDrawer', {
        isOpen: false,
        isLoading: false,
        cartData: {
            items: [],
            item_count: 0,
            subtotal: 0,
            discount_amount: 0,
            shipping_amount: 0,
            total: 0,
            free_shipping_progress: 0,
            remaining_for_free_shipping: 0,
        },

        init() {
            this.fetchCart();
        },

        open() {
            this.isOpen = true;
            document.body.style.overflow = 'hidden';
        },

        close() {
            this.isOpen = false;
            document.body.style.overflow = '';
        },

        toggle() {
            if (this.isOpen) {
                this.close();
            } else {
                this.open();
            }
        },

        async fetchCart() {
            try {
                const response = await fetch('/api/cart/summary', {
                    headers: { 'Accept': 'application/json' }
                });
                if (response.ok) {
                    const data = await response.json();
                    this.cartData = data;
                    window.dispatchEvent(new CustomEvent('cart-updated', { detail: data }));
                }
            } catch (error) {
                console.error('Failed to load cart summary:', error);
            }
        },

        async addItem(variantId, quantity = 1) {
            const parsedId = parseInt(variantId, 10);
            if (!parsedId || isNaN(parsedId) || parsedId <= 0) {
                window.toast('Please select an available size / color before adding to bag.', 'error');
                return;
            }

            this.isLoading = true;
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const response = await fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ variant_id: parsedId, quantity: Math.max(1, parseInt(quantity, 10) || 1) })
                });

                const result = await response.json();
                if (result.success) {
                    this.cartData = result.cart;
                    this.open();
                    window.toast(result.message, 'success');
                    window.dispatchEvent(new CustomEvent('cart-updated', { detail: result.cart }));
                } else {
                    window.toast(result.message || 'Could not add to bag.', 'error');
                }
            } catch (err) {
                window.toast('Unable to add item to bag. Please refresh and try again.', 'error');
            } finally {
                this.isLoading = false;
            }
        },

        async updateQuantity(cartItemId, quantity) {
            this.isLoading = true;
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const response = await fetch('/cart/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ item_id: cartItemId, quantity })
                });

                const result = await response.json();
                if (result.success) {
                    this.cartData = result.cart;
                    window.dispatchEvent(new CustomEvent('cart-updated', { detail: result.cart }));
                } else {
                    window.toast(result.message || 'Unable to update quantity.', 'error');
                }
            } catch (err) {
                window.toast('Error updating quantity.', 'error');
            } finally {
                this.isLoading = false;
            }
        },

        async removeItem(cartItemId) {
            this.isLoading = true;
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const response = await fetch('/cart/remove', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ item_id: cartItemId })
                });

                const result = await response.json();
                if (result.success) {
                    this.cartData = result.cart;
                    window.toast(result.message, 'info');
                    window.dispatchEvent(new CustomEvent('cart-updated', { detail: result.cart }));
                }
            } catch (err) {
                window.toast('Error removing item.', 'error');
            } finally {
                this.isLoading = false;
            }
        }
    });

    // Wishlist Store
    Alpine.store('wishlist', {
        count: 0,

        async toggle(productId, buttonEl = null) {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const response = await fetch('/wishlist/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ product_id: productId })
                });

                if (response.status === 401) {
                    window.location.href = '/login?redirect=' + encodeURIComponent(window.location.pathname);
                    return;
                }

                const data = await response.json();
                if (data.success) {
                    this.count = data.wishlist_count;
                    window.toast(data.message, data.is_added ? 'success' : 'info');
                    if (buttonEl) {
                        buttonEl.classList.toggle('text-rose-600', data.is_added);
                        buttonEl.classList.toggle('fill-rose-600', data.is_added);
                    }
                    window.dispatchEvent(new CustomEvent('wishlist-updated', { detail: data }));
                }
            } catch (e) {
                window.toast('Please sign in to save items to your wishlist.', 'error');
            }
        }
    });
});

window.Alpine = Alpine;
Alpine.start();
