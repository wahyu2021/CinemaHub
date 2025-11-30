@extends('layouts.app')

@section('title', $movie['title'] . ' - CinemaHub')

@section('content')
<!-- Backdrop Hero Section -->
<div class="relative min-h-screen flex items-end pb-20 overflow-hidden rounded-2xl">
    <!-- Background Image with Parallax -->
    <div class="absolute inset-0 z-0">
        @if($movie['backdrop_path'])
            <div class="absolute inset-0 bg-cover bg-center transform scale-105" 
                 style="background-image: url('https://image.tmdb.org/t/p/original{{ $movie['backdrop_path'] }}'); filter: brightness(0.4);">
            </div>
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-[#0a0a0a] to-[#1a1a1a]"></div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-[#050505] via-[#050505]/80 to-transparent"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
    </div>
    
    <!-- Content -->
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pt-32">
        <div class="flex flex-col md:flex-row gap-12 items-start">
            <!-- Poster -->
            @if($movie['poster_path'])
                <div class="w-72 shrink-0 hidden md:block rounded-2xl overflow-hidden shadow-[0_0_40px_rgba(0,0,0,0.5)] border border-white/10 reveal">
                    <img 
                        src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" 
                        alt="{{ $movie['title'] }}"
                        class="w-full h-full object-cover transform hover:scale-110 transition-transform duration-700"
                    >
                </div>
            @endif
            
            <!-- Info -->
            <div class="flex-1 text-white reveal delay-100">
                <!-- Breadcrumbs/Badges -->
                <div class="flex flex-wrap items-center gap-3 mb-6">
                    @if(isset($movie['status']))
                        <span class="px-3 py-1 rounded-full glass text-xs font-bold tracking-wider uppercase border-primary/30 text-primary">
                            {{ $movie['status'] }}
                        </span>
                    @endif
                    <span class="text-gray-400 text-sm font-mono">
                        {{ $movie['release_date'] ? date('d M Y', strtotime($movie['release_date'])) : 'N/A' }}
                    </span>
                    <span class="text-gray-400 text-sm font-mono">•</span>
                    <span class="text-gray-400 text-sm font-mono">{{ $movie['runtime'] ?? 'N/A' }} min</span>
                </div>

                <h1 class="text-5xl md:text-7xl font-display font-bold mb-6 leading-none text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-400">
                    {{ $movie['title'] }}
                </h1>
                
                <!-- Genres -->
                <div class="flex flex-wrap gap-3 mb-8">
                    @foreach($movie['genres'] ?? [] as $genre)
                        <span class="px-4 py-2 rounded-lg bg-white/5 border border-white/10 hover:bg-white/10 hover:border-primary/50 transition-all cursor-default text-sm">
                            {{ $genre['name'] }}
                        </span>
                    @endforeach
                </div>

                <!-- Rating -->
                <div class="flex items-center gap-6 mb-8 glass inline-flex px-6 py-3 rounded-2xl border-white/5">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-star text-yellow-400 text-xl"></i>
                        <span class="text-2xl font-bold">{{ number_format($movie['vote_average'], 1) }}</span>
                        <span class="text-xs text-gray-400 uppercase tracking-wide mt-1">/ 10 TMDB</span>
                    </div>
                    <div class="w-px h-8 bg-white/10"></div>
                    <div class="flex flex-col">
                        <span class="text-sm font-bold counter" data-target="{{ $movie['vote_count'] ?? 0 }}">0</span>
                        <span class="text-xs text-gray-400 uppercase">Votes</span>
                    </div>
                </div>

                <h3 class="text-xl font-bold mb-3 text-gray-300">Synopsis</h3>
                <p class="text-gray-300 text-lg leading-relaxed mb-10 max-w-3xl font-light">
                    {{ $movie['overview'] ?? 'Synopsis not available.' }}
                </p>

                <!-- Actions -->
                <div class="flex flex-wrap gap-4">
                    @guest
                        <a href="{{ route('login') }}" class="px-8 py-4 bg-primary text-white font-bold rounded-xl hover:bg-red-700 hover:shadow-[0_0_20px_rgba(229,9,20,0.4)] transition-all flex items-center gap-3 group">
                            <i class="fas fa-plus group-hover:rotate-90 transition-transform"></i> Add to Watchlist
                        </a>
                    @else
                        @if($inWatchlist)
                            <form action="{{ route('watchlist.destroy', $movie['id']) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-8 py-4 glass border-green-500/50 text-green-400 font-bold rounded-xl hover:bg-green-500/20 transition-all flex items-center gap-3">
                                    <i class="fas fa-check"></i> In Watchlist
                                </button>
                            </form>
                        @else
                            <form action="{{ route('watchlist.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="movie_id" value="{{ $movie['id'] }}">
                                <input type="hidden" name="title" value="{{ $movie['title'] }}">
                                <input type="hidden" name="poster_path" value="{{ $movie['poster_path'] }}">
                                <button type="submit" class="px-8 py-4 bg-primary text-white font-bold rounded-xl hover:bg-red-700 hover:shadow-[0_0_20px_rgba(229,9,20,0.4)] transition-all flex items-center gap-3 group">
                                    <i class="fas fa-plus group-hover:rotate-90 transition-transform"></i> Add to Watchlist
                                </button>
                            </form>
                        @endif
                    @endguest
                    
                    @php
                        $hasTrailers = false;
                        $trailerCount = 0;
                        if (isset($movie['videos']['results'])) {
                            $youtubeTrailers = array_filter($movie['videos']['results'], function($video) {
                                    return $video['site'] === 'YouTube' && in_array($video['type'], ['Trailer', 'Teaser']);
                            });
                            $trailerCount = count($youtubeTrailers);
                            $hasTrailers = $trailerCount > 0;
                        }
                    @endphp
                    
                    @if($hasTrailers)
                        <button onclick="scrollToTrailers()" class="px-8 py-4 glass text-white font-bold rounded-xl hover:bg-white hover:text-black transition-all flex items-center gap-3">
                            <i class="fas fa-play"></i> Watch Trailer <span class="bg-white/20 px-2 py-0.5 rounded text-xs ml-1">{{ $trailerCount }}</span>
                        </button>
                    @endif
                    
                    <button onclick="shareMovie('{{ addslashes($movie['title']) }}', {{ $movie['id'] }})" class="px-6 py-4 glass text-white font-bold rounded-xl hover:bg-white/10 transition-all">
                        <i class="fas fa-share-alt"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative z-10">
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-20 reveal">
        @if(isset($movie['budget']) && $movie['budget'] > 0)
            <div class="glass p-6 rounded-2xl border border-white/5 hover:border-primary/30 transition-colors group">
                <h3 class="text-gray-400 text-xs uppercase tracking-widest mb-2">Budget</h3>
                <p class="text-2xl font-display font-bold group-hover:text-primary transition-colors counter-currency" data-target="{{ $movie['budget'] }}">0</p>
            </div>
        @endif
        @if(isset($movie['revenue']) && $movie['revenue'] > 0)
            <div class="glass p-6 rounded-2xl border border-white/5 hover:border-primary/30 transition-colors group">
                <h3 class="text-gray-400 text-xs uppercase tracking-widest mb-2">Revenue</h3>
                <p class="text-2xl font-display font-bold group-hover:text-green-400 transition-colors">{{ usd_to_idr($movie['revenue']) }}</p>
            </div>
        @endif
        <div class="glass p-6 rounded-2xl border border-white/5 hover:border-primary/30 transition-colors group">
            <h3 class="text-gray-400 text-xs uppercase tracking-widest mb-2">Original Language</h3>
            <p class="text-2xl font-display font-bold uppercase group-hover:text-primary transition-colors">{{ $movie['original_language'] ?? 'N/A' }}</p>
        </div>
        <div class="glass p-6 rounded-2xl border border-white/5 hover:border-primary/30 transition-colors group">
            <h3 class="text-gray-400 text-xs uppercase tracking-widest mb-2">Popularity Index</h3>
            <p class="text-2xl font-display font-bold group-hover:text-primary transition-colors">{{ number_format($movie['popularity']) }}</p>
        </div>
    </div>

    <!-- Trailers Section -->
    @if(isset($movie['videos']['results']) && count($movie['videos']['results']) > 0)
        @php
            $trailers = array_filter($movie['videos']['results'], function($video) {
                return $video['site'] === 'YouTube' && in_array($video['type'], ['Trailer', 'Teaser']);
            });
        @endphp
        
        @if(count($trailers) > 0)
            <div id="trailers-section" class="mb-20 reveal">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-1 h-8 bg-primary rounded-full"></div>
                    <h2 class="text-3xl font-display font-bold">Trailers & Clips</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($trailers as $video)
                        <div class="glass rounded-xl overflow-hidden group cursor-pointer border border-white/5 hover:border-primary transition-all"
                             onclick="playTrailer('{{ $video['key'] }}', '{{ addslashes($video['name']) }}')">
                            <div class="aspect-video relative overflow-hidden">
                                <img 
                                    src="https://img.youtube.com/vi/{{ $video['key'] }}/hqdefault.jpg" 
                                    alt="{{ $video['name'] }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                                    loading="lazy"
                                >
                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center group-hover:bg-black/30 transition">
                                    <div class="w-16 h-16 bg-primary/90 rounded-full flex items-center justify-center group-hover:scale-110 transition shadow-[0_0_20px_rgba(229,9,20,0.5)]">
                                        <i class="fas fa-play text-white text-xl ml-1"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="p-5">
                                <h3 class="font-bold line-clamp-1 group-hover:text-primary transition">{{ $video['name'] }}</h3>
                                <p class="text-gray-500 text-sm mt-1 font-mono">
                                    {{ isset($video['published_at']) ? date('d M Y', strtotime($video['published_at'])) : '' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endif

    <!-- Cast -->
    @if(isset($movie['credits']['cast']) && count($movie['credits']['cast']) > 0)
        <div class="mb-20 relative reveal">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-4">
                    <div class="w-1 h-8 bg-primary rounded-full"></div>
                    <h2 class="text-3xl font-display font-bold">Top Cast</h2>
                </div>
                <div class="flex gap-2">
                    <button onclick="scrollCast('left')" class="w-10 h-10 rounded-full glass hover:bg-white hover:text-black transition flex items-center justify-center">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button onclick="scrollCast('right')" class="w-10 h-10 rounded-full glass hover:bg-white hover:text-black transition flex items-center justify-center">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
            <div id="cast-container" class="flex overflow-x-auto space-x-6 pb-8 scrollbar-hide scroll-smooth">
                @foreach(array_slice($movie['credits']['cast'], 0, 15) as $cast)
                    <div class="flex-shrink-0 w-44 group">
                        <div class="glass rounded-2xl overflow-hidden h-full border border-white/5 hover:border-primary/50 transition-all duration-300">
                            <div class="aspect-[2/3] overflow-hidden">
                                @if($cast['profile_path'])
                                    <img 
                                        src="https://image.tmdb.org/t/p/w185{{ $cast['profile_path'] }}" 
                                        alt="{{ $cast['name'] }}"
                                        class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500 group-hover:scale-110"
                                    >
                                @else
                                    <div class="w-full h-full bg-gray-800 flex items-center justify-center">
                                        <i class="fas fa-user text-4xl text-gray-600"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <p class="font-bold text-sm line-clamp-1 group-hover:text-primary transition-colors" title="{{ $cast['name'] }}">{{ $cast['name'] }}</p>
                                <p class="text-gray-500 text-xs line-clamp-1 mt-1 font-mono" title="{{ $cast['character'] }}">{{ $cast['character'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Recommendations -->
    @if(isset($movie['recommendations']['results']) && count($movie['recommendations']['results']) > 0)
        <div class="reveal">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-1 h-8 bg-primary rounded-full"></div>
                <h2 class="text-3xl font-display font-bold">You Might Also Like</h2>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
                @foreach(array_slice($movie['recommendations']['results'], 0, 12) as $rec)
                    <x-movie-card :movie="$rec" :showBadges="false" />
                @endforeach
            </div>
        </div>
    @endif
</div>

<!-- Trailer Modal -->
<div id="trailer-modal" class="hidden fixed inset-0 bg-black/95 z-[100] flex items-center justify-center p-4 backdrop-blur-xl opacity-0 transition-opacity duration-300" onclick="closeTrailer()">
    <div class="max-w-6xl w-full transform scale-95 transition-transform duration-300" id="trailer-content" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-6">
            <h3 id="trailer-title" class="text-white text-2xl font-display font-bold"></h3>
            <button onclick="closeTrailer()" class="w-10 h-10 rounded-full glass hover:bg-red-600 transition flex items-center justify-center">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="aspect-video bg-black rounded-2xl overflow-hidden shadow-2xl border border-white/10">
            <iframe 
                id="trailer-iframe" 
                class="w-full h-full" 
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
            ></iframe>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function playTrailer(key, name = '') {
        const modal = document.getElementById('trailer-modal');
        const content = document.getElementById('trailer-content');
        const iframe = document.getElementById('trailer-iframe');
        const title = document.getElementById('trailer-title');
        
        iframe.src = `https://www.youtube.com/embed/${key}?autoplay=1&rel=0`;
        title.textContent = name || 'Trailer';
        
        modal.classList.remove('hidden');
        // Trigger animation
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }, 10);
        
        document.body.style.overflow = 'hidden';
    }

    function closeTrailer() {
        const modal = document.getElementById('trailer-modal');
        const content = document.getElementById('trailer-content');
        const iframe = document.getElementById('trailer-iframe');
        
        modal.classList.add('opacity-0');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        
        setTimeout(() => {
            iframe.src = '';
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 300);
    }
    
    function scrollToTrailers() {
        const section = document.getElementById('trailers-section');
        if (section) {
            section.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
    
    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeTrailer();
        }
    });
    
    function scrollCast(direction) {
        const container = document.getElementById('cast-container');
        const scrollAmount = 400;
        
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

    // Animated Counters
    const animateCounters = () => {
        const counters = document.querySelectorAll('.counter');
        const currencyCounters = document.querySelectorAll('.counter-currency');
        
        const runCounter = (el, isCurrency = false) => {
            const target = +el.getAttribute('data-target');
            const duration = 2000; // 2 seconds
            const increment = target / (duration / 16); // 60fps
            
            let current = 0;
            
            const updateCount = () => {
                current += increment;
                
                if(current < target) {
                    if(isCurrency) {
                        // Simple IDR formatter
                        el.innerText = 'Rp ' + Math.ceil(current * 15500).toLocaleString('id-ID');
                    } else {
                        el.innerText = Math.ceil(current).toLocaleString();
                    }
                    requestAnimationFrame(updateCount);
                } else {
                    if(isCurrency) {
                         // Final format exact value
                        el.innerText = 'Rp ' + (target * 15500).toLocaleString('id-ID');
                    } else {
                        el.innerText = target.toLocaleString();
                    }
                }
            };
            updateCount();
        };

        // Observer to start animation when visible
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if(entry.isIntersecting) {
                    if(entry.target.classList.contains('counter')) runCounter(entry.target);
                    if(entry.target.classList.contains('counter-currency')) runCounter(entry.target, true);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(c => observer.observe(c));
        currencyCounters.forEach(c => observer.observe(c));
    };

    document.addEventListener('DOMContentLoaded', animateCounters);
</script>
@endpush
@endsection
