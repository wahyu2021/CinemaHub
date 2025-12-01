@props(['movie', 'showBadges' => true])

<div class="tilt-card relative group h-full cursor-hover transition-all duration-500 ease-out">
    <div class="glass-card rounded-2xl overflow-hidden h-full relative shadow-2xl border border-white/5 group-hover:border-primary/50 group-hover:shadow-[0_0_30px_rgba(229,9,20,0.3)]">
        
        <a href="{{ route('movies.show', $movie['id']) }}" class="block h-full relative z-10">
            <div class="relative overflow-hidden aspect-[2/3]">
                @if($movie['poster_path'])
                    <img 
                        src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" 
                        alt="{{ $movie['title'] }}"
                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 filter brightness-90 group-hover:brightness-110"
                        loading="lazy"
                    >
                @else
                    <div class="w-full h-full bg-gradient-to-br from-gray-900 to-black flex items-center justify-center">
                        <i class="fas fa-film text-6xl text-gray-800 group-hover:text-primary transition-colors"></i>
                    </div>
                @endif
                
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent opacity-90 lg:opacity-60 lg:group-hover:opacity-80 transition-opacity duration-500"></div>

                <div class="absolute inset-0 flex flex-col justify-end p-4 lg:p-5 translate-y-0 lg:translate-y-4 lg:group-hover:translate-y-0 transition-transform duration-500">
                    <div class="absolute top-3 left-3 lg:top-4 lg:left-4 flex items-center gap-1 bg-black/60 backdrop-blur-md px-2 py-0.5 lg:px-2.5 lg:py-1 rounded-lg border border-white/10 group-hover:border-primary/50 transition-colors">
                        <i class="fas fa-star text-yellow-500 text-[10px] lg:text-xs"></i>
                        <span class="text-[10px] lg:text-xs font-bold text-white">{{ number_format($movie['vote_average'] ?? 0, 1) }}</span>
                    </div>

                    @if($showBadges && isset($movie['release_date']) && \Carbon\Carbon::parse($movie['release_date'])->diffInDays(now()) < 30)
                        <div class="absolute top-3 right-3 lg:top-4 lg:right-4 bg-primary text-white text-[8px] lg:text-[10px] font-bold px-1.5 py-0.5 lg:px-2 lg:py-1 rounded uppercase tracking-wider shadow-lg animate-pulse">
                            New
                        </div>
                    @endif

                    <div class="tilt-content overflow-hidden">
                        <h3 class="font-display font-bold text-base lg:text-xl leading-tight text-white mb-1 lg:mb-2 group-hover:text-primary transition-colors drop-shadow-lg line-clamp-1 lg:line-clamp-none">
                            {{ $movie['title'] }}
                        </h3>
                        
                        <div class="flex items-center gap-2 lg:gap-3 text-[10px] lg:text-xs text-gray-300 opacity-100 lg:opacity-0 lg:group-hover:opacity-100 transition-opacity duration-500 delay-100 mb-1 lg:mb-3">
                            <span>{{ isset($movie['release_date']) ? date('Y', strtotime($movie['release_date'])) : 'TBA' }}</span>
                            <span class="w-1 h-1 rounded-full bg-gray-500"></span>
                            <span class="uppercase tracking-wide">Movie</span>
                        </div>

                        <p class="text-[10px] lg:text-xs text-gray-400 opacity-100 lg:opacity-0 lg:group-hover:opacity-100 transition-opacity duration-500 delay-200 hidden md:block overflow-hidden"
                           style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                            {{ $movie['overview'] ?? 'Synopsis not available.' }}
                        </p>
                    </div>
                </div>
            </div>
        </a>

        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 flex items-center gap-3 opacity-0 group-hover:opacity-100 transition-all duration-300 z-20 scale-90 group-hover:scale-100">
            <form action="{{ route('watchlist.store') }}" method="POST">
                @csrf
                <input type="hidden" name="movie_id" value="{{ $movie['id'] }}">
                <input type="hidden" name="title" value="{{ $movie['title'] }}">
                <input type="hidden" name="poster_path" value="{{ $movie['poster_path'] }}">
                <button type="submit" class="w-12 h-12 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white hover:bg-primary hover:border-primary flex items-center justify-center transition-all hover:scale-110 cursor-hover" title="Add to Watchlist">
                    <i class="fas fa-plus"></i>
                </button>
            </form>
            
            <button onclick="shareMovie('{{ addslashes($movie['title']) }}', {{ $movie['id'] }})" class="w-12 h-12 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white hover:bg-white hover:text-black flex items-center justify-center transition-all hover:scale-110 cursor-hover" title="Share">
                <i class="fas fa-share-alt"></i>
            </button>
        </div>
    </div>
</div>