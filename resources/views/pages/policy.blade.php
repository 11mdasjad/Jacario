@extends('layouts.app')

@section('title', "{$title} | JACARIO")

@section('content')

    <div class="bg-gradient-to-b from-[#F7F4EE] via-[#FAF8F5] to-white text-zinc-900 py-16 border-b border-zinc-200/80">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-3">
            <span class="text-xs font-bold uppercase tracking-[0.25em] text-[#8C6D46]">Legal & Service Policies</span>
            <h1 class="text-3xl sm:text-4xl font-serif-luxury font-bold text-zinc-950">{{ $title }}</h1>
            <p class="text-xs sm:text-sm text-zinc-600 font-light">{{ $subtitle }}</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="bg-white p-8 sm:p-12 rounded-2xl border border-zinc-200 shadow-sm prose prose-zinc max-w-none text-zinc-700 font-light leading-relaxed whitespace-pre-line text-sm sm:text-base">
            {!! nl2br(e($content)) !!}
        </div>
    </div>

@endsection
