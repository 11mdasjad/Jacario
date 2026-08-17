@extends('layouts.app')

@section('title', 'Unauthorized Access — 403 | JACARIO')

@section('content')

<div class="min-h-[70vh] flex items-center justify-center px-4 py-16">
    <div class="max-w-md w-full bg-white rounded-2xl border border-zinc-200 shadow-xl p-10 text-center space-y-6">
        <span class="text-5xl font-serif-luxury font-bold text-zinc-900">403</span>
        <div class="space-y-2">
            <h1 class="text-xl font-serif-luxury font-bold text-zinc-900">Private Atelier Access Restricted</h1>
            <p class="text-xs text-zinc-500 font-light leading-relaxed">
                You do not possess the necessary staff credentials to access this administrative portal.
            </p>
        </div>
        <div class="pt-2">
            <a href="{{ route('home') }}" class="inline-flex items-center px-8 py-3.5 bg-[#0B0D10] hover:bg-black text-[#DFCAAB] text-xs font-bold uppercase tracking-widest rounded-xl transition-colors shadow-lg">
                Return to Storefront
            </a>
        </div>
    </div>
</div>

@endsection
