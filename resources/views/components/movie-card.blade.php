@props(['movie', 'showBadges' => true])

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
            
            @if($showBadges)
                <!-- Quality/Status Badges -->
                @if(isset($movie['release_date']) && \Carbon\Carbon::parse($movie['release_date'])->diffInDays(now()) < 30)
                    <div class="absolute top-2 right-14 bg-primary rounded-full px-2 py-1 text-xs font-bold badge-pulse">
                        BARU
                    </div>
                @endif
                @if(isset($movie['vote_average']) && $movie['vote_average'] >= 8)
                    <div class="absolute top-12 left-2 bg-yellow-500 text-black rounded-full px-2 py-1 text-xs font-bold">
                        TOP
                    </div>
                @endif
            @endif
        </div>
    </a>
    
    <!-- Action Buttons -->
    <div class="action-buttons absolute top-2 right-2 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
        <!-- Favorite Button -->
        <button 
            onclick="event.stopPropagation(); toggleFavorite({{ $movie['id'] }}, '{{ addslashes($movie['title']) }}', '{{ $movie['poster_path'] }}')"
            data-movie-id="{{ $movie['id'] }}"
            class="bg-black bg-opacity-75 rounded-full p-2 hover:scale-110 transition"
            title="Tambah ke Favorit"
        >
            <i class="far fa-heart text-white fav-icon"></i>
        </button>
        
        <!-- Watch Later Button -->
        <button 
            onclick="event.stopPropagation(); toggleWatchLater({{ $movie['id'] }}, '{{ addslashes($movie['title']) }}', '{{ $movie['poster_path'] }}')"
            data-watchlater-id="{{ $movie['id'] }}"
            class="bg-black bg-opacity-75 rounded-full p-2 hover:scale-110 transition"
            title="Tonton Nanti"
        >
            <i class="far fa-clock text-white watch-icon"></i>
        </button>
        
        <!-- Share Button -->
        <button 
            onclick="event.stopPropagation(); shareMovie('{{ addslashes($movie['title']) }}', {{ $movie['id'] }})"
            class="bg-black bg-opacity-75 rounded-full p-2 hover:scale-110 transition"
            title="Bagikan"
        >
            <i class="fas fa-share-alt text-white"></i>
        </button>
    </div>
</div>
