@extends('layouts.app')

@section('title', 'Film Trending - CinemaHub')

@section('content')
<div class="max-w-7xl mx-auto py-8">
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
        <div>
            <h1 class="text-4xl md:text-5xl font-display font-bold mb-2 text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-500">
                <i class="fas fa-fire text-primary"></i> {{ __('messages.trending') }}
            </h1>
            <p class="text-gray-400 font-light flex items-center gap-2">
                <span class="w-8 h-[1px] bg-primary"></span>
                {{ __('messages.trending_description') }}
            </p>
        </div>
    </div>

    @if(count($movies) >= 3)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
            @foreach(array_slice($movies, 0, 3) as $index => $movie)
                <a href="{{ route('movies.show', $movie['id']) }}" class="relative group overflow-hidden rounded-2xl glass-card border border-white/5 hover:border-primary/50 transition-all duration-500">
                    <div class="relative h-80 md:h-96 overflow-hidden">
                        @if($movie['backdrop_path'])
                            <img 
                                src="https://image.tmdb.org/t/p/w780{{ $movie['backdrop_path'] }}" 
                                alt="{{ $movie['title'] }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                            >
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-gray-800 to-gray-900"></div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>
                        
                        <div class="absolute top-4 left-4">
                            <div class="w-14 h-14 rounded-xl bg-primary text-white flex items-center justify-center shadow-lg shadow-primary/30">
                                <span class="text-2xl font-display font-bold">#{{ $index + 1 }}</span>
                            </div>
                        </div>

                        <div class="absolute bottom-0 left-0 right-0 p-6">
                            <h3 class="text-2xl font-display font-bold mb-3 group-hover:text-primary transition-colors line-clamp-2">{{ $movie['title'] }}</h3>
                            <div class="flex items-center gap-4 text-sm text-gray-300">
                                <span class="flex items-center gap-1 bg-black/50 backdrop-blur-md px-3 py-1 rounded-lg">
                                    <i class="fas fa-star text-yellow-400"></i>
                                    {{ number_format($movie['vote_average'], 1) }}
                                </span>
                                <span class="bg-black/50 backdrop-blur-md px-3 py-1 rounded-lg">
                                    {{ isset($movie['release_date']) ? date('Y', strtotime($movie['release_date'])) : 'N/A' }}
                                </span>
                            </div>
                        </div>

                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <div class="w-16 h-16 rounded-full bg-primary/90 flex items-center justify-center shadow-2xl shadow-primary/50 scale-90 group-hover:scale-100 transition-transform">
                                <i class="fas fa-play text-white text-xl ml-1"></i>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

    <div class="mb-8">
        <div class="flex items-center gap-4 mb-8">
            <div class="w-1 h-8 bg-primary rounded-full"></div>
            <h2 class="text-2xl font-display font-bold">{{ __('messages.all_trending_movies') }}</h2>
        </div>
        <div class="movies-container grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @foreach($movies as $index => $movie)
                <div class="reveal" style="transition-delay: {{ $index * 50 }}ms">
                    <x-movie-card :movie="$movie" />
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
