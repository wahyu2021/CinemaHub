@extends('layouts.app')

@section('title', __('messages.my_watchlist') . ' - CinemaHub')

@section('content')
<div class="min-h-screen py-12 relative overflow-hidden">
    <!-- Ambient Background Effects -->
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-primary/20 rounded-full blur-[128px] pointer-events-none -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-blue-600/10 rounded-full blur-[128px] pointer-events-none translate-y-1/2"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
            <div class="relative">
                <h1 class="text-4xl md:text-6xl font-display font-bold mb-4 text-white drop-shadow-lg">
                    {{ __('messages.my_watchlist') }}
                </h1>
                <div class="flex items-center gap-3 text-gray-400 font-light text-lg">
                    <span class="px-3 py-1 rounded-full bg-white/5 border border-white/10 text-sm font-medium text-primary backdrop-blur-sm">
                        {{ $watchlist->count() }} Items
                    </span>
                    <span class="w-1 h-1 rounded-full bg-gray-600"></span>
                    <p>{{ __('messages.movies_in_watchlist', ['count' => $watchlist->count()]) }}</p>
                </div>
            </div>
            
            <a href="{{ route('movies.index') }}" class="group px-6 py-3 bg-white/5 hover:bg-primary border border-white/10 hover:border-primary text-white rounded-full transition-all duration-300 flex items-center gap-2 backdrop-blur-md shadow-lg hover:shadow-primary/25">
                <span class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center group-hover:bg-white/30 transition-colors">
                    <i class="fas fa-plus text-xs"></i>
                </span>
                <span class="font-medium tracking-wide">{{ __('messages.add_movie') }}</span>
            </a>
        </div>

        @if(session('success'))
            <div class="mb-8 animate-fade-in-down">
                <div class="px-6 py-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center gap-4 backdrop-blur-sm shadow-lg">
                    <div class="w-8 h-8 rounded-full bg-emerald-500/20 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-check"></i>
                    </div>
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if($watchlist->isEmpty())
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center py-20 md:py-32 text-center animate-fade-in-up">
                <div class="relative mb-8 group">
                    <div class="absolute inset-0 bg-primary/20 blur-2xl rounded-full group-hover:bg-primary/30 transition-all duration-500"></div>
                    <div class="relative w-24 h-24 bg-gradient-to-br from-gray-800 to-gray-900 rounded-3xl border border-white/10 flex items-center justify-center shadow-2xl group-hover:-translate-y-2 transition-transform duration-300">
                        <i class="fas fa-film text-4xl text-gray-600 group-hover:text-primary transition-colors duration-300"></i>
                    </div>
                    <!-- Floating Elements -->
                    <div class="absolute -top-2 -right-2 w-8 h-8 bg-gray-800 rounded-xl border border-white/10 flex items-center justify-center shadow-lg animate-bounce" style="animation-delay: 0.1s">
                        <i class="fas fa-plus text-xs text-gray-400"></i>
                    </div>
                    <div class="absolute -bottom-2 -left-2 w-8 h-8 bg-gray-800 rounded-xl border border-white/10 flex items-center justify-center shadow-lg animate-bounce" style="animation-delay: 0.2s">
                        <i class="fas fa-heart text-xs text-gray-400"></i>
                    </div>
                </div>
                
                <h3 class="text-2xl md:text-3xl font-bold text-white mb-3">{{ __('messages.watchlist_empty') }}</h3>
                <p class="text-gray-400 mb-10 max-w-md text-lg leading-relaxed">{{ __('messages.watchlist_empty_desc') }}</p>
                
                <a href="{{ route('movies.index') }}" class="px-8 py-4 bg-primary text-white font-bold rounded-full hover:bg-red-700 hover:shadow-[0_0_25px_rgba(229,9,20,0.5)] hover:-translate-y-1 transition-all duration-300 flex items-center gap-3 group">
                    <i class="fas fa-compass text-lg group-hover:rotate-45 transition-transform duration-300"></i>
                    <span>{{ __('messages.browse_movies') }}</span>
                </a>
            </div>
        @else
            <!-- Watchlist Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 md:gap-8">
                @foreach($watchlist as $index => $item)
                    <div class="group relative animate-fade-in-up" style="animation-delay: {{ $index * 100 }}ms">
                        <div class="relative aspect-[2/3] rounded-2xl overflow-hidden bg-gray-900 shadow-2xl border border-white/5 transition-all duration-500 group-hover:shadow-[0_0_30px_rgba(0,0,0,0.5)] group-hover:border-white/20">
                            
                            <!-- Poster Image -->
                            @if($item->poster_path)
                                <img src="https://image.tmdb.org/t/p/w500{{ $item->poster_path }}" 
                                    alt="{{ $item->title }}"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 group-hover:brightness-75"
                                    loading="lazy">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-gray-800 to-gray-900 flex flex-col items-center justify-center p-4 text-center">
                                    <i class="fas fa-film text-4xl text-gray-700 mb-2"></i>
                                    <span class="text-xs text-gray-500 font-mono">NO POSTER</span>
                                </div>
                            @endif

                            <!-- Hover Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col justify-between p-4 translate-y-4 group-hover:translate-y-0">
                                
                                <!-- Top Action -->
                                <div class="flex justify-end">
                                    <form action="{{ route('watchlist.destroy', $item->movie_id) }}" method="POST" 
                                        onsubmit="return confirm('{{ __('messages.remove_confirmation') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                            class="w-9 h-9 rounded-full bg-black/50 backdrop-blur-md border border-white/10 text-gray-300 hover:text-red-500 hover:bg-white/10 flex items-center justify-center transition-all hover:scale-110 hover:rotate-12"
                                            title="{{ __('messages.remove_from_watchlist') }}">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Bottom Content -->
                                <div>
                                    <h3 class="font-display font-bold text-lg leading-tight text-white mb-1 line-clamp-2 drop-shadow-md">
                                        {{ $item->title }}
                                    </h3>
                                    <div class="flex items-center gap-2 text-xs text-gray-300 mb-3">
                                        <i class="far fa-clock text-primary"></i>
                                        <span>{{ __('messages.added_time', ['time' => $item->created_at->diffForHumans()]) }}</span>
                                    </div>
                                    
                                    <a href="{{ route('movies.show', $item->movie_id) }}" 
                                        class="block w-full py-2.5 text-center bg-white text-black font-bold text-sm rounded-xl hover:bg-primary hover:text-white transition-colors shadow-lg">
                                        {{ __('messages.details') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Tips Section -->
            <div class="mt-20 relative overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-r from-gray-900 to-gray-800 p-8 md:p-12">
                <div class="absolute top-0 right-0 -mt-16 -mr-16 w-64 h-64 bg-primary/10 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="flex items-start gap-6">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary to-red-700 flex items-center justify-center shadow-lg shadow-primary/25 flex-shrink-0 transform rotate-3">
                            <i class="fas fa-lightbulb text-2xl text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">{{ __('messages.tips_title') }}</h3>
                            <p class="text-gray-400 leading-relaxed max-w-lg">{{ __('messages.tips_desc') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('movies.trending') }}" class="whitespace-nowrap px-8 py-4 bg-white/5 hover:bg-white/10 border border-white/10 text-white font-semibold rounded-xl transition-all duration-300 flex items-center gap-3 hover:scale-105">
                        <i class="fas fa-fire text-primary"></i>
                        {{ __('messages.view_trending') }}
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }
</style>
@endsection
