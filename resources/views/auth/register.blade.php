@extends('layouts.app')

@section('title', 'Create Private Client Account | JACARIO')

@section('content')

<div class="min-h-[75vh] flex items-center justify-center px-4 sm:px-6 lg:px-8 py-16">
    <div class="max-w-md w-full bg-white rounded-2xl border border-zinc-200 shadow-2xl p-8 sm:p-10 space-y-8">
        
        <div class="text-center space-y-3">
            <div class="flex justify-center">
                <img src="{{ asset('images/logo.png') }}" alt="JACARIO" class="h-10 sm:h-12 w-auto object-contain">
            </div>
            <span class="text-xs font-bold uppercase tracking-[0.25em] text-[#A4845B] block">New Client Registration</span>
            <h1 class="text-2xl sm:text-3xl font-serif-luxury font-bold text-zinc-900">Create Account</h1>
            <p class="text-xs text-zinc-500 font-light">Join the JACARIO Society for exclusive polo drops and seamless order tracking</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            @if(request('redirect'))
                <input type="hidden" name="redirect" value="{{ request('redirect') }}">
            @endif

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Full Legal Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-3 focus:outline-none focus:border-black">
                @error('name') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Email Address *</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-3 focus:outline-none focus:border-black">
                @error('email') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Mobile Phone (Optional)</label>
                <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+91 98200 12345" class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-3 focus:outline-none focus:border-black">
                @error('phone') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Password *</label>
                <input type="password" name="password" required class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-3 focus:outline-none focus:border-black">
                @error('password') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Confirm Password *</label>
                <input type="password" name="password_confirmation" required class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-3 focus:outline-none focus:border-black">
            </div>

            <button type="submit" class="w-full py-3.5 bg-[#0B0D10] hover:bg-black text-[#DFCAAB] text-xs font-bold uppercase tracking-[0.2em] rounded-xl transition-colors shadow-lg">
                Create Account
            </button>
        </form>

        <div class="pt-4 border-t border-zinc-100 text-center text-xs text-zinc-600">
            <span>Already have an account?</span>
            <a href="{{ route('login', ['redirect' => request('redirect')]) }}" class="font-bold text-zinc-900 hover:text-[#A4845B] ml-1 underline">
                Sign In
            </a>
        </div>

    </div>
</div>

@endsection
