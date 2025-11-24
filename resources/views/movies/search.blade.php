@extends('layouts.app')

@section('title', 'Pencarian: ' . $query . ' - CinemaHub')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold mb-2">Hasil Pencarian</h1>
        <p class="text-gray-400">Hasil pencarian untuk: <span class="text-white font-semibold">"{{ $query }}"</span></p>
    </div>

    <!-- Enhanced Search -->
    <div class="bg-dark rounded-lg p-6 mb-8 border border-gray-800">
        <form method="GET" action="{{ route('movies.search') }}" class="flex gap-4">
            <div class="flex-1">
                <input 
                    type="text" 
                    name="q" 
                    value="{{ $query }}"
                    placeholder="Cari film..." 
                    class="w-full bg-darker border border-gray-700 rounded-lg px-6 py-3 focus:outline-none focus:border-primary text-lg"
                    autofocus
                >
            </div>
            <button type="submit" class="bg-primary hover:bg-red-700 px-8 py-3 rounded-lg font-semibold transition">
                <i class="fas fa-search mr-2"></i> Cari
            </button>
        </form>
    </div>

    <!-- Results -->
    @if(count($movies) > 0)
        <div class="mb-6">
            <p class="text-gray-400">Menampilkan halaman {{ $current_page }} dari {{ $total_pages }}</p>
        </div>

        <div class="movies-container grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 mb-8">
            @foreach($movies as $movie)
                <div class="movie-card group relative bg-dark rounded-lg overflow-hidden">
                    <a href="{{ route('movies.show', $movie['id']) }}" class="block">
                        <div class="relative overflow-hidden aspect-[2/3]">
                            @if($movie['poster_path'])
                                <img 
                                    src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" 
                                    alt="{{ $movie['title'] }}"
                                    class="w-full h-full object-cover"
                                    loading="lazy"
                                >
                            @else
                                <div class="w-full h-full bg-gray-800 flex items-center justify-center">
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
                        </div>
                    </a>
                    
                    <!-- Action Buttons -->
                    <div class="action-buttons absolute top-2 right-2 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button 
                            onclick="event.stopPropagation(); toggleFavorite({{ $movie['id'] }}, '{{ addslashes($movie['title']) }}', '{{ $movie['poster_path'] }}')"
                            data-movie-id="{{ $movie['id'] }}"
                            class="bg-black bg-opacity-75 rounded-full p-2 hover:scale-110 transition"
                            title="Tambah ke Favorit"
                        >
                            <i class="far fa-heart text-white fav-icon"></i>
                        </button>
                        <button 
                            onclick="event.stopPropagation(); toggleWatchLater({{ $movie['id'] }}, '{{ addslashes($movie['title']) }}', '{{ $movie['poster_path'] }}')"
                            data-watchlater-id="{{ $movie['id'] }}"
                            class="bg-black bg-opacity-75 rounded-full p-2 hover:scale-110 transition"
                            title="Tonton Nanti"
                        >
                            <i class="far fa-clock text-white watch-icon"></i>
                        </button>
                        <button 
                            onclick="event.stopPropagation(); shareMovie('{{ addslashes($movie['title']) }}', {{ $movie['id'] }})"
                            class="bg-black bg-opacity-75 rounded-full p-2 hover:scale-110 transition"
                            title="Bagikan"
                        >
                            <i class="fas fa-share-alt text-white"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($total_pages > 1)
            <div class="flex justify-center items-center space-x-2">
                @if($current_page > 1)
                    <a href="{{ route('movies.search', ['q' => $query, 'page' => $current_page - 1]) }}" 
                       class="bg-dark hover:bg-gray-800 px-4 py-2 rounded-lg transition">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @endif

                <div class="flex space-x-2">
                    @php
                        $start = max(1, $current_page - 2);
                        $end = min($total_pages, $current_page + 2);
                    @endphp

                    @if($start > 1)
                        <a href="{{ route('movies.search', ['q' => $query, 'page' => 1]) }}" 
                           class="bg-dark hover:bg-gray-800 px-4 py-2 rounded-lg transition">1</a>
                        @if($start > 2)
                            <span class="px-4 py-2">...</span>
                        @endif
                    @endif

                    @for($i = $start; $i <= $end; $i++)
                        <a href="{{ route('movies.search', ['q' => $query, 'page' => $i]) }}" 
                           class="px-4 py-2 rounded-lg transition {{ $i == $current_page ? 'bg-primary' : 'bg-dark hover:bg-gray-800' }}">
                            {{ $i }}
                        </a>
                    @endfor

                    @if($end < $total_pages)
                        @if($end < $total_pages - 1)
                            <span class="px-4 py-2">...</span>
                        @endif
                        <a href="{{ route('movies.search', ['q' => $query, 'page' => $total_pages]) }}" 
                           class="bg-dark hover:bg-gray-800 px-4 py-2 rounded-lg transition">{{ $total_pages }}</a>
                    @endif
                </div>

                @if($current_page < $total_pages)
                    <a href="{{ route('movies.search', ['q' => $query, 'page' => $current_page + 1]) }}" 
                       class="bg-dark hover:bg-gray-800 px-4 py-2 rounded-lg transition">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                @endif
            </div>
        @endif
    @else
        <div class="text-center py-20">
            <i class="fas fa-search text-6xl text-gray-600 mb-4"></i>
            <p class="text-gray-400 text-xl mb-2">Tidak ada hasil untuk "{{ $query }}"</p>
            <p class="text-gray-500">Coba kata kunci lain atau periksa ejaan Anda</p>
        </div>
    @endif
</div>
@endsection
