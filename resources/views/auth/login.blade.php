@extends('layouts.app')

@section('title', 'Sign In to Your Account | JACARIO')

@section('content')

<div class="min-h-[75vh] flex items-center justify-center px-4 sm:px-6 lg:px-8 py-8 sm:py-16">
    <div class="max-w-md w-full bg-white rounded-2xl border border-zinc-200 shadow-2xl p-6 sm:p-8 space-y-6"
         x-data="{ showEmailAuth: {{ ($errors->any() || old('email')) ? 'true' : 'false' }} }">
        
        <div class="text-center space-y-3">
            <div class="flex justify-center">
                <img src="{{ asset('images/logo.png') }}" alt="JACARIO" class="h-10 sm:h-12 w-auto object-contain">
            </div>
            <span class="text-xs font-bold uppercase tracking-[0.25em] text-[#A4845B] block">Client Access</span>
            <h1 class="text-2xl sm:text-3xl font-serif-luxury font-bold text-zinc-900">Sign In</h1>
            <p class="text-xs text-zinc-500 font-light">Access your bespoke order history, saved addresses, and exclusive drops</p>
        </div>

        @if(session('status'))
            <div class="p-3 bg-emerald-50 text-emerald-800 rounded-lg text-xs font-semibold text-center border border-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-3 bg-rose-50 text-rose-800 rounded-lg text-xs font-semibold text-center border border-rose-200">
                {{ session('error') }}
            </div>
        @endif

        <!-- 1. PRIMARY & RECOMMENDED OPTION: ONE-CLICK GOOGLE LOGIN -->
        <div class="space-y-3 pt-2">
            <a href="{{ route('auth.google', ['redirect' => request('redirect')]) }}" 
               class="w-full flex items-center justify-center space-x-3 py-3.5 px-4 bg-white hover:bg-zinc-50 active:bg-zinc-100 border-2 border-zinc-200 hover:border-zinc-400 text-zinc-900 rounded-xl font-bold text-xs uppercase tracking-[0.16em] shadow-sm hover:shadow-md transition-all duration-200 group">
                <svg class="w-5 h-5 shrink-0 transition-transform duration-200 group-hover:scale-110" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>
                <span>Continue with Google</span>
            </a>

            <div class="flex items-center justify-center space-x-1.5 text-[11px] text-zinc-400">
                <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>Verified 1-Click Secure Google Authentication</span>
            </div>
        </div>

        <!-- 2. STAFF / EMAIL SIGN IN ACCORDION (FOR ADMINS & TESTS) -->
        <div class="pt-2 border-t border-zinc-100">
            <div class="text-center">
                <button @click="showEmailAuth = !showEmailAuth" type="button" class="text-[11px] font-semibold text-zinc-500 hover:text-zinc-800 transition-colors inline-flex items-center space-x-1 py-1">
                    <span x-text="showEmailAuth ? 'Hide Staff / Password Sign In' : 'Staff Administration or Email Sign In'"></span>
                    <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': showEmailAuth }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
            </div>

            <div x-show="showEmailAuth" x-collapse class="mt-4 pt-3 border-t border-zinc-100">
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf
                    @if(request('redirect'))
                        <input type="hidden" name="redirect" value="{{ request('redirect') }}">
                    @endif

                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 mb-1">Email Address *</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-3 focus:outline-none focus:border-black">
                        @error('email') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="text-xs font-semibold text-zinc-700">Password *</label>
                            <a href="{{ route('password.request') }}" class="text-xs text-[#A4845B] hover:underline font-medium">Forgot?</a>
                        </div>
                        <input type="password" name="password" class="w-full text-xs bg-zinc-50 border border-zinc-300 rounded-lg p-3 focus:outline-none focus:border-black">
                        @error('password') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-between text-xs pt-1">
                        <label class="flex items-center space-x-2 text-zinc-700 cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded border-zinc-300 text-zinc-900 focus:ring-black">
                            <span>Keep me signed in</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full py-3 bg-[#0B0D10] hover:bg-black text-[#DFCAAB] text-xs font-bold uppercase tracking-[0.2em] rounded-xl transition-colors shadow-md">
                        Sign In with Password
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection
