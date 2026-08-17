@extends('layouts.app')

@section('title', 'Sign In to Your Account | JACARIO')

@section('content')

<div class="min-h-[75vh] flex items-center justify-center px-4 sm:px-6 lg:px-8 py-16">
    <div class="max-w-md w-full bg-white rounded-2xl border border-zinc-200 shadow-2xl p-8 sm:p-10 space-y-8">
        
        <div class="text-center space-y-3">
            <div class="flex justify-center">
                <img src="{{ asset('images/logo.png') }}" alt="JACARIO" class="h-10 sm:h-12 w-auto object-contain">
            </div>
            <span class="text-xs font-bold uppercase tracking-[0.25em] text-[#A4845B] block">Client Access</span>
            <h1 class="text-2xl sm:text-3xl font-serif-luxury font-bold text-zinc-900">Sign In</h1>
            <p class="text-xs text-zinc-500 font-light">Access your bespoke order history and saved delivery addresses</p>
        </div>

        @if(session('status'))
            <div class="p-3 bg-emerald-50 text-emerald-800 rounded-lg text-xs font-semibold">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            @if(request('redirect'))
                <input type="hidden" name="redirect" value="{{ request('redirect') }}">
            @endif

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Email Address *</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-3 focus:outline-none focus:border-black">
                @error('email') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="text-xs font-semibold text-zinc-700">Password *</label>
                    <a href="{{ route('password.request') }}" class="text-xs text-[#A4845B] hover:underline font-medium">Forgot?</a>
                </div>
                <input type="password" name="password" required class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-3 focus:outline-none focus:border-black">
                @error('password') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center space-x-2 text-zinc-700 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-zinc-300 text-zinc-900 focus:ring-black">
                    <span>Keep me signed in</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3.5 bg-[#0B0D10] hover:bg-black text-[#DFCAAB] text-xs font-bold uppercase tracking-[0.2em] rounded-xl transition-colors shadow-lg">
                Sign In to Account
            </button>
        </form>

        <div class="pt-4 border-t border-zinc-100 text-center text-xs text-zinc-600">
            <span>New to JACARIO?</span>
            <a href="{{ route('register', ['redirect' => request('redirect')]) }}" class="font-bold text-zinc-900 hover:text-[#A4845B] ml-1 underline">
                Create Private Client Account
            </a>
        </div>

        <!-- Demo Credentials Helper Note for Reviewers -->
        <div class="p-3 bg-zinc-50 rounded-xl border border-zinc-200 text-[11px] text-zinc-500 text-left space-y-1">
            <p class="font-bold text-zinc-800 uppercase tracking-wider text-[10px]">Demo Sign-In Accounts:</p>
            <p>• Super Admin: <span class="font-mono text-zinc-900">admin@jacario.com</span> / <span class="font-mono text-zinc-900">Password123!</span></p>
            <p>• Demo Client: <span class="font-mono text-zinc-900">customer@jacario.com</span> / <span class="font-mono text-zinc-900">Password123!</span></p>
        </div>

    </div>
</div>

@endsection
