@extends('layouts.app')

@section('title', 'Shop Polo T-Shirts | JACARIO Haute Apparel')
@section('meta_description', 'Explore JACARIO\'s comprehensive collection of luxury Polo T-Shirts. Filter by style, size, color, fit, and premium cotton-silk fabrics.')

@section('content')

    <!-- Breadcrumb & Header Banner -->
    <div class="bg-gradient-to-r from-[#F7F4EE] via-[#FAF8F5] to-[#F5F2EC] text-zinc-900 py-12 border-b border-zinc-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center space-x-2 text-xs text-zinc-500 mb-4">
                <a href="{{ route('home') }}" class="hover:text-black transition-colors">Home</a>
                <span>/</span>
                <span class="text-[#8C6D46] font-bold">Collection</span>
            </nav>

            <div class="flex flex-col md:flex-row md:items-end justify-between">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-serif-luxury font-bold text-zinc-950 tracking-tight">
                        @if(request('collection') === 'bestsellers')
                            Best Selling T-Shirts
                        @elseif(request('collection') === 'new-arrivals')
                            New Season Arrivals
                        @elseif(request('category'))
                            {{ $categories->firstWhere('slug', request('category'))->name ?? 'The Collection' }}
                        @elseif(request('q'))
                            Search Results for "{{ request('q') }}"
                        @else
                            The Polo Collection
                        @endif
                    </h1>
                    <p class="text-xs sm:text-sm text-zinc-600 font-light mt-1 max-w-xl">
                        Meticulously tailored from 100% Supima® cotton, Mulberry silk blends, and engineered athletic knit.
                    </p>
                </div>
                <p class="text-xs text-zinc-500 mt-4 md:mt-0 font-medium">
                    Showing <strong class="text-zinc-900">{{ $products->total() }}</strong> bespoke luxury silhouettes
                </p>
            </div>
        </div>
    </div>

    <!-- Main Catalog Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ mobileFiltersOpen: false }">
        
        <!-- Controls Bar: Mobile Filter Button, Sorting & Active Pills -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-zinc-200 gap-4">
            
            <!-- Mobile Filter Trigger -->
            <button @click="mobileFiltersOpen = true" type="button" class="lg:hidden inline-flex items-center justify-center space-x-2 px-4 py-2.5 bg-zinc-900 text-white rounded-lg text-xs font-bold uppercase tracking-wider">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                <span>Filter Polos</span>
            </button>

            <!-- Active Filters Quick Bar -->
            <div class="flex flex-wrap items-center gap-2">
                @if(request('q') || request('category') || request('fit') || request('size') || request('color') || request('min_price') || request('max_price') || request('collection'))
                    <span class="text-xs text-zinc-500 font-medium">Active Filters:</span>
                    
                    @if(request('q'))
                        <span class="inline-flex items-center space-x-1 px-2.5 py-1 bg-zinc-200 text-zinc-800 rounded-full text-xs font-medium">
                            <span>"{{ request('q') }}"</span>
                            <a href="{{ request()->fullUrlWithQuery(['q' => null]) }}" class="hover:text-black">✕</a>
                        </span>
                    @endif

                    @if(request('category'))
                        <span class="inline-flex items-center space-x-1 px-2.5 py-1 bg-zinc-200 text-zinc-800 rounded-full text-xs font-medium">
                            <span>{{ $categories->firstWhere('slug', request('category'))->name ?? request('category') }}</span>
                            <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="hover:text-black">✕</a>
                        </span>
                    @endif

                    @if(request('collection'))
                        <span class="inline-flex items-center space-x-1 px-2.5 py-1 bg-[#0B0D10] text-[#DFCAAB] rounded-full text-xs font-medium">
                            <span>{{ ucfirst(str_replace('-', ' ', request('collection'))) }}</span>
                            <a href="{{ request()->fullUrlWithQuery(['collection' => null]) }}" class="hover:text-white">✕</a>
                        </span>
                    @endif

                    <a href="{{ route('shop.index') }}" class="text-xs text-rose-600 hover:underline font-semibold ml-2">Clear All</a>
                @endif
            </div>

            <!-- Sorting Select Dropdown -->
            <div class="flex items-center space-x-2 self-end sm:self-auto">
                <label for="sort" class="text-xs text-zinc-500 font-medium whitespace-nowrap">Sort By:</label>
                <form method="GET" action="{{ route('shop.index') }}" id="sortForm">
                    @foreach(request()->except('sort', 'page') as $key => $val)
                        @if(is_array($val))
                            @foreach($val as $subVal)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $subVal }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                        @endif
                    @endforeach

                    <select name="sort" onchange="document.getElementById('sortForm').submit()" class="text-xs font-semibold text-zinc-800 bg-white border border-zinc-200 rounded-lg px-3 py-2 focus:outline-none focus:border-black cursor-pointer">
                        <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Featured</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="bestselling" {{ request('sort') == 'bestselling' ? 'selected' : '' }}>Best Selling</option>
                        <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 pt-8">
            
            <!-- Desktop Filters Sidebar -->
            <aside class="hidden lg:block space-y-8">
                <form method="GET" action="{{ route('shop.index') }}">
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif

                    <!-- Search Input -->
                    <div class="pb-6 border-b border-zinc-200">
                        <h4 class="text-xs font-bold uppercase tracking-widest text-zinc-900 mb-3">Search</h4>
                        <div class="relative">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Keyword or fabric..." class="w-full text-xs bg-white border border-zinc-200 rounded-lg pl-3 pr-8 py-2 focus:outline-none focus:border-black">
                            <button type="submit" class="absolute right-2.5 top-2.5 text-zinc-400 hover:text-black">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Category / Style -->
                    <div class="py-6 border-b border-zinc-200 space-y-3">
                        <h4 class="text-xs font-bold uppercase tracking-widest text-zinc-900">Collection Style</h4>
                        <div class="space-y-2">
                            @foreach($categories as $category)
                                <label class="flex items-center space-x-2 text-xs text-zinc-700 hover:text-black cursor-pointer">
                                    <input type="radio" name="category" value="{{ $category->slug }}" {{ request('category') === $category->slug ? 'checked' : '' }} onchange="this.form.submit()" class="text-zinc-900 focus:ring-black">
                                    <span>{{ $category->name }}</span>
                                    <span class="text-zinc-400 text-[10px]">({{ $category->products_count }})</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Size Filter -->
                    <div class="py-6 border-b border-zinc-200 space-y-3">
                        <h4 class="text-xs font-bold uppercase tracking-widest text-zinc-900">Size</h4>
                        <div class="grid grid-cols-5 gap-1.5">
                            @foreach($sizes as $size)
                                @php $isSizeActive = in_array($size->code, (array) request('size', [])); @endphp
                                <label class="text-center py-2 text-xs font-bold rounded-lg border cursor-pointer transition-colors {{ $isSizeActive ? 'bg-zinc-900 text-white border-zinc-900' : 'bg-white text-zinc-700 border-zinc-200 hover:border-black' }}">
                                    <input type="checkbox" name="size[]" value="{{ $size->code }}" {{ $isSizeActive ? 'checked' : '' }} onchange="this.form.submit()" class="sr-only">
                                    <span>{{ $size->code }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Color Filter -->
                    <div class="py-6 border-b border-zinc-200 space-y-3">
                        <h4 class="text-xs font-bold uppercase tracking-widest text-zinc-900">Color Palette</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($colors as $color)
                                @php $isColorActive = in_array($color->slug, (array) request('color', [])); @endphp
                                <label class="relative cursor-pointer group" title="{{ $color->name }}">
                                    <input type="checkbox" name="color[]" value="{{ $color->slug }}" {{ $isColorActive ? 'checked' : '' }} onchange="this.form.submit()" class="sr-only">
                                    <span class="w-6 h-6 rounded-full border border-zinc-300 block transition-transform group-hover:scale-110 {{ $isColorActive ? 'ring-2 ring-black ring-offset-2' : '' }}" style="background-color: {{ $color->hex_code }}"></span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Fit Filter -->
                    <div class="py-6 border-b border-zinc-200 space-y-3">
                        <h4 class="text-xs font-bold uppercase tracking-widest text-zinc-900">Fit Silhouette</h4>
                        <div class="space-y-2">
                            @foreach($fits as $fit)
                                @php $isFitActive = in_array($fit, (array) request('fit', [])); @endphp
                                <label class="flex items-center space-x-2 text-xs text-zinc-700 hover:text-black cursor-pointer">
                                    <input type="checkbox" name="fit[]" value="{{ $fit }}" {{ $isFitActive ? 'checked' : '' }} onchange="this.form.submit()" class="rounded border-zinc-300 text-zinc-900 focus:ring-black">
                                    <span>{{ $fit }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="py-6 border-b border-zinc-200 space-y-3">
                        <h4 class="text-xs font-bold uppercase tracking-widest text-zinc-900">Price Range (₹)</h4>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min ₹" class="w-full text-xs bg-white border border-zinc-200 rounded-lg p-2 focus:outline-none focus:border-black">
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max ₹" class="w-full text-xs bg-white border border-zinc-200 rounded-lg p-2 focus:outline-none focus:border-black">
                        </div>
                        <button type="submit" class="w-full py-2 bg-zinc-100 hover:bg-zinc-200 text-zinc-800 rounded-lg text-xs font-bold uppercase tracking-wider transition-colors">
                            Apply Price
                        </button>
                    </div>

                    <!-- Availability -->
                    <div class="pt-6 space-y-2">
                        <label class="flex items-center space-x-2 text-xs text-zinc-700 hover:text-black cursor-pointer">
                            <input type="checkbox" name="in_stock" value="1" {{ request('in_stock') ? 'checked' : '' }} onchange="this.form.submit()" class="rounded border-zinc-300 text-zinc-900 focus:ring-black">
                            <span>In Stock Only</span>
                        </label>
                    </div>
                </form>
            </aside>

            <!-- Products Grid Area -->
            <div class="lg:col-span-3">
                
                @if($products->isEmpty())
                    <!-- Empty State -->
                    <div class="py-20 text-center bg-white rounded-2xl border border-zinc-200 p-12">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-zinc-100 flex items-center justify-center text-zinc-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <h3 class="text-lg font-serif-luxury font-bold text-zinc-900 mb-2">No matching Polo T-Shirts found</h3>
                        <p class="text-xs text-zinc-500 max-w-sm mx-auto mb-6">
                            Try adjusting your filters or searching for alternative polo styles and colors.
                        </p>
                        <a href="{{ route('shop.index') }}" class="inline-flex items-center px-6 py-2.5 bg-zinc-900 text-white rounded-lg text-xs font-bold uppercase tracking-widest hover:bg-black transition-colors">
                            Reset All Filters
                        </a>
                    </div>
                @else
                    <!-- Products Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-12">
                        {{ $products->links() }}
                    </div>
                @endif

            </div>

        </div>

    </div>

    <!-- Mobile Filters Slide-over Modal -->
    <div x-show="mobileFiltersOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-hidden lg:hidden"
         style="display: none;">
        
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="mobileFiltersOpen = false"></div>

        <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
            <div class="w-screen max-w-sm bg-white shadow-2xl flex flex-col justify-between p-6 overflow-y-auto">
                <div class="flex items-center justify-between pb-4 border-b border-zinc-100">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-zinc-900">Filter Polo T-Shirts</h3>
                    <button @click="mobileFiltersOpen = false" class="p-2 text-zinc-400 hover:text-black">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form method="GET" action="{{ route('shop.index') }}" class="py-4 space-y-6 flex-1">
                    <!-- Styles -->
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-zinc-900 mb-2">Style</h4>
                        <div class="space-y-1.5">
                            @foreach($categories as $category)
                                <label class="flex items-center space-x-2 text-xs text-zinc-700">
                                    <input type="radio" name="category" value="{{ $category->slug }}" {{ request('category') === $category->slug ? 'checked' : '' }} class="text-black">
                                    <span>{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Sizes -->
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-zinc-900 mb-2">Size</h4>
                        <div class="grid grid-cols-5 gap-1.5">
                            @foreach($sizes as $size)
                                <label class="text-center py-2 text-xs font-bold rounded-lg border {{ in_array($size->code, (array) request('size', [])) ? 'bg-black text-white' : 'bg-white text-zinc-700 border-zinc-200' }}">
                                    <input type="checkbox" name="size[]" value="{{ $size->code }}" {{ in_array($size->code, (array) request('size', [])) ? 'checked' : '' }} class="sr-only">
                                    <span>{{ $size->code }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-4 border-t border-zinc-100 flex space-x-3">
                        <a href="{{ route('shop.index') }}" class="w-1/2 py-3 text-center border border-zinc-300 text-zinc-700 rounded-lg text-xs font-bold uppercase">Reset</a>
                        <button type="submit" class="w-1/2 py-3 bg-zinc-900 text-white rounded-lg text-xs font-bold uppercase tracking-wider">Apply</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
