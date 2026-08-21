<div x-data="{
    query: '',
    results: [],
    loading: false,
    timer: null,

    handleInput() {
        clearTimeout(this.timer);
        if (this.query.trim().length < 2) {
            this.results = [];
            this.loading = false;
            return;
        }
        this.loading = true;
        this.timer = setTimeout(() => {
            fetch('/shop?q=' + encodeURIComponent(this.query), {
                headers: { 'Accept': 'text/html' }
            })
            .then(res => res.text())
            .then(html => {
                this.loading = false;
            })
            .catch(() => { this.loading = false; });
        }, 300);
    }
}"
x-show="$store.nav.searchOpen" 
x-transition:enter="transition ease-out duration-200"
x-transition:enter-start="opacity-0"
x-transition:enter-end="opacity-100"
x-transition:leave="transition ease-in duration-150"
x-transition:leave-start="opacity-100"
x-transition:leave-end="opacity-0"
class="fixed inset-0 z-50 overflow-y-auto"
style="display: none;"
@keydown.window.escape="$store.nav.searchOpen = false">
    
    <!-- Backdrop Blur -->
    <div class="fixed inset-0 bg-black/75 backdrop-blur-md" @click="$store.nav.searchOpen = false"></div>

    <div class="relative min-h-screen flex items-start justify-center pt-8 sm:pt-20 px-3 sm:px-4">
        <div class="relative max-w-2xl w-full bg-white rounded-3xl shadow-2xl overflow-hidden border border-zinc-200" @click.stop>
            
            <!-- Search Input Header -->
            <form action="{{ route('shop.index') }}" method="GET" class="relative">
                <div class="flex items-center px-4 sm:px-6 py-4 sm:py-5 border-b border-zinc-100">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-zinc-400 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    
                    <input type="text" 
                           name="q" 
                           x-model="query"
                           @input="handleInput()"
                           placeholder="Search Polo T-Shirts (e.g. Supima, Mulberry Silk, Obsidian, Slim Fit)..." 
                           class="w-full text-sm sm:text-base font-medium text-zinc-900 placeholder-zinc-400 focus:outline-none bg-transparent"
                           autofocus>

                    <button type="button" @click="$store.nav.searchOpen = false" class="p-1 text-zinc-400 hover:text-zinc-900 ml-2 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </form>

            <!-- Popular Searches & Quick Filters -->
            <div class="p-5 sm:p-6 bg-zinc-50/70 space-y-4">
                <div>
                    <p class="text-[10px] sm:text-[11px] font-bold uppercase tracking-widest text-[#8C6D46] mb-2.5">Trending Searches</p>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('shop.index', ['q' => 'Supima']) }}" class="px-3 py-1.5 bg-white border border-zinc-200 hover:border-black rounded-xl text-xs font-semibold text-zinc-800 transition-colors shadow-2xs">
                            Supima® Cotton
                        </a>
                        <a href="{{ route('shop.index', ['q' => 'Silk']) }}" class="px-3 py-1.5 bg-white border border-zinc-200 hover:border-black rounded-xl text-xs font-semibold text-zinc-800 transition-colors shadow-2xs">
                            Mulberry Silk
                        </a>
                        <a href="{{ route('shop.index', ['q' => 'Obsidian']) }}" class="px-3 py-1.5 bg-white border border-zinc-200 hover:border-black rounded-xl text-xs font-semibold text-zinc-800 transition-colors shadow-2xs">
                            Obsidian Black
                        </a>
                        <a href="{{ route('shop.index', ['collection' => 'bestsellers']) }}" class="px-3 py-1.5 bg-white border border-zinc-200 hover:border-black rounded-xl text-xs font-semibold text-zinc-800 transition-colors shadow-2xs">
                            Best Sellers
                        </a>
                        <a href="{{ route('shop.index', ['collection' => 'new-arrivals']) }}" class="px-3 py-1.5 bg-white border border-zinc-200 hover:border-black rounded-xl text-xs font-semibold text-zinc-800 transition-colors shadow-2xs">
                            New Season Drops
                        </a>
                        <a href="{{ route('shop.index', ['fit' => ['Slim Fit']]) }}" class="px-3 py-1.5 bg-white border border-zinc-200 hover:border-black rounded-xl text-xs font-semibold text-zinc-800 transition-colors shadow-2xs">
                            Slim Fit Polos
                        </a>
                    </div>
                </div>

                <div class="pt-3 border-t border-zinc-200/80 flex items-center justify-between text-xs text-zinc-500">
                    <span>Press <kbd class="px-1.5 py-0.5 text-[10px] font-mono bg-zinc-200 rounded text-zinc-800 font-bold">ENTER</kbd> to view full catalog search</span>
                    <a href="{{ route('shop.index') }}" class="font-bold text-zinc-900 hover:text-[#8C6D46]">Browse All 50 Polos →</a>
                </div>
            </div>

        </div>
    </div>
</div>
