@props(['title', 'movies', 'route', 'id', 'iconColor' => 'primary'])

@php
    // Map colors to specific Tailwind classes so the scanner detects them
    $colors = [
        'primary' => [
            'bg' => 'bg-primary',
            'hover_text' => 'hover:text-primary'
        ],
        'blue-500' => [
            'bg' => 'bg-blue-500',
            'hover_text' => 'hover:text-blue-500'
        ],
        'yellow-500' => [
            'bg' => 'bg-yellow-500',
            'hover_text' => 'hover:text-yellow-500'
        ],
        'green-500' => [
            'bg' => 'bg-green-500',
            'hover_text' => 'hover:text-green-500'
        ],
        'purple-500' => [
            'bg' => 'bg-purple-500',
            'hover_text' => 'hover:text-purple-500'
        ],
        'pink-500' => [
            'bg' => 'bg-pink-500',
            'hover_text' => 'hover:text-pink-500'
        ],
        'red-500' => [
            'bg' => 'bg-red-500',
            'hover_text' => 'hover:text-red-500'
        ],
    ];

    // Fallback for unmapped colors (might still fail if not used elsewhere, but handles custom inputs)
    $activeColor = $colors[$iconColor] ?? [
        'bg' => "bg-{$iconColor}",
        'hover_text' => "hover:text-{$iconColor}"
    ];
@endphp

<div class="relative group/section">
    <div class="flex justify-between items-end mb-6 px-2">
        <h2 class="text-2xl md:text-3xl font-display font-bold text-white flex items-center gap-3">
            <span class="w-1 h-8 {{ $activeColor['bg'] }} rounded-full"></span>
            {{ $title }}
        </h2>
        <a href="{{ $route }}" class="text-sm font-medium text-gray-400 {{ $activeColor['hover_text'] }} transition-colors flex items-center gap-2 group/link">
            {{ __('messages.view_all') }} <i class="fas fa-arrow-right transform group-hover/link:translate-x-1 transition-transform"></i>
        </a>
    </div>
    
    <div class="relative">
        <button class="absolute left-0 top-1/2 -translate-y-1/2 z-20 w-12 h-full bg-gradient-to-r from-black to-transparent opacity-0 group-hover/section:opacity-100 transition-opacity flex items-center justify-start pl-2 text-white/70 {{ $activeColor['hover_text'] }}" onclick="scrollHorizontal('{{ $id }}', -300)">
            <i class="fas fa-chevron-left text-2xl drop-shadow-lg"></i>
        </button>
        
        <div class="flex overflow-x-auto gap-5 pb-6 pt-6 snap-x snap-mandatory hide-scrollbar scroll-smooth" id="{{ $id }}">
            @foreach(array_slice($movies, 0, 10) as $movie)
                <div class="min-w-[160px] md:min-w-[220px] snap-start transform transition-transform duration-300 hover:scale-105 hover:z-10">
                    <x-movie-card :movie="$movie" />
                </div>
            @endforeach
        </div>

        <button class="absolute right-0 top-1/2 -translate-y-1/2 z-20 w-12 h-full bg-gradient-to-l from-black to-transparent opacity-0 group-hover/section:opacity-100 transition-opacity flex items-center justify-end pr-2 text-white/70 {{ $activeColor['hover_text'] }}" onclick="scrollHorizontal('{{ $id }}', 300)">
            <i class="fas fa-chevron-right text-2xl drop-shadow-lg"></i>
        </button>
    </div>
</div>