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
                <x-movie-card :movie="$movie" :showBadges="false" />
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
