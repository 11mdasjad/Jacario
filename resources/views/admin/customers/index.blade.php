@extends('layouts.admin')

@section('title', 'Client Accounts')
@section('header_title', 'Customer Accounts')

@section('content')

<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-serif-luxury font-bold text-zinc-950">Customer Accounts</h1>
            <p class="text-xs text-zinc-500 mt-0.5">Manage registered clients, addresses, order history, and account activity</p>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="p-4 bg-white rounded-2xl border border-zinc-200 shadow-xs flex flex-col md:flex-row gap-4 items-center justify-between">
        <form method="GET" action="{{ route('admin.customers.index') }}" class="flex flex-wrap gap-3 items-center w-full">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search client by name, email, phone..." class="w-full text-xs bg-zinc-50 border border-zinc-200 rounded-xl px-3 py-2 text-zinc-900 focus:outline-none focus:border-black">
            </div>

            <button type="submit" class="px-4 py-2 bg-zinc-900 hover:bg-black text-white rounded-xl text-xs font-bold uppercase tracking-wider">
                Search
            </button>

            @if(request('search'))
                <a href="{{ route('admin.customers.index') }}" class="text-xs text-rose-600 hover:underline font-semibold">Reset</a>
            @endif
        </form>
    </div>

    <!-- Customers Table -->
    <div class="bg-white rounded-2xl border border-zinc-200 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead>
                    <tr class="border-b border-zinc-200 text-zinc-500 uppercase tracking-wider text-[10px] bg-zinc-50">
                        <th class="p-4">Customer Name</th>
                        <th class="p-4">Contact Info</th>
                        <th class="p-4">Orders Placed</th>
                        <th class="p-4">Lifetime Spend</th>
                        <th class="p-4">Joined Date</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-zinc-50/80 transition-colors">
                            <td class="p-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-zinc-100 text-zinc-900 border border-zinc-200 flex items-center justify-center font-bold text-xs">
                                        {{ substr($customer->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-zinc-950">{{ $customer->name }}</p>
                                        <span class="text-[10px] text-zinc-400 capitalize">{{ $customer->role }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <p class="text-zinc-900 font-medium">{{ $customer->email }}</p>
                                <p class="text-[10px] text-zinc-500 font-mono">{{ $customer->phone ?: 'No phone added' }}</p>
                            </td>
                            <td class="p-4 font-bold text-zinc-900">
                                {{ $customer->orders_count }} orders
                            </td>
                            <td class="p-4 font-bold text-zinc-950">
                                ₹{{ number_format($customer->orders->sum('total_amount'), 2) }}
                            </td>
                            <td class="p-4 text-zinc-500">
                                {{ $customer->created_at->format('M j, Y') }}
                            </td>
                            <td class="p-4">
                                @if($customer->is_active)
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-300">Active</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-800 border border-rose-300">Suspended</span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.customers.show', $customer->id) }}" class="px-2.5 py-1 bg-zinc-100 hover:bg-zinc-200 text-zinc-800 rounded-lg text-xs font-semibold">View</a>
                                    <form method="POST" action="{{ route('admin.customers.toggle-active', $customer->id) }}">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 {{ $customer->is_active ? 'bg-amber-50 hover:bg-amber-100 text-amber-800' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-800' }} rounded-lg text-xs font-semibold">
                                            {{ $customer->is_active ? 'Suspend' : 'Activate' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center text-zinc-400">
                                <p class="text-sm font-medium">No customer accounts registered yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
            <div class="p-4 border-t border-zinc-200 bg-white">
                {{ $customers->links() }}
            </div>
        @endif
    </div>

</div>

@endsection
