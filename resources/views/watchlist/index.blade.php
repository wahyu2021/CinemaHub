@extends('layouts.app')

@section('title', __('messages.my_watchlist') . ' - CinemaHub')

@section('content')
<div class="max-w-7xl mx-auto py-8">
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
        <div>
            <h1 class="text-4xl md:text-5xl font-display font-bold mb-2 text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-500">
                {{ __('messages.my_watchlist') }}
            </h1>
            <p class="text-gray-400 font-light flex items-center gap-2">
                <span class="w-8 h-[1px] bg-primary"></span>
                {{ __('messages.movies_in_watchlist', ['count' => $watchlist->count()]) }}
            </p>
        </div>
        <a href="{{ route('movies.index') }}" class="px-6 py-3 glass rounded-xl hover:bg-white/10 transition-all flex items-center gap-2 text-white font-medium">
            <i class="fas fa-plus"></i>
            {{ __('messages.add_movie') }}
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 px-6 py-4 rounded-xl bg-green-500/20 border border-green-500/30 text-green-400 flex items-center gap-3">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($watchlist->isEmpty())
        <div class="flex flex-col items-center justify-center py-32 text-center">
            <div class="w-32 h-32 bg-white/5 rounded-full flex items-center justify-center mb-8 animate-pulse">
                <i class="fas fa-bookmark text-5xl text-gray-600"></i>
            </div>
            <h3 class="text-2xl font-bold text-white mb-3">{{ __('messages.watchlist_empty') }}</h3>
            <p class="text-gray-400 mb-8 max-w-md">{{ __('messages.watchlist_empty_desc') }}</p>
            <a href="{{ route('movies.index') }}" class="px-8 py-4 bg-primary text-white font-bold rounded-xl hover:bg-red-700 hover:shadow-[0_0_20px_rgba(229,9,20,0.4)] transition-all flex items-center gap-3 group">
                <i class="fas fa-compass"></i>
                <span>{{ __('messages.browse_movies') }}</span>
                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @foreach($watchlist as $index => $item)
                <div class="reveal group" style="transition-delay: {{ $index * 50 }}ms">
                    <div class="glass-card rounded-2xl overflow-hidden h-full relative shadow-2xl border border-white/5 group-hover:border-primary/50 group-hover:shadow-[0_0_30px_rgba(229,9,20,0.3)] transition-all duration-500">
                        <a href="{{ route('movies.show', $item->movie_id) }}" class="block relative">
                            <div class="relative overflow-hidden aspect-[2/3]">
                                @if($item->poster_path)
                                    <img src="https://image.tmdb.org/t/p/w500{{ $item->poster_path }}" 
                                        alt="{{ $item->title }}"
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                        loading="lazy">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-gray-900 to-black flex items-center justify-center">
                                        <i class="fas fa-film text-6xl text-gray-800"></i>
                                    </div>
                                @endif

                                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent opacity-60 group-hover:opacity-90 transition-opacity duration-500"></div>

                                <div class="absolute top-4 left-4">
                                    <span class="px-3 py-1 rounded-lg bg-primary/90 text-white text-xs font-bold backdrop-blur-sm">
                                        <i class="fas fa-bookmark mr-1"></i> {{ __('messages.saved') }}
                                    </span>
                                </div>

                                <div class="absolute inset-0 flex flex-col justify-end p-5 translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                                    <h3 class="font-display font-bold text-xl leading-tight text-white mb-2 drop-shadow-lg line-clamp-2">
                                        {{ $item->title }}
                                    </h3>
                                    <p class="text-xs text-gray-300 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                        {{ __('messages.added_time', ['time' => $item->created_at->diffForHumans()]) }}
                                    </p>
                                </div>
                            </div>
                        </a>

                        <form action="{{ route('watchlist.destroy', $item->movie_id) }}" method="POST" 
                            class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300"
                            onsubmit="return confirm('{{ __('messages.remove_confirmation') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                class="w-10 h-10 rounded-full bg-black/60 backdrop-blur-md border border-white/20 text-white hover:bg-red-600 hover:border-red-600 flex items-center justify-center transition-all hover:scale-110"
                                title="{{ __('messages.remove_from_watchlist') }}">
                                <i class="fas fa-trash-alt text-sm"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-16 glass-card rounded-2xl p-8 border border-white/10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-xl bg-primary/20 flex items-center justify-center text-primary">
                        <i class="fas fa-lightbulb text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-lg">{{ __('messages.tips_title') }}</h3>
                        <p class="text-gray-400 text-sm">{{ __('messages.tips_desc') }}</p>
                    </div>
                </div>
                <a href="{{ route('movies.trending') }}" class="px-6 py-3 bg-white/10 text-white font-medium rounded-xl hover:bg-white/20 transition-colors flex items-center gap-2">
                    <i class="fas fa-fire text-primary"></i>
                    {{ __('messages.view_trending') }}
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
