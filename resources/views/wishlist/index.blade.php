@extends('layouts.app')

@section('title', 'My Saved Wishlist | JACARIO')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <div class="flex items-center justify-between pb-6 border-b border-zinc-200 mb-8">
        <div>
            <h1 class="text-3xl font-serif-luxury font-bold text-zinc-900 tracking-tight">My Wishlist</h1>
            <p class="text-xs text-zinc-500 mt-1">Your curated selection of luxury Polo T-Shirts</p>
        </div>
        <a href="{{ route('shop.index') }}" class="text-xs font-bold uppercase tracking-widest text-zinc-900 hover:text-[#A4845B] transition-colors">
            Continue Browsing
        </a>
    </div>

    @if($wishlistItems->isEmpty())
        <div class="py-20 text-center bg-white rounded-2xl border border-zinc-200 p-8 sm:p-12">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-zinc-100 flex items-center justify-center text-zinc-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </div>
            <h3 class="text-lg font-serif-luxury font-bold text-zinc-900 mb-1">Your wishlist is presently empty</h3>
            <p class="text-xs text-zinc-500 mb-6">Save your favorite polo silhouettes and revisit them anytime.</p>
            <a href="{{ route('shop.index') }}" class="inline-flex items-center px-8 py-3 bg-[#0B0D10] text-[#DFCAAB] rounded-lg text-xs font-bold uppercase tracking-widest hover:bg-black transition-colors">
                Explore Polo T-Shirts
            </a>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-6">
            @foreach($wishlistItems as $item)
                @php $product = $item->product; @endphp
                <div class="bg-white rounded-xl sm:rounded-2xl border border-zinc-200 p-2.5 sm:p-4 flex flex-col justify-between space-y-2.5 sm:space-y-4 relative group shadow-xs hover:shadow-md transition-shadow">
                    
                    <form method="POST" action="{{ route('wishlist.toggle') }}" class="absolute top-2 right-2 sm:top-3 sm:right-3 z-10">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-white/90 shadow-sm border border-zinc-100 flex items-center justify-center text-rose-600 hover:scale-110 transition-transform" title="Remove from wishlist">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 fill-rose-600" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </button>
                    </form>

                    <a href="{{ route('products.show', $product->slug) }}" class="aspect-[4/5] bg-zinc-50 rounded-lg sm:rounded-xl p-2 sm:p-4 flex items-center justify-center overflow-hidden">
                        <img src="{{ $product->primaryImage ? $product->primaryImage->url : asset('images/placeholder-polo.svg') }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </a>

                    <div class="space-y-1">
                        <p class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-zinc-400">{{ $product->category->name }}</p>
                        <h4 class="text-xs font-bold text-zinc-900 truncate">
                            <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                        </h4>
                        <div class="flex items-baseline space-x-1.5 pt-0.5">
                            @if($product->has_discount)
                                <span class="text-xs sm:text-sm font-bold text-zinc-950">₹{{ number_format($product->sale_price) }}</span>
                                <span class="text-[10px] sm:text-xs text-zinc-400 line-through">₹{{ number_format($product->base_price) }}</span>
                            @else
                                <span class="text-xs sm:text-sm font-bold text-zinc-950">₹{{ number_format($product->base_price) }}</span>
                            @endif
                        </div>
                    </div>

                    <form method="POST" action="{{ route('wishlist.move-to-cart', $item->id) }}">
                        @csrf
                        <button type="submit" class="w-full py-2 sm:py-2.5 bg-zinc-900 hover:bg-black text-[#DFCAAB] text-[10px] sm:text-xs font-bold uppercase tracking-wider rounded-lg transition-colors flex items-center justify-center space-x-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            <span>Move to Bag</span>
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $wishlistItems->links() }}
        </div>
    @endif

</div>

@endsection
