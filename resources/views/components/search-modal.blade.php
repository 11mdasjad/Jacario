<div x-show="searchOpen" 
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;"
     @keydown.window.escape="searchOpen = false">
    
    <div class="fixed inset-0 bg-black/70 backdrop-blur-md" @click="searchOpen = false"></div>

    <div class="relative min-h-screen flex items-start justify-center pt-20 px-4">
        <div class="relative max-w-2xl w-full bg-white rounded-2xl shadow-2xl overflow-hidden border border-zinc-200" @click.stop>
            
            <form action="{{ route('shop.index') }}" method="GET" class="relative">
                <div class="flex items-center px-6 py-4 border-b border-zinc-100">
                    <svg class="w-6 h-6 text-zinc-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" 
                           name="q" 
                           placeholder="Search Polo T-Shirts (e.g. Supima, Mulberry Silk, Obsidian, Slim Fit)..." 
                           class="w-full text-base font-medium text-zinc-900 placeholder-zinc-400 focus:outline-none bg-transparent"
                           autofocus>
                    <button type="button" @click="searchOpen = false" class="text-zinc-400 hover:text-zinc-900 ml-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </form>

            <div class="p-6 bg-zinc-50/50 space-y-4">
                <p class="text-[11px] font-bold uppercase tracking-widest text-zinc-400">Popular Searches</p>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('shop.index', ['category' => 'supima-luxury-polo']) }}" class="px-3 py-1.5 bg-white border border-zinc-200 hover:border-black rounded-lg text-xs font-medium text-zinc-700 transition-colors">
                        Supima Cotton
                    </a>
                    <a href="{{ route('shop.index', ['color' => ['obsidian-black']]) }}" class="px-3 py-1.5 bg-white border border-zinc-200 hover:border-black rounded-lg text-xs font-medium text-zinc-700 transition-colors">
                        Obsidian Black
                    </a>
                    <a href="{{ route('shop.index', ['category' => 'silk-cotton-blend-polo']) }}" class="px-3 py-1.5 bg-white border border-zinc-200 hover:border-black rounded-lg text-xs font-medium text-zinc-700 transition-colors">
                        Mulberry Silk Polo
                    </a>
                    <a href="{{ route('shop.index', ['category' => 'performance-knit-polo']) }}" class="px-3 py-1.5 bg-white border border-zinc-200 hover:border-black rounded-lg text-xs font-medium text-zinc-700 transition-colors">
                        Golf Performance
                    </a>
                    <a href="{{ route('shop.index', ['collection' => 'bestsellers']) }}" class="px-3 py-1.5 bg-white border border-zinc-200 hover:border-black rounded-lg text-xs font-medium text-zinc-700 transition-colors">
                        Best Sellers
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
