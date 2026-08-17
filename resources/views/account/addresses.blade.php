@extends('layouts.app')

@section('title', 'Address Book | JACARIO')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ newAddressModal: false }">
    
    <div class="flex items-center justify-between pb-6 border-b border-zinc-200 mb-8">
        <div>
            <h1 class="text-3xl font-serif-luxury font-bold text-zinc-900 tracking-tight">Saved Addresses</h1>
            <p class="text-xs text-zinc-500 mt-1">Manage delivery locations for swift, one-touch checkout</p>
        </div>
        <button type="button" @click="newAddressModal = true" class="px-5 py-2.5 bg-zinc-900 hover:bg-black text-[#DFCAAB] text-xs font-bold uppercase tracking-wider rounded-lg transition-colors">
            + Add New Address
        </button>
    </div>

    <!-- Account Navigation Tabs -->
    <div class="flex items-center space-x-2 border-b border-zinc-200 mb-8 overflow-x-auto pb-px text-xs font-bold uppercase tracking-wider">
        <a href="{{ route('account.dashboard') }}" class="px-4 py-3 border-b-2 border-transparent text-zinc-500 hover:text-black hover:border-zinc-300 whitespace-nowrap">Overview</a>
        <a href="{{ route('account.orders') }}" class="px-4 py-3 border-b-2 border-transparent text-zinc-500 hover:text-black hover:border-zinc-300 whitespace-nowrap">Orders & Tracking</a>
        <a href="{{ route('account.addresses') }}" class="px-4 py-3 border-b-2 border-black text-black whitespace-nowrap">Saved Addresses</a>
        <a href="{{ route('account.profile') }}" class="px-4 py-3 border-b-2 border-transparent text-zinc-500 hover:text-black hover:border-zinc-300 whitespace-nowrap">Profile & Password</a>
        <a href="{{ route('account.reviews') }}" class="px-4 py-3 border-b-2 border-transparent text-zinc-500 hover:text-black hover:border-zinc-300 whitespace-nowrap">My Reviews</a>
        <a href="{{ route('wishlist.index') }}" class="px-4 py-3 border-b-2 border-transparent text-zinc-500 hover:text-black hover:border-zinc-300 whitespace-nowrap">Wishlist</a>
    </div>

    @if($addresses->isEmpty())
        <div class="p-12 text-center bg-white rounded-2xl border border-zinc-200">
            <p class="text-xs text-zinc-500 mb-4">You have no saved addresses in your address book.</p>
            <button type="button" @click="newAddressModal = true" class="px-6 py-2.5 bg-zinc-900 text-white rounded-lg text-xs font-bold uppercase tracking-wider">
                Add Your First Address
            </button>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($addresses as $addr)
                <div class="p-6 bg-white rounded-2xl border {{ $addr->is_default ? 'border-zinc-950 ring-2 ring-zinc-950/10' : 'border-zinc-200' }} shadow-sm flex flex-col justify-between space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-zinc-900">{{ $addr->full_name }}</span>
                            <div class="flex items-center space-x-2">
                                @if($addr->is_default)
                                    <span class="text-[10px] font-bold uppercase tracking-wider bg-zinc-900 text-[#DFCAAB] px-2 py-0.5 rounded">Default</span>
                                @endif
                                <span class="text-[10px] uppercase font-semibold bg-zinc-100 text-zinc-600 px-2 py-0.5 rounded">{{ $addr->address_type }}</span>
                            </div>
                        </div>

                        <p class="text-xs text-zinc-600 leading-relaxed">{{ $addr->formatted_address }}</p>
                        <p class="text-xs text-zinc-500 font-mono mt-2">Mobile: {{ $addr->phone }}</p>
                    </div>

                    <div class="pt-3 border-t border-zinc-100 flex items-center justify-between text-xs">
                        @if(!$addr->is_default)
                            <form method="POST" action="{{ route('account.addresses.default', $addr->id) }}">
                                @csrf
                                <button type="submit" class="text-zinc-600 hover:text-black font-semibold">Set as Default</button>
                            </form>
                        @else
                            <span class="text-emerald-700 font-semibold">Default Address</span>
                        @endif

                        <form method="POST" action="{{ route('account.addresses.delete', $addr->id) }}" onsubmit="return confirm('Remove this address?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-rose-600 hover:underline font-semibold">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Add New Address Modal -->
    <div x-show="newAddressModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;"
         @keydown.window.escape="newAddressModal = false">
        
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="newAddressModal = false"></div>

        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative max-w-lg w-full bg-white rounded-2xl shadow-2xl p-6 sm:p-8 overflow-hidden border border-zinc-200" @click.stop>
                
                <div class="flex items-center justify-between pb-4 border-b border-zinc-100">
                    <h3 class="text-base font-serif-luxury font-bold text-zinc-900">Add New Delivery Location</h3>
                    <button type="button" @click="newAddressModal = false" class="text-zinc-400 hover:text-black">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('account.addresses.store') }}" class="space-y-4 pt-4">
                    @csrf

                    <div class="grid grid-cols-2 gap-3">
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-zinc-700 mb-1">Full Name *</label>
                            <input type="text" name="full_name" value="{{ old('full_name', $user->name) }}" required class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-2.5 focus:outline-none focus:border-black">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-zinc-700 mb-1">Mobile Phone *</label>
                            <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" required class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-2.5 focus:outline-none focus:border-black">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-zinc-700 mb-1">Address Line 1 (Flat, House, Building) *</label>
                            <input type="text" name="address_line_1" required class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-2.5 focus:outline-none focus:border-black">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 mb-1">Address Line 2 (Area/Locality)</label>
                            <input type="text" name="address_line_2" class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-2.5 focus:outline-none focus:border-black">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 mb-1">Landmark</label>
                            <input type="text" name="landmark" class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-2.5 focus:outline-none focus:border-black">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 mb-1">City *</label>
                            <input type="text" name="city" required class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-2.5 focus:outline-none focus:border-black">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 mb-1">State *</label>
                            <input type="text" name="state" required class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-2.5 focus:outline-none focus:border-black">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 mb-1">Postal Code (PIN) *</label>
                            <input type="text" name="postal_code" maxlength="6" required class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-2.5 focus:outline-none focus:border-black">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 mb-1">Address Type</label>
                            <select name="address_type" class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-2.5 focus:outline-none focus:border-black">
                                <option value="home">Home</option>
                                <option value="work">Work / Office</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="flex items-center space-x-2 text-xs text-zinc-700">
                            <input type="checkbox" name="is_default" value="1" class="rounded border-zinc-300 text-zinc-900 focus:ring-black">
                            <span>Set as my default shipping address</span>
                        </label>
                    </div>

                    <div class="pt-3 border-t border-zinc-100 flex space-x-3">
                        <button type="button" @click="newAddressModal = false" class="w-1/2 py-3 border border-zinc-300 text-zinc-700 rounded-lg text-xs font-bold uppercase">Cancel</button>
                        <button type="submit" class="w-1/2 py-3 bg-zinc-900 hover:bg-black text-[#DFCAAB] rounded-lg text-xs font-bold uppercase tracking-wider">Save Address</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</div>

@endsection
