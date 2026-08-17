@extends('layouts.admin')

@section('title', "Customer Profile: {$customer->name}")
@section('header_title', "Customer: {$customer->name}")

@section('content')

<div class="space-y-6">
    
    <div class="flex items-center justify-between pb-4 border-b border-zinc-200">
        <div>
            <h1 class="text-xl font-serif-luxury font-bold text-zinc-950">{{ $customer->name }}</h1>
            <p class="text-xs text-zinc-500 mt-0.5 font-mono">{{ $customer->email }} • Registered {{ $customer->created_at->format('M Y') }}</p>
        </div>
        <a href="{{ route('admin.customers.index') }}" class="text-xs font-bold text-zinc-600 hover:text-black uppercase tracking-wider">
            ← Back to Accounts
        </a>
    </div>

    <!-- Client Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="p-6 bg-white rounded-2xl border border-zinc-200 shadow-xs space-y-1">
            <span class="text-xs text-zinc-500 font-bold uppercase">Total Orders</span>
            <p class="text-2xl font-serif-luxury font-bold text-zinc-950">{{ $customer->orders->count() }}</p>
        </div>

        <div class="p-6 bg-white rounded-2xl border border-zinc-200 shadow-xs space-y-1">
            <span class="text-xs text-zinc-500 font-bold uppercase">Lifetime Spend</span>
            <p class="text-2xl font-serif-luxury font-bold text-zinc-950">
                ₹{{ number_format($customer->orders->where('payment_status', 'captured')->sum('total_amount'), 2) }}
            </p>
        </div>

        <div class="p-6 bg-white rounded-2xl border border-zinc-200 shadow-xs space-y-1">
            <span class="text-xs text-zinc-500 font-bold uppercase">Saved Addresses</span>
            <p class="text-2xl font-serif-luxury font-bold text-zinc-950">{{ $customer->addresses->count() }}</p>
        </div>
    </div>

    <!-- Order History of Customer -->
    <div class="p-6 bg-white rounded-2xl border border-zinc-200 shadow-xs space-y-4">
        <h3 class="text-xs font-bold uppercase tracking-wider text-[#8C6D46]">Customer Order History</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead>
                    <tr class="border-b border-zinc-200 text-zinc-500 uppercase tracking-wider text-[10px] bg-zinc-50">
                        <th class="py-3 px-4 rounded-l-lg">Order #</th>
                        <th class="py-3 px-4">Date</th>
                        <th class="py-3 px-4">Items</th>
                        <th class="py-3 px-4">Total Amount</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right rounded-r-lg">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @forelse($customer->orders as $ord)
                        <tr class="hover:bg-zinc-50/80 transition-colors">
                            <td class="py-3 px-4 font-mono font-bold text-zinc-950">{{ $ord->order_number }}</td>
                            <td class="py-3 px-4 text-zinc-600">{{ $ord->created_at->format('M j, Y') }}</td>
                            <td class="py-3 px-4 text-zinc-700">{{ $ord->items->count() }}</td>
                            <td class="py-3 px-4 font-bold text-zinc-950">₹{{ number_format($ord->total_amount, 2) }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase border {{ $ord->status_badge_color }}">
                                    {{ str_replace('_', ' ', $ord->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('admin.orders.show', $ord->id) }}" class="px-3 py-1 bg-zinc-100 hover:bg-zinc-950 hover:text-white text-zinc-800 rounded-lg text-[11px] font-bold transition-colors">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-zinc-400">No orders placed by this customer.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
