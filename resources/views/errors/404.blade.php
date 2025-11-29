@extends('layouts.app')

@section('title', __('messages.lost_in_space'))

@section('content')
<div class="min-h-[80vh] flex items-center justify-center relative overflow-hidden">
    {{-- Background Elements --}}
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-primary/10 rounded-full blur-[120px] animate-pulse-slow pointer-events-none"></div>
    
    <div class="text-center relative z-10 px-4">
        <div class="mb-8 relative inline-block">
            <h1 class="text-9xl font-display font-bold text-transparent bg-clip-text bg-gradient-to-b from-white to-white/10 relative z-10">
                404
            </h1>
            <div class="absolute -inset-4 bg-primary/20 blur-2xl rounded-full z-0 opacity-50"></div>
            
            {{-- Floating Astronaut/Icon --}}
            <div class="absolute -top-12 -right-12 animate-float">
                <i class="fas fa-satellite text-6xl text-gray-400/50 rotate-45"></i>
            </div>
        </div>

        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">{{ __('messages.not_found_title') }}</h2>
        <p class="text-gray-400 text-lg mb-10 max-w-lg mx-auto">
            {{ __('messages.page_lost_desc') }}
        </p>

        <a href="{{ route('movies.index') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-primary text-white font-bold rounded-xl hover:bg-red-700 hover:shadow-[0_0_30px_rgba(229,9,20,0.4)] transition-all group">
            <i class="fas fa-rocket group-hover:-translate-y-1 group-hover:translate-x-1 transition-transform"></i>
            <span>{{ __('messages.back_to_earth') }}</span>
        </a>
    </div>
</div>
@endsection
