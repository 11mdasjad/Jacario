@extends('layouts.admin')

@section('title', 'Product Catalog & Inventory')
@section('header_title', 'Product Catalog & Inventory')

@section('content')

<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-serif-luxury font-bold text-zinc-950">Product Catalog & Inventory</h1>
            <p class="text-xs text-zinc-500 mt-0.5">Manage polo and round neck silhouettes, sizing variants, colorways, and pricing</p>
        </div>

        <a href="{{ route('admin.products.create') }}" class="px-5 py-2.5 bg-zinc-950 hover:bg-black text-white rounded-xl text-xs font-bold uppercase tracking-wider transition-colors flex items-center space-x-1.5 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Add New Product</span>
        </a>
    </div>

    <!-- Filters & Search Bar -->
    <div class="p-4 bg-white rounded-2xl border border-zinc-200 shadow-xs flex flex-col md:flex-row gap-4 items-center justify-between">
        <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-wrap gap-3 items-center w-full">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, SKU, fabric..." class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl px-3 py-2 text-zinc-900 focus:outline-none focus:border-black">
            </div>

            <div>
                <select name="category" class="text-xs bg-zinc-50 border border-zinc-200 rounded-xl px-3 py-2 text-zinc-700 focus:outline-none">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="status" class="text-xs bg-zinc-50 border border-zinc-200 rounded-xl px-3 py-2 text-zinc-700 focus:outline-none">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="trashed" {{ request('status') === 'trashed' ? 'selected' : '' }}>Trashed</option>
                </select>
            </div>

            <button type="submit" class="px-4 py-2 bg-zinc-900 hover:bg-black text-white rounded-xl text-xs font-bold uppercase tracking-wider">
                Filter
            </button>
            
            @if(request('search') || request('category') || request('status'))
                <a href="{{ route('admin.products.index') }}" class="text-xs text-rose-600 hover:underline font-semibold">Reset</a>
            @endif
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-2xl border border-zinc-200 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead>
                    <tr class="border-b border-zinc-200 text-zinc-500 uppercase tracking-wider text-[10px] bg-zinc-50">
                        <th class="p-4">Silhouette & Name</th>
                        <th class="p-4">SKU</th>
                        <th class="p-4">Category</th>
                        <th class="p-4">Price</th>
                        <th class="p-4">Total Stock</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @forelse($products as $product)
                        <tr class="hover:bg-zinc-50/80 transition-colors">
                            <td class="p-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-14 bg-zinc-100 rounded-xl overflow-hidden border border-zinc-200 flex items-center justify-center shrink-0">
                                        <img src="{{ $product->primaryImage ? $product->primaryImage->url : asset('images/placeholder-polo.svg') }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <p class="font-bold text-zinc-950">{{ $product->name }}</p>
                                        <p class="text-[11px] text-zinc-500">{{ $product->variants->count() }} size/color combinations</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 font-mono font-semibold text-zinc-600">{{ $product->sku }}</td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-zinc-100 text-zinc-800 border border-zinc-200">
                                    {{ $product->category->name }}
                                </span>
                            </td>
                            <td class="p-4 font-bold text-zinc-950">
                                ₹{{ number_format($product->effective_price, 2) }}
                                @if($product->has_discount)
                                    <span class="text-[10px] text-zinc-400 line-through block font-normal">₹{{ number_format($product->base_price, 2) }}</span>
                                @endif
                            </td>
                            <td class="p-4">
                                @php $stock = $product->variants->sum('stock_quantity'); @endphp
                                <span class="font-bold {{ $stock < 10 ? 'text-amber-600' : 'text-zinc-900' }}">
                                    {{ $stock }} units
                                </span>
                            </td>
                            <td class="p-4">
                                @if($product->trashed())
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-300">Trashed</span>
                                @elseif($product->is_active)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">Active</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-zinc-100 text-zinc-700 border border-zinc-300">Inactive</span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    @if($product->trashed())
                                        <form method="POST" action="{{ route('admin.products.restore', $product->id) }}">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-lg text-xs font-semibold">Restore</button>
                                        </form>
                                    @else
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="px-2.5 py-1 bg-zinc-100 hover:bg-zinc-200 text-zinc-800 rounded-lg text-xs font-semibold">Edit</a>
                                        <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" onsubmit="return confirm('Are you sure you want to archive this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs font-semibold">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center text-zinc-400">
                                <p class="text-sm font-medium">No products found in the catalog.</p>
                                <p class="text-xs text-zinc-400 mt-1">Click "Add New Product" to create one.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="p-4 border-t border-zinc-200 bg-white">
                {{ $products->links() }}
            </div>
        @endif
    </div>

</div>

@endsection
