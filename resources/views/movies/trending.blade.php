@extends('layouts.app')

@section('title', 'Film Trending - CinemaHub')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold mb-2">
            <i class="fas fa-fire text-primary"></i> Sedang Trending Minggu Ini
        </h1>
        <p class="text-gray-400">Film paling populer yang sedang ditonton semua orang</p>
    </div>

    <!-- Featured Top 3 -->
    @if(count($movies) >= 3)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            @foreach(array_slice($movies, 0, 3) as $index => $movie)
                <a href="{{ route('movies.show', $movie['id']) }}" class="relative group overflow-hidden rounded-lg">
                    <div class="relative h-96">
                        @if($movie['backdrop_path'])
                            <img 
                                src="https://image.tmdb.org/t/p/w780{{ $movie['backdrop_path'] }}" 
                                alt="{{ $movie['title'] }}"
                                class="w-full h-full object-cover"
                            >
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-gray-800 to-gray-900"></div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>
                        
                        <!-- Rank Badge -->
                        <div class="absolute top-4 left-4 bg-primary text-white text-4xl font-bold rounded-lg px-4 py-2 shadow-lg">
                            #{{ $index + 1 }}
                        </div>

                        <!-- Info -->
                        <div class="absolute bottom-0 left-0 right-0 p-6">
                            <h3 class="text-2xl font-bold mb-2">{{ $movie['title'] }}</h3>
                            <div class="flex items-center space-x-4 text-sm">
                                <span class="flex items-center">
                                    <i class="fas fa-star text-yellow-400 mr-1"></i>
                                    {{ number_format($movie['vote_average'], 1) }}
                                </span>
                                <span>{{ isset($movie['release_date']) ? date('Y', strtotime($movie['release_date'])) : 'N/A' }}</span>
                            </div>
                        </div>

                        <!-- Play Icon -->
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <i class="fas fa-play-circle text-6xl text-white"></i>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

    <!-- All Trending Movies -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold mb-6">Semua Film Trending</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @foreach($movies as $movie)
                <div class="movie-card group relative">
                    <a href="{{ route('movies.show', $movie['id']) }}" class="block">
                        <div class="relative overflow-hidden rounded-lg shadow-lg">
                            @if($movie['poster_path'])
                                <img 
                                    src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" 
                                    alt="{{ $movie['title'] }}"
                                    class="w-full h-auto object-cover"
                                    loading="lazy"
                                >
                            @else
                                <div class="w-full aspect-[2/3] bg-gray-800 flex items-center justify-center">
                                    <i class="fas fa-film text-6xl text-gray-600"></i>
                                </div>
                            @endif
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="absolute bottom-0 left-0 right-0 p-4">
                                    <h3 class="font-semibold text-sm mb-1 line-clamp-2">{{ $movie['title'] }}</h3>
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-gray-300">{{ isset($movie['release_date']) ? date('Y', strtotime($movie['release_date'])) : 'N/A' }}</span>
                                        <span class="flex items-center text-yellow-400">
                                            <i class="fas fa-star mr-1"></i>
                                            {{ number_format($movie['vote_average'] ?? 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="absolute top-2 left-2 bg-black bg-opacity-75 rounded-full px-2 py-1 text-xs font-semibold flex items-center">
                                <i class="fas fa-star text-yellow-400 mr-1"></i>
                                {{ number_format($movie['vote_average'] ?? 0, 1) }}
                            </div>

                            <!-- Trending Badge -->
                            <div class="absolute bottom-2 left-2 bg-primary rounded-full px-2 py-1 text-xs font-semibold">
                                <i class="fas fa-fire mr-1"></i> Trending
                            </div>
                        </div>
                    </a>
                    
                    <button 
                        onclick="event.stopPropagation(); toggleFavorite({{ $movie['id'] }}, '{{ addslashes($movie['title']) }}', '{{ $movie['poster_path'] }}')"
                        data-movie-id="{{ $movie['id'] }}"
                        class="absolute top-2 right-2 bg-black bg-opacity-75 rounded-full p-2 opacity-0 group-hover:opacity-100 transition-opacity hover:scale-110"
                    >
                        <i class="far fa-heart text-white"></i>
                    </button>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
