@extends('layouts.app')

@section('title', 'Forgot Password | JACARIO')

@section('content')

<div class="min-h-[75vh] flex items-center justify-center px-4 sm:px-6 lg:px-8 py-16">
    <div class="max-w-md w-full bg-white rounded-2xl border border-zinc-200 shadow-2xl p-8 sm:p-10 space-y-8">
        
        <div class="text-center space-y-2">
            <span class="text-xs font-bold uppercase tracking-[0.25em] text-[#A4845B]">Account Security</span>
            <h1 class="text-2xl sm:text-3xl font-serif-luxury font-bold text-zinc-900">Reset Password</h1>
            <p class="text-xs text-zinc-500 font-light">Enter your registered email and we will send you a secure password reset link</p>
        </div>

        @if(session('status'))
            <div class="p-3 bg-emerald-50 text-emerald-800 rounded-lg text-xs font-semibold">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Email Address *</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-3 focus:outline-none focus:border-black">
                @error('email') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="w-full py-3.5 bg-[#0B0D10] hover:bg-black text-[#DFCAAB] text-xs font-bold uppercase tracking-[0.2em] rounded-xl transition-colors shadow-lg">
                Send Reset Link
            </button>
        </form>

        <div class="pt-4 border-t border-zinc-100 text-center text-xs text-zinc-600">
            <a href="{{ route('login') }}" class="font-bold text-zinc-900 hover:text-[#A4845B] underline">
                ← Return to Sign In
            </a>
        </div>

    </div>
</div>

@endsection
