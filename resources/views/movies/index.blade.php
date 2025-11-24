@extends('layouts.app')

@section('title', 'CinemaHub - Temukan Film')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold mb-2">
            @switch($category)
                @case('now_playing') Sedang Tayang @break
                @case('top_rated') Rating Tertinggi @break
                @case('upcoming') Akan Datang @break
                @default Film Populer
            @endswitch
        </h1>
        <p class="text-gray-400">Temukan film-film menarik untuk ditonton</p>
    </div>

    <!-- Filters -->
    <div class="bg-dark rounded-lg p-6 mb-8 border border-gray-800">
        <form method="GET" action="{{ route('movies.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="hidden" name="category" value="{{ $category }}">
            
            <!-- Genre Filter -->
            <div>
                <label class="block text-sm font-medium mb-2 text-gray-300">Genre</label>
                <select name="genre" class="w-full bg-darker border border-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:border-primary">
                    <option value="">Semua Genre</option>
                    @foreach($genres as $genre)
                        <option value="{{ $genre['id'] }}" {{ $selected_genre == $genre['id'] ? 'selected' : '' }}>
                            {{ $genre['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Sort By -->
            <div>
                <label class="block text-sm font-medium mb-2 text-gray-300">Urutkan</label>
                <select name="sort_by" class="w-full bg-darker border border-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:border-primary">
                    <option value="popularity.desc" {{ $selected_sort === 'popularity.desc' ? 'selected' : '' }}>Popularitas ↓</option>
                    <option value="popularity.asc" {{ $selected_sort === 'popularity.asc' ? 'selected' : '' }}>Popularitas ↑</option>
                    <option value="vote_average.desc" {{ $selected_sort === 'vote_average.desc' ? 'selected' : '' }}>Rating ↓</option>
                    <option value="vote_average.asc" {{ $selected_sort === 'vote_average.asc' ? 'selected' : '' }}>Rating ↑</option>
                    <option value="release_date.desc" {{ $selected_sort === 'release_date.desc' ? 'selected' : '' }}>Tanggal Rilis ↓</option>
                    <option value="release_date.asc" {{ $selected_sort === 'release_date.asc' ? 'selected' : '' }}>Tanggal Rilis ↑</option>
                </select>
            </div>

            <!-- Year Filter -->
            <div>
                <label class="block text-sm font-medium mb-2 text-gray-300">Tahun</label>
                <select name="year" class="w-full bg-darker border border-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:border-primary">
                    <option value="">Semua Tahun</option>
                    @for($y = date('Y') + 1; $y >= 1990; $y--)
                        <option value="{{ $y }}" {{ $selected_year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <!-- Submit Button -->
            <div class="flex items-end">
                <button type="submit" class="w-full bg-primary hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition">
                    <i class="fas fa-filter mr-2"></i> Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Movies Grid -->
    @if(count($movies) > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 mb-8">
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
                            
                            <!-- Overlay -->
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
                            
                            <!-- Rating Badge -->
                            <div class="absolute top-2 left-2 bg-black bg-opacity-75 rounded-full px-2 py-1 text-xs font-semibold flex items-center">
                                <i class="fas fa-star text-yellow-400 mr-1"></i>
                                {{ number_format($movie['vote_average'] ?? 0, 1) }}
                            </div>
                        </div>
                    </a>
                    
                    <!-- Favorite Button -->
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

        <!-- Pagination -->
        @if($total_pages > 1)
            <div class="flex justify-center items-center space-x-2">
                @if($current_page > 1)
                    <a href="{{ route('movies.index', array_merge(request()->all(), ['page' => $current_page - 1])) }}" 
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
                        <a href="{{ route('movies.index', array_merge(request()->all(), ['page' => 1])) }}" 
                           class="bg-dark hover:bg-gray-800 px-4 py-2 rounded-lg transition">1</a>
                        @if($start > 2)
                            <span class="px-4 py-2">...</span>
                        @endif
                    @endif

                    @for($i = $start; $i <= $end; $i++)
                        <a href="{{ route('movies.index', array_merge(request()->all(), ['page' => $i])) }}" 
                           class="px-4 py-2 rounded-lg transition {{ $i == $current_page ? 'bg-primary' : 'bg-dark hover:bg-gray-800' }}">
                            {{ $i }}
                        </a>
                    @endfor

                    @if($end < $total_pages)
                        @if($end < $total_pages - 1)
                            <span class="px-4 py-2">...</span>
                        @endif
                        <a href="{{ route('movies.index', array_merge(request()->all(), ['page' => $total_pages])) }}" 
                           class="bg-dark hover:bg-gray-800 px-4 py-2 rounded-lg transition">{{ $total_pages }}</a>
                    @endif
                </div>

                @if($current_page < $total_pages)
                    <a href="{{ route('movies.index', array_merge(request()->all(), ['page' => $current_page + 1])) }}" 
                       class="bg-dark hover:bg-gray-800 px-4 py-2 rounded-lg transition">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                @endif
            </div>
        @endif
    @else
        <div class="text-center py-20">
            <i class="fas fa-film text-6xl text-gray-600 mb-4"></i>
            <p class="text-gray-400 text-xl">Tidak ada film ditemukan</p>
        </div>
    @endif
</div>
@endsection
