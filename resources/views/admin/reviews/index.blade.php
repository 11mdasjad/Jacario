@extends('layouts.admin')

@section('title', 'Review Moderation')
@section('header_title', 'Client Review Moderation')

@section('content')

<div class="space-y-6">
    
    <div>
        <h1 class="text-xl font-serif-luxury font-bold text-zinc-950">Client Review Moderation</h1>
        <p class="text-xs text-zinc-500 mt-0.5">Approve, feature, or remove verified customer product testimonials</p>
    </div>

    <!-- Reviews Table -->
    <div class="bg-white rounded-2xl border border-zinc-200 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead>
                    <tr class="border-b border-zinc-200 text-zinc-500 uppercase tracking-wider text-[10px] bg-zinc-50">
                        <th class="p-4">Product</th>
                        <th class="p-4">Customer</th>
                        <th class="p-4">Rating</th>
                        <th class="p-4">Review Content</th>
                        <th class="p-4">Date</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @forelse($reviews as $review)
                        <tr class="hover:bg-zinc-50/80 transition-colors">
                            <td class="p-4">
                                <p class="font-bold text-zinc-900">{{ $review->product->name ?? 'Product' }}</p>
                            </td>
                            <td class="p-4">
                                <p class="font-semibold text-zinc-900">{{ $review->user->name ?? 'Client' }}</p>
                                <p class="text-[10px] text-zinc-500">{{ $review->user->email ?? '' }}</p>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center text-amber-500">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'fill-amber-400' : 'fill-zinc-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                            </td>
                            <td class="p-4 max-w-xs">
                                <p class="font-bold text-zinc-900 line-clamp-1">{{ $review->title }}</p>
                                <p class="text-zinc-600 text-[11px] line-clamp-2 mt-0.5">{{ $review->comment }}</p>
                            </td>
                            <td class="p-4 text-zinc-500">
                                {{ $review->created_at->format('M j, Y') }}
                            </td>
                            <td class="p-4">
                                <div class="flex flex-col space-y-1">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $review->is_approved ? 'bg-emerald-50 text-emerald-800 border border-emerald-300' : 'bg-amber-50 text-amber-800 border border-amber-300' }}">
                                        {{ $review->is_approved ? 'Approved' : 'Pending' }}
                                    </span>
                                    @if($review->is_featured)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-purple-50 text-purple-800 border border-purple-300">
                                            Featured
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <form method="POST" action="{{ route('admin.reviews.toggle-approved', $review->id) }}">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 bg-zinc-100 hover:bg-zinc-200 text-zinc-800 rounded-lg text-xs font-semibold">
                                            {{ $review->is_approved ? 'Unapprove' : 'Approve' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.reviews.destroy', $review->id) }}" onsubmit="return confirm('Delete review?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs font-semibold">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center text-zinc-400">
                                <p class="text-sm font-medium">No customer reviews submitted yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reviews->hasPages())
            <div class="p-4 border-t border-zinc-200 bg-white">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>

</div>

@endsection
