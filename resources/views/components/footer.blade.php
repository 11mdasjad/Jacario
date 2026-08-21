<footer class="bg-[#0B0D10] text-zinc-400 pt-16 pb-12 border-t border-zinc-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Main Footer Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-zinc-800/80">
            
            <!-- Brand Column -->
            <div class="lg:col-span-2 space-y-4">
                <a href="{{ route('home') }}" class="inline-block focus:outline-none">
                    <img src="{{ asset('images/logo.png') }}" alt="JACARIO" class="h-9 sm:h-11 w-auto object-contain brightness-0 invert opacity-90 hover:opacity-100 transition-opacity">
                </a>
                <p class="text-xs uppercase tracking-widest text-[#C5A880] font-semibold">
                    The Polo & T-Shirt Atelier
                </p>
                <p class="text-sm text-zinc-400 max-w-sm leading-relaxed">
                    Exclusively dedicated to the art of luxury Polo & Round Neck T-Shirts. Engineered with long-staple Supima® cotton, Mulberry silk blends, and meticulous sartorial craftsmanship.
                </p>
                
                <!-- Social Links -->
                <div class="flex items-center space-x-4 pt-2">
                    <a href="https://instagram.com" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-zinc-900 border border-zinc-800 flex items-center justify-center text-zinc-400 hover:text-white hover:border-[#C5A880] transition-colors" aria-label="Instagram">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="https://twitter.com" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-zinc-900 border border-zinc-800 flex items-center justify-center text-zinc-400 hover:text-white hover:border-[#C5A880] transition-colors" aria-label="Twitter / X">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Categories Links (3 Distinct Categories) -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-[0.2em] text-white">Collections</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('shop.index', ['category' => 'mens-polo-t-shirts']) }}" class="hover:text-white transition-colors">Men's Polo T-Shirts</a></li>
                    <li><a href="{{ route('shop.index', ['category' => 'round-neck-t-shirts']) }}" class="hover:text-white transition-colors">Round Neck T-Shirts</a></li>
                    <li><a href="{{ route('shop.index', ['category' => 'new-arrival-t-shirts']) }}" class="hover:text-white transition-colors">New Arrival T-Shirts</a></li>
                    <li><a href="{{ route('shop.index') }}" class="hover:text-white transition-colors">All Collections</a></li>
                </ul>
            </div>

            <!-- Concierge & Care -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-[0.2em] text-white">Client Concierge</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('orders.track') }}" class="hover:text-white transition-colors">Track Your Order</a></li>
                    <li><a href="{{ route('shipping') }}" class="hover:text-white transition-colors">Shipping & Delivery</a></li>
                    <li><a href="{{ route('returns') }}" class="hover:text-white transition-colors">Returns & Exchanges</a></li>
                    <li><a href="{{ route('faqs') }}" class="hover:text-white transition-colors">Frequently Asked Questions</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-white transition-colors">Contact Concierge</a></li>
                </ul>
            </div>

            <!-- Legal & Company -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-[0.2em] text-white">The Maison</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">About JACARIO</a></li>
                    <li><a href="{{ route('privacy') }}" class="hover:text-white transition-colors">Privacy Policy</a></li>
                    <li><a href="{{ route('terms') }}" class="hover:text-white transition-colors">Terms of Service</a></li>
                    <li><a href="{{ route('sitemap') }}" class="hover:text-white transition-colors">XML Sitemap</a></li>
                </ul>
            </div>

        </div>

        <!-- Bottom Bar -->
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-zinc-500 space-y-4 sm:space-y-0">
            <p>© {{ date('Y') }} JACARIO Luxury Apparel. All rights reserved. Men's Polo & Round Neck T-Shirts.</p>
            
            <div class="flex items-center space-x-6 text-zinc-400">
                <span class="flex items-center space-x-1.5"><svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg> <span>256-Bit SSL Encrypted</span></span>
                <span class="flex items-center space-x-1.5"><svg class="w-4 h-4 text-[#C5A880]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> <span>100% Certified Authentic</span></span>
            </div>
        </div>

    </div>
</footer>
