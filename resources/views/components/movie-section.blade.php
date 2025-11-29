@props(['title', 'movies', 'route', 'id', 'iconColor' => 'primary'])

<div class="relative group/section">
    <div class="flex justify-between items-end mb-6 px-2">
        <h2 class="text-2xl md:text-3xl font-display font-bold text-white flex items-center gap-3">
            <span class="w-1 h-8 bg-{{ $iconColor }} rounded-full"></span>
            {{ $title }}
        </h2>
        <a href="{{ $route }}" class="text-sm font-medium text-gray-400 hover:text-{{ $iconColor }} transition-colors flex items-center gap-2 group/link">
            {{ __('messages.view_all') }} <i class="fas fa-arrow-right transform group-hover/link:translate-x-1 transition-transform"></i>
        </a>
    </div>
    
    <div class="relative">
        <button class="absolute left-0 top-1/2 -translate-y-1/2 z-20 w-12 h-full bg-gradient-to-r from-black to-transparent opacity-0 group-hover/section:opacity-100 transition-opacity flex items-center justify-start pl-2 text-white/70 hover:text-{{ $iconColor }}" onclick="scrollHorizontal('{{ $id }}', -300)">
            <i class="fas fa-chevron-left text-2xl drop-shadow-lg"></i>
        </button>
        
        <div class="flex overflow-x-auto gap-5 pb-8 pt-2 snap-x snap-mandatory hide-scrollbar scroll-smooth" id="{{ $id }}">
            @foreach(array_slice($movies, 0, 10) as $movie)
                <div class="min-w-[160px] md:min-w-[220px] snap-start transform transition-transform duration-300 hover:scale-105 hover:z-10">
                    <x-movie-card :movie="$movie" />
                </div>
            @endforeach
        </div>

        <button class="absolute right-0 top-1/2 -translate-y-1/2 z-20 w-12 h-full bg-gradient-to-l from-black to-transparent opacity-0 group-hover/section:opacity-100 transition-opacity flex items-center justify-end pr-2 text-white/70 hover:text-{{ $iconColor }}" onclick="scrollHorizontal('{{ $id }}', 300)">
            <i class="fas fa-chevron-right text-2xl drop-shadow-lg"></i>
        </button>
    </div>
</div>