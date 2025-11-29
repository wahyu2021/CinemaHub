@extends('layouts.app')

@section('title', __('messages.search_results_title') . ': ' . $query . ' - CinemaHub')

@section('content')
<div class="max-w-7xl mx-auto py-8">
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
        <div>
            <h1 class="text-4xl md:text-5xl font-display font-bold mb-2 text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-500">
                {{ __('messages.search_results_title') }}
            </h1>
            <p class="text-gray-400 font-light flex items-center gap-2">
                <span class="w-8 h-[1px] bg-primary"></span>
                {{ __('messages.results_for') }} <span class="text-white font-semibold">"{{ $query }}"</span>
            </p>
        </div>
    </div>

    <div class="glass-card rounded-2xl p-6 mb-10 border border-white/10">
        <form method="GET" action="{{ route('movies.search') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative group">
                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-500 group-focus-within:text-primary transition-colors"></i>
                </div>
                <input 
                    type="text" 
                    name="q" 
                    value="{{ $query }}"
                    placeholder="{{ __('messages.search_placeholder') }}" 
                    class="w-full bg-black/30 border border-white/10 rounded-xl pl-12 pr-5 py-4 text-white placeholder-gray-600 focus:border-primary focus:bg-black/50 focus:outline-none focus:shadow-[0_0_15px_rgba(229,9,20,0.3)] transition-all"
                    autofocus
                >
            </div>
            <button type="submit" class="px-8 py-4 bg-primary hover:bg-red-700 text-white font-bold rounded-xl hover:shadow-[0_0_20px_rgba(229,9,20,0.4)] transition-all flex items-center justify-center gap-2">
                <i class="fas fa-search"></i>
                <span>{{ __('messages.search_btn') }}</span>
            </button>
        </form>
    </div>

    @if(count($movies) > 0)
        <div class="mb-6 text-gray-400 text-sm">
            {{ __('messages.showing_page', ['current' => $current_page, 'total' => $total_pages]) }}
        </div>

        <div class="movies-container grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 mb-12">
            @foreach($movies as $index => $movie)
                <div class="reveal" style="transition-delay: {{ $index * 50 }}ms">
                    <x-movie-card :movie="$movie" :showBadges="false" />
                </div>
            @endforeach
        </div>

        @if($total_pages > 1)
            <x-pagination 
                :current-page="$current_page" 
                :total-pages="$total_pages" 
                :route-name="'movies.search'"
                :route-params="['q' => $query]"
            />
        @endif
    @else
        <div class="flex flex-col items-center justify-center py-32 text-center">
            <div class="w-24 h-24 bg-white/5 rounded-full flex items-center justify-center mb-6 animate-pulse">
                <i class="fas fa-search text-4xl text-gray-600"></i>
            </div>
            <h3 class="text-2xl font-bold text-white mb-2">{{ __('messages.not_found_title') }}</h3>
            <p class="text-gray-400 mb-8 max-w-md">{{ __('messages.not_found_desc', ['query' => $query]) }}</p>
            <a href="{{ route('movies.index') }}"
                class="px-6 py-3 bg-white text-black font-bold rounded-full hover:bg-gray-200 transition-colors">
                {{ __('messages.explore_movies') }}
            </a>
        </div>
    @endif
</div>
@endsection

