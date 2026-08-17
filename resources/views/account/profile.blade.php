@extends('layouts.app')

@section('title', 'Client Profile & Security | JACARIO')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <div class="mb-8">
        <h1 class="text-3xl font-serif-luxury font-bold text-zinc-900 tracking-tight">Client Settings & Security</h1>
        <p class="text-xs text-zinc-500 mt-1">Manage your personal credentials and encrypted password</p>
    </div>

    <!-- Account Navigation Tabs -->
    <div class="flex items-center space-x-2 border-b border-zinc-200 mb-8 overflow-x-auto pb-px text-xs font-bold uppercase tracking-wider">
        <a href="{{ route('account.dashboard') }}" class="px-4 py-3 border-b-2 border-transparent text-zinc-500 hover:text-black hover:border-zinc-300 whitespace-nowrap">Overview</a>
        <a href="{{ route('account.orders') }}" class="px-4 py-3 border-b-2 border-transparent text-zinc-500 hover:text-black hover:border-zinc-300 whitespace-nowrap">Orders & Tracking</a>
        <a href="{{ route('account.addresses') }}" class="px-4 py-3 border-b-2 border-transparent text-zinc-500 hover:text-black hover:border-zinc-300 whitespace-nowrap">Saved Addresses</a>
        <a href="{{ route('account.profile') }}" class="px-4 py-3 border-b-2 border-black text-black whitespace-nowrap">Profile & Password</a>
        <a href="{{ route('account.reviews') }}" class="px-4 py-3 border-b-2 border-transparent text-zinc-500 hover:text-black hover:border-zinc-300 whitespace-nowrap">My Reviews</a>
        <a href="{{ route('wishlist.index') }}" class="px-4 py-3 border-b-2 border-transparent text-zinc-500 hover:text-black hover:border-zinc-300 whitespace-nowrap">Wishlist</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Personal Information Card -->
        <div class="p-8 bg-white rounded-2xl border border-zinc-200 shadow-sm space-y-6">
            <div class="pb-3 border-b border-zinc-100">
                <h3 class="text-sm font-bold uppercase tracking-wider text-zinc-900">Personal Information</h3>
                <p class="text-xs text-zinc-500 mt-0.5">Your name and communication contact</p>
            </div>

            <form method="POST" action="{{ route('account.profile.update') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Full Legal Name *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-3 focus:outline-none focus:border-black">
                    @error('name') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-3 focus:outline-none focus:border-black">
                    @error('email') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Contact Phone Number</label>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+91 98200 12345" class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-3 focus:outline-none focus:border-black">
                    @error('phone') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="px-6 py-3 bg-zinc-900 hover:bg-black text-[#DFCAAB] text-xs font-bold uppercase tracking-widest rounded-lg transition-colors">
                    Save Personal Details
                </button>
            </form>
        </div>

        <!-- Password Change Card -->
        <div class="p-8 bg-white rounded-2xl border border-zinc-200 shadow-sm space-y-6">
            <div class="pb-3 border-b border-zinc-100">
                <h3 class="text-sm font-bold uppercase tracking-wider text-zinc-900">Change Password</h3>
                <p class="text-xs text-zinc-500 mt-0.5">Ensure your account uses a strong, unique password</p>
            </div>

            <form method="POST" action="{{ route('account.password.update') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Current Password *</label>
                    <input type="password" name="current_password" required class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-3 focus:outline-none focus:border-black">
                    @error('current_password') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">New Password *</label>
                    <input type="password" name="password" required class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-3 focus:outline-none focus:border-black">
                    @error('password') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Confirm New Password *</label>
                    <input type="password" name="password_confirmation" required class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-3 focus:outline-none focus:border-black">
                </div>

                <button type="submit" class="px-6 py-3 bg-zinc-900 hover:bg-black text-[#DFCAAB] text-xs font-bold uppercase tracking-widest rounded-lg transition-colors">
                    Update Password
                </button>
            </form>
        </div>

    </div>

</div>

@endsection
