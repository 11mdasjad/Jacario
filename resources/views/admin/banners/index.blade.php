@extends('layouts.admin')

@section('title', 'Hero Banners Management')
@section('header_title', 'Hero Promotional Banners')

@section('content')

<div class="space-y-6">
    
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-zinc-200">
        <div>
            <h1 class="text-xl font-serif-luxury font-bold text-zinc-950">Store Banners & Sliders</h1>
            <p class="text-xs text-zinc-500 mt-0.5">Manage the 5 homepage hero banners and the 3 running shop catalog banners</p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('shop.index') }}" target="_blank" class="px-4 py-2.5 bg-zinc-100 hover:bg-zinc-200 text-zinc-800 rounded-xl text-xs font-bold uppercase tracking-wider transition-colors flex items-center space-x-1.5 border border-zinc-200">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <span>Preview Shop Banner</span>
            </a>
            <a href="{{ route('admin.banners.create') }}" class="px-4 py-2.5 bg-zinc-950 hover:bg-black text-[#DFCAAB] rounded-xl text-xs font-bold uppercase tracking-wider transition-colors flex items-center space-x-1.5 shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Add Banner</span>
            </a>
        </div>
    </div>

    <!-- Position Filter Tabs -->
    <div class="flex items-center space-x-2 border-b border-zinc-200 pb-3">
        <a href="{{ route('admin.banners.index', ['position' => 'all']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ ($position ?? 'all') === 'all' ? 'bg-zinc-950 text-white shadow-xs' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200' }}">
            All Banners ({{ $banners->count() }})
        </a>
        <a href="{{ route('admin.banners.index', ['position' => 'hero']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ ($position ?? '') === 'hero' ? 'bg-zinc-950 text-white shadow-xs' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200' }}">
            ✨ Homepage Hero Carousel ({{ $heroCount ?? 5 }})
        </a>
        <a href="{{ route('admin.banners.index', ['position' => 'shop']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ ($position ?? '') === 'shop' ? 'bg-zinc-950 text-white shadow-xs' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200' }}">
            🛍️ Shop Catalog Running Carousel ({{ $shopCount ?? 3 }})
        </a>
    </div>

    <!-- Banners Grid / Table -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($banners as $banner)
            <div class="bg-white rounded-2xl border border-zinc-200 overflow-hidden shadow-xs hover:shadow-md transition-all flex flex-col justify-between">
                
                <div>
                    <!-- Banner Image Preview -->
                    <div class="relative aspect-[16/9] bg-zinc-900 overflow-hidden group">
                        <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                        
                        <!-- Badges -->
                        <div class="absolute top-3 left-3 flex flex-wrap items-center gap-1.5">
                            <span class="px-2 py-0.5 rounded-md bg-white/90 backdrop-blur-md text-[10px] font-black text-zinc-900 shadow-xs">
                                {{ ($banner->position ?? 'hero') === 'shop' ? 'Shop Slot' : 'Hero Slot' }} #{{ $banner->sort_order }}
                            </span>
                            <span class="px-2 py-0.5 rounded-md {{ ($banner->position ?? 'hero') === 'shop' ? 'bg-blue-900/80 text-blue-200' : 'bg-zinc-950/80 text-[#DFCAAB]' }} backdrop-blur-md text-[10px] font-bold uppercase tracking-wider shadow-xs">
                                {{ ($banner->position ?? 'hero') === 'shop' ? 'Shop Strip' : 'Hero Slide' }}
                            </span>
                            @if($banner->badge_text)
                                <span class="px-2 py-0.5 rounded-md bg-[#A4845B] text-white text-[10px] font-bold shadow-xs">
                                    {{ $banner->badge_text }}
                                </span>
                            @endif
                        </div>

                        <!-- Status Pill -->
                        <div class="absolute top-3 right-3">
                            <form action="{{ route('admin.banners.toggle-active', $banner->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider transition-all {{ $banner->is_active ? 'bg-emerald-500 text-white shadow-xs' : 'bg-zinc-800 text-zinc-300' }}">
                                    {{ $banner->is_active ? '● Active' : '○ Paused' }}
                                </button>
                            </form>
                        </div>

                        <!-- On-Image Headline Preview -->
                        <div class="absolute bottom-3 left-3 right-3 text-white">
                            <h3 class="text-base font-serif-luxury font-bold leading-tight">{{ $banner->title }}</h3>
                            <p class="text-[11px] text-zinc-300 line-clamp-1 font-light mt-0.5">{{ $banner->subtitle }}</p>
                        </div>
                    </div>

                    <!-- Details Box -->
                    <div class="p-4 space-y-2.5 border-b border-zinc-100 text-xs">
                        <div class="flex items-center justify-between text-zinc-600">
                            <span class="font-semibold text-zinc-700">CTA Button:</span>
                            <span class="font-bold text-zinc-900 bg-zinc-100 px-2 py-0.5 rounded">{{ $banner->cta_text }}</span>
                        </div>
                        <div class="flex items-center justify-between text-zinc-600">
                            <span class="font-semibold text-zinc-700">Destination Link:</span>
                            <code class="text-[10px] text-zinc-500 bg-zinc-50 px-1.5 py-0.5 rounded truncate max-w-[150px]">{{ $banner->cta_url }}</code>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="p-3 bg-zinc-50 flex items-center justify-between gap-2">
                    <a href="{{ route('admin.banners.edit', $banner->id) }}" class="flex-1 py-2 bg-white hover:bg-zinc-950 hover:text-white text-zinc-900 border border-zinc-200 rounded-xl text-center text-xs font-bold uppercase tracking-wider transition-colors shadow-2xs">
                        Edit Banner
                    </a>

                    @if($banners->count() > 1)
                        <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete Banner #{{ $banner->sort_order }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 rounded-xl bg-white hover:bg-rose-50 text-rose-600 border border-zinc-200 hover:border-rose-200 transition-colors" title="Delete Banner">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    @endif
                </div>

            </div>
        @empty
            <div class="col-span-full p-12 bg-white rounded-3xl border border-zinc-200 text-center space-y-3">
                <svg class="w-12 h-12 mx-auto text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <h3 class="text-sm font-bold text-zinc-900">No Banners Configured</h3>
                <p class="text-xs text-zinc-500">Add your first promotional hero banner to appear on the homepage carousel.</p>
                <a href="{{ route('admin.banners.create') }}" class="inline-block px-4 py-2 bg-zinc-950 text-white text-xs font-bold rounded-xl uppercase tracking-wider">
                    Add Banner #1
                </a>
            </div>
        @endforelse
    </div>

</div>

@endsection
