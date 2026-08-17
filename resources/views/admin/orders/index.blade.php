@extends('layouts.admin')

@section('title', 'Client Orders & Logistics')
@section('header_title', 'Order Management & Fulfillment')

@section('content')

<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-serif-luxury font-bold text-zinc-950">Order Management</h1>
            <p class="text-xs text-zinc-500 mt-0.5">Track fulfillment transitions, payment receipts, and dispatch tracking</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="p-4 bg-white rounded-2xl border border-zinc-200 shadow-xs flex flex-col md:flex-row gap-4 items-center justify-between">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-wrap gap-3 items-center w-full">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by order #, name, email, phone..." class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl px-3 py-2 text-zinc-900 focus:outline-none focus:border-black">
            </div>

            <div>
                <select name="status" class="text-xs bg-zinc-50 border border-zinc-200 rounded-xl px-3 py-2 text-zinc-700 focus:outline-none">
                    <option value="">All Fulfillment Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="out_for_delivery" {{ request('status') === 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div>
                <select name="payment_status" class="text-xs bg-zinc-50 border border-zinc-200 rounded-xl px-3 py-2 text-zinc-700 focus:outline-none">
                    <option value="">All Payment Statuses</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="captured" {{ request('payment_status') === 'captured' ? 'selected' : '' }}>Captured</option>
                    <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>

            <button type="submit" class="px-4 py-2 bg-zinc-900 hover:bg-black text-white rounded-xl text-xs font-bold uppercase tracking-wider">
                Filter
            </button>

            @if(request('search') || request('status') || request('payment_status'))
                <a href="{{ route('admin.orders.index') }}" class="text-xs text-rose-600 hover:underline font-semibold">Reset</a>
            @endif
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-2xl border border-zinc-200 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead>
                    <tr class="border-b border-zinc-200 text-zinc-500 uppercase tracking-wider text-[10px] bg-zinc-50">
                        <th class="p-4">Order Reference</th>
                        <th class="p-4">Client</th>
                        <th class="p-4">Date</th>
                        <th class="p-4">Payment</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Grand Total</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-zinc-50/80 transition-colors">
                            <td class="p-4">
                                <span class="font-mono font-bold text-zinc-950">{{ $order->order_number }}</span>
                                <span class="text-[10px] text-zinc-500 block uppercase font-medium">{{ $order->payment_method }}</span>
                            </td>
                            <td class="p-4">
                                <p class="font-bold text-zinc-900">{{ $order->customer_name }}</p>
                                <p class="text-[10px] text-zinc-500">{{ $order->customer_email }}</p>
                            </td>
                            <td class="p-4 text-zinc-600">
                                {{ $order->created_at->format('M j, Y') }}
                                <span class="text-[10px] block text-zinc-400">{{ $order->created_at->format('g:i A') }}</span>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $order->payment_status === 'captured' || $order->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-800 border-emerald-300' : 'bg-zinc-100 text-zinc-700 border-zinc-300' }}">
                                    {{ $order->payment_status === 'captured' ? 'Paid' : $order->payment_status }}
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $order->status_badge_color }}">
                                    {{ str_replace('_', ' ', $order->status) }}
                                </span>
                            </td>
                            <td class="p-4 font-bold text-zinc-950">
                                ₹{{ number_format($order->total_amount, 2) }}
                            </td>
                            <td class="p-4 text-right">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="px-3 py-1.5 bg-zinc-100 hover:bg-zinc-950 hover:text-white rounded-lg text-[11px] font-bold text-zinc-800 transition-colors">
                                    Manage
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center text-zinc-400">
                                <p class="text-sm font-medium">No orders found.</p>
                                <p class="text-xs text-zinc-400 mt-1">Customer purchases will be listed here.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-4 border-t border-zinc-200 bg-white">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

</div>

@endsection
