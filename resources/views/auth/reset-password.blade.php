@extends('layouts.app')

@section('title', 'Set New Password | JACARIO')

@section('content')

<div class="min-h-[75vh] flex items-center justify-center px-4 sm:px-6 lg:px-8 py-16">
    <div class="max-w-md w-full bg-white rounded-2xl border border-zinc-200 shadow-2xl p-8 sm:p-10 space-y-8">
        
        <div class="text-center space-y-2">
            <span class="text-xs font-bold uppercase tracking-[0.25em] text-[#A4845B]">Account Security</span>
            <h1 class="text-2xl sm:text-3xl font-serif-luxury font-bold text-zinc-900">Set New Password</h1>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Email Address *</label>
                <input type="email" name="email" value="{{ old('email', $email) }}" required class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-3 focus:outline-none focus:border-black">
                @error('email') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
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

            <button type="submit" class="w-full py-3.5 bg-[#0B0D10] hover:bg-black text-[#DFCAAB] text-xs font-bold uppercase tracking-[0.2em] rounded-xl transition-colors shadow-lg">
                Update & Sign In
            </button>
        </form>

    </div>
</div>

@endsection
