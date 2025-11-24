@extends('layouts.app')

@section('title', $movie['title'] . ' - CinemaHub')

@section('content')
<!-- Backdrop Hero Section -->
<div class="relative h-[70vh] overflow-hidden">
    @if($movie['backdrop_path'])
        <img 
            src="https://image.tmdb.org/t/p/original{{ $movie['backdrop_path'] }}" 
            alt="{{ $movie['title'] }}"
            class="w-full h-full object-cover"
        >
        <div class="absolute inset-0 bg-gradient-to-t from-darker via-darker/80 to-transparent"></div>
    @else
        <div class="w-full h-full bg-gradient-to-br from-gray-900 to-gray-800"></div>
    @endif
    
    <!-- Content -->
    <div class="absolute inset-0 flex items-end">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 w-full">
            <div class="flex flex-col md:flex-row gap-8 items-end">
                <!-- Poster -->
                @if($movie['poster_path'])
                    <img 
                        src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" 
                        alt="{{ $movie['title'] }}"
                        class="w-64 rounded-lg shadow-2xl hidden md:block"
                    >
                @endif
                
                <!-- Info -->
                <div class="flex-1">
                    <h1 class="text-5xl font-bold mb-4">{{ $movie['title'] }}</h1>
                    
                    <div class="flex flex-wrap items-center gap-4 mb-6">
                        <div class="flex items-center bg-yellow-500 text-black px-3 py-1 rounded-full font-semibold">
                            <i class="fas fa-star mr-2"></i>
                            {{ number_format($movie['vote_average'], 1) }} / 10
                        </div>
                        <span class="text-gray-300">{{ $movie['release_date'] ? date('Y', strtotime($movie['release_date'])) : 'N/A' }}</span>
                        <span class="text-gray-300">{{ $movie['runtime'] ?? 'N/A' }} min</span>
                        @if(isset($movie['status']))
                            <span class="bg-primary px-3 py-1 rounded-full text-sm">{{ $movie['status'] }}</span>
                        @endif
                    </div>

                    <!-- Genres -->
                    <div class="flex flex-wrap gap-2 mb-6">
                        @foreach($movie['genres'] ?? [] as $genre)
                            <span class="bg-dark border border-gray-700 px-4 py-1 rounded-full text-sm">
                                {{ $genre['name'] }}
                            </span>
                        @endforeach
                    </div>

                    <!-- Star Rating Display -->
                    <div class="star-rating mb-4 flex items-center gap-2">
                        @for($i = 1; $i <= 5; $i++)
                            @php
                                $rating = ($movie['vote_average'] ?? 0) / 2;
                                $filled = $i <= floor($rating);
                                $half = !$filled && $i <= ceil($rating);
                            @endphp
                            <i class="fas {{ $filled ? 'fa-star' : ($half ? 'fa-star-half-alt' : 'fa-star') }} text-2xl {{ $filled || $half ? 'text-yellow-400' : 'text-gray-600' }}"></i>
                        @endfor
                        <span class="text-lg ml-2 text-gray-300">({{ number_format($movie['vote_count'] ?? 0) }} suara)</span>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-wrap gap-4">
                        <button 
                            onclick="toggleFavorite({{ $movie['id'] }}, '{{ addslashes($movie['title']) }}', '{{ $movie['poster_path'] }}')"
                            data-movie-id="{{ $movie['id'] }}"
                            class="bg-primary hover:bg-red-700 px-6 py-3 rounded-lg font-semibold transition flex items-center group"
                        >
                            <i class="far fa-heart mr-2 fav-icon group-hover:scale-110 transition"></i> Tambah ke Favorit
                        </button>
                        
                        <button 
                            onclick="toggleWatchLater({{ $movie['id'] }}, '{{ addslashes($movie['title']) }}', '{{ $movie['poster_path'] }}')"
                            data-watchlater-id="{{ $movie['id'] }}"
                            class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-lg font-semibold transition flex items-center group"
                        >
                            <i class="far fa-clock mr-2 watch-icon group-hover:scale-110 transition"></i> Tonton Nanti
                        </button>
                        
                        @if(isset($movie['videos']['results'][0]))
                            <button 
                                onclick="playTrailer('{{ $movie['videos']['results'][0]['key'] }}')"
                                class="bg-white text-black hover:bg-gray-200 px-6 py-3 rounded-lg font-semibold transition flex items-center"
                            >
                                <i class="fas fa-play mr-2"></i> Tonton Trailer
                            </button>
                        @endif
                        
                        <button 
                            onclick="shareMovie('{{ addslashes($movie['title']) }}', {{ $movie['id'] }})"
                            class="bg-gray-700 hover:bg-gray-600 px-6 py-3 rounded-lg font-semibold transition flex items-center"
                        >
                            <i class="fas fa-share-alt mr-2"></i> Bagikan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Overview -->
    <div class="mb-12">
        <h2 class="text-3xl font-bold mb-4">Sinopsis</h2>
        <p class="text-gray-300 text-lg leading-relaxed">{{ $movie['overview'] ?? 'Sinopsis tidak tersedia.' }}</p>
    </div>

    <!-- Additional Info -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-12">
        @if(isset($movie['budget']) && $movie['budget'] > 0)
            <div class="bg-dark p-6 rounded-lg border border-gray-800">
                <h3 class="text-gray-400 text-sm mb-2">Anggaran</h3>
                <p class="text-xl font-semibold">${{ number_format($movie['budget']) }}</p>
            </div>
        @endif
        @if(isset($movie['revenue']) && $movie['revenue'] > 0)
            <div class="bg-dark p-6 rounded-lg border border-gray-800">
                <h3 class="text-gray-400 text-sm mb-2">Pendapatan</h3>
                <p class="text-xl font-semibold">${{ number_format($movie['revenue']) }}</p>
            </div>
        @endif
        @if(isset($movie['vote_count']))
            <div class="bg-dark p-6 rounded-lg border border-gray-800">
                <h3 class="text-gray-400 text-sm mb-2">Suara</h3>
                <p class="text-xl font-semibold">{{ number_format($movie['vote_count']) }}</p>
            </div>
        @endif
        @if(isset($movie['popularity']))
            <div class="bg-dark p-6 rounded-lg border border-gray-800">
                <h3 class="text-gray-400 text-sm mb-2">Popularitas</h3>
                <p class="text-xl font-semibold">{{ number_format($movie['popularity']) }}</p>
            </div>
        @endif
    </div>

    <!-- Cast -->
    @if(isset($movie['credits']['cast']) && count($movie['credits']['cast']) > 0)
        <div class="mb-12 relative">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-3xl font-bold">Pemeran Utama</h2>
                <div class="flex gap-2">
                    <button onclick="scrollCast('left')" class="bg-dark hover:bg-gray-800 p-2 rounded-lg transition">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button onclick="scrollCast('right')" class="bg-dark hover:bg-gray-800 p-2 rounded-lg transition">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
            <div id="cast-container" class="flex overflow-x-auto space-x-4 pb-4 scrollbar-hide scroll-smooth">
                @foreach(array_slice($movie['credits']['cast'], 0, 20) as $cast)
                    <div class="flex-shrink-0 w-40">
                        <div class="bg-dark rounded-lg overflow-hidden border border-gray-800 h-full">
                            <div class="aspect-[2/3] overflow-hidden">
                                @if($cast['profile_path'])
                                    <img 
                                        src="https://image.tmdb.org/t/p/w185{{ $cast['profile_path'] }}" 
                                        alt="{{ $cast['name'] }}"
                                        class="w-full h-full object-cover"
                                    >
                                @else
                                    <div class="w-full h-full bg-gray-800 flex items-center justify-center">
                                        <i class="fas fa-user text-4xl text-gray-600"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="p-3">
                                <p class="font-semibold text-sm line-clamp-1" title="{{ $cast['name'] }}">{{ $cast['name'] }}</p>
                                <p class="text-gray-400 text-xs line-clamp-2" title="{{ $cast['character'] }}">{{ $cast['character'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Recommendations -->
    @if(isset($movie['recommendations']['results']) && count($movie['recommendations']['results']) > 0)
        <div class="mb-12">
            <h2 class="text-3xl font-bold mb-6">Rekomendasi Untuk Anda</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach(array_slice($movie['recommendations']['results'], 0, 12) as $rec)
                    <a href="{{ route('movies.show', $rec['id']) }}" class="movie-card group block">
                        <div class="relative overflow-hidden rounded-lg aspect-[2/3]">
                            @if($rec['poster_path'])
                                <img 
                                    src="https://image.tmdb.org/t/p/w342{{ $rec['poster_path'] }}" 
                                    alt="{{ $rec['title'] }}"
                                    class="w-full h-full object-cover"
                                >
                            @else
                                <div class="w-full h-full bg-gray-800 flex items-center justify-center">
                                    <i class="fas fa-film text-4xl text-gray-600"></i>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-75 transition-all flex items-center justify-center">
                                <i class="fas fa-play text-3xl opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </div>
                        </div>
                        <h3 class="text-sm mt-2 line-clamp-2" title="{{ $rec['title'] }}">{{ $rec['title'] }}</h3>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>

<!-- Trailer Modal -->
<div id="trailer-modal" class="hidden fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4">
    <div class="max-w-5xl w-full">
        <div class="flex justify-end mb-4">
            <button onclick="closeTrailer()" class="text-white hover:text-primary text-2xl">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="aspect-video">
            <iframe 
                id="trailer-iframe" 
                class="w-full h-full rounded-lg" 
                frameborder="0" 
                allowfullscreen
            ></iframe>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function playTrailer(key) {
        const modal = document.getElementById('trailer-modal');
        const iframe = document.getElementById('trailer-iframe');
        iframe.src = `https://www.youtube.com/embed/${key}?autoplay=1`;
        modal.classList.remove('hidden');
    }

    function closeTrailer() {
        const modal = document.getElementById('trailer-modal');
        const iframe = document.getElementById('trailer-iframe');
        iframe.src = '';
        modal.classList.add('hidden');
    }
    
    function scrollCast(direction) {
        const container = document.getElementById('cast-container');
        const scrollAmount = 300;
        
        if (direction === 'left') {
            container.scrollBy({
                left: -scrollAmount,
                behavior: 'smooth'
            });
        } else {
            container.scrollBy({
                left: scrollAmount,
                behavior: 'smooth'
            });
        }
    }
</script>
@endpush
@endsection
