@props(['movie', 'showBadges' => true])

<div class="glass-card group relative rounded-xl overflow-hidden h-full reveal">
    <a href="{{ route('movies.show', $movie['id']) }}" class="block h-full">
        <div class="relative overflow-hidden aspect-[2/3]">
            @if($movie['poster_path'])
                <img 
                    src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" 
                    alt="{{ $movie['title'] }}"
                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                    loading="lazy"
                >
            @else
                <div class="w-full h-full bg-gray-900 flex items-center justify-center">
                    <i class="fas fa-film text-6xl text-gray-700"></i>
                </div>
            @endif
            
            <!-- Glass Overlay (Slide Up) -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 translate-y-4 group-hover:translate-y-0 flex flex-col justify-end p-4">
                <h3 class="font-display font-bold text-lg leading-tight mb-2 text-white group-hover:text-primary transition-colors">{{ $movie['title'] }}</h3>
                
                <div class="flex items-center justify-between text-sm text-gray-300 mb-3">
                    <span>{{ isset($movie['release_date']) ? date('Y', strtotime($movie['release_date'])) : 'N/A' }}</span>
                    <span class="flex items-center text-yellow-400 font-bold">
                        <i class="fas fa-star mr-1 text-xs"></i>
                        {{ number_format($movie['vote_average'] ?? 0, 1) }}
                    </span>
                </div>
                
                <div class="w-full bg-white/20 h-[1px] mb-3"></div>
                
                <p class="text-xs text-gray-400 line-clamp-2 mb-2">
                    {{ $movie['overview'] ?? 'No overview available.' }}
                </p>
            </div>
            
            <!-- Floating Badge -->
            <div class="absolute top-3 left-3 glass px-3 py-1 rounded-lg flex items-center gap-1 backdrop-blur-md border-white/10">
                <i class="fas fa-star text-yellow-400 text-xs"></i>
                <span class="font-bold text-sm">{{ number_format($movie['vote_average'] ?? 0, 1) }}</span>
            </div>
            
            @if($showBadges)
                @if(isset($movie['release_date']) && \Carbon\Carbon::parse($movie['release_date'])->diffInDays(now()) < 30)
                    <div class="absolute top-3 right-3 bg-primary/90 text-white px-3 py-1 rounded-lg text-xs font-bold shadow-[0_0_10px_rgba(229,9,20,0.5)] animate-pulse">
                        NEW
                    </div>
                @endif
            @endif
        </div>
    </a>
    
    <!-- Hover Actions -->
    <div class="absolute top-3 right-3 flex flex-col gap-2 opacity-0 translate-x-4 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300 delay-100 z-20">
        <!-- Favorite -->
        <form action="{{ route('watchlist.store') }}" method="POST" onsubmit="event.stopPropagation();">
            @csrf
            <input type="hidden" name="movie_id" value="{{ $movie['id'] }}">
            <input type="hidden" name="title" value="{{ $movie['title'] }}">
            <input type="hidden" name="poster_path" value="{{ $movie['poster_path'] }}">
            <button type="submit" class="w-10 h-10 rounded-full glass hover:bg-primary hover:border-primary flex items-center justify-center transition-all hover:scale-110 group/btn" title="Add to Watchlist">
                <i class="fas fa-plus text-white group-hover/btn:rotate-90 transition-transform"></i>
            </button>
        </form>
        
        <!-- Share -->
        <button 
            onclick="event.stopPropagation(); shareMovie('{{ addslashes($movie['title']) }}', {{ $movie['id'] }})"
            class="w-10 h-10 rounded-full glass hover:bg-white hover:text-black hover:border-white flex items-center justify-center transition-all hover:scale-110"
            title="Share"
        >
            <i class="fas fa-share-alt"></i>
        </button>
    </div>
</div>
