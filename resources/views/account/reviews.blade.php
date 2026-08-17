@extends('layouts.app')

@section('title', 'My Customer Reviews | JACARIO')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <div class="mb-8">
        <h1 class="text-3xl font-serif-luxury font-bold text-zinc-900 tracking-tight">My Product Reviews</h1>
        <p class="text-xs text-zinc-500 mt-1">Your published reviews and feedback on JACARIO Polo T-Shirts</p>
    </div>

    <!-- Account Navigation Tabs -->
    <div class="flex items-center space-x-2 border-b border-zinc-200 mb-8 overflow-x-auto pb-px text-xs font-bold uppercase tracking-wider">
        <a href="{{ route('account.dashboard') }}" class="px-4 py-3 border-b-2 border-transparent text-zinc-500 hover:text-black hover:border-zinc-300 whitespace-nowrap">Overview</a>
        <a href="{{ route('account.orders') }}" class="px-4 py-3 border-b-2 border-transparent text-zinc-500 hover:text-black hover:border-zinc-300 whitespace-nowrap">Orders & Tracking</a>
        <a href="{{ route('account.addresses') }}" class="px-4 py-3 border-b-2 border-transparent text-zinc-500 hover:text-black hover:border-zinc-300 whitespace-nowrap">Saved Addresses</a>
        <a href="{{ route('account.profile') }}" class="px-4 py-3 border-b-2 border-transparent text-zinc-500 hover:text-black hover:border-zinc-300 whitespace-nowrap">Profile & Password</a>
        <a href="{{ route('account.reviews') }}" class="px-4 py-3 border-b-2 border-black text-black whitespace-nowrap">My Reviews</a>
        <a href="{{ route('wishlist.index') }}" class="px-4 py-3 border-b-2 border-transparent text-zinc-500 hover:text-black hover:border-zinc-300 whitespace-nowrap">Wishlist</a>
    </div>

    @if($reviews->isEmpty())
        <div class="p-12 text-center bg-white rounded-2xl border border-zinc-200">
            <p class="text-xs text-zinc-500 mb-4">You have not submitted any reviews yet.</p>
            <a href="{{ route('shop.index') }}" class="inline-flex items-center px-6 py-2.5 bg-zinc-900 text-white rounded-lg text-xs font-bold uppercase tracking-wider">
                Explore Polo T-Shirts
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($reviews as $rev)
                <div class="p-6 bg-white rounded-2xl border border-zinc-200 shadow-sm space-y-3">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-bold text-zinc-900">
                            <a href="{{ route('products.show', $rev->product->slug) }}" class="hover:underline">
                                {{ $rev->product->name }}
                            </a>
                        </h4>
                        <span class="text-xs text-zinc-400">{{ $rev->created_at->format('M j, Y') }}</span>
                    </div>

                    <div class="flex items-center space-x-1 text-amber-500">
                        @for($i = 0; $i < $rev->rating; $i++)
                            <svg class="w-4 h-4 fill-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>

                    <h5 class="text-xs font-bold text-zinc-900 font-serif-luxury">{{ $rev->title }}</h5>
                    <p class="text-xs text-zinc-600 font-light leading-relaxed">{{ $rev->comment }}</p>
                </div>
            @endforeach

            <div class="mt-6">
                {{ $reviews->links() }}
            </div>
        </div>
    @endif

</div>

@endsection
