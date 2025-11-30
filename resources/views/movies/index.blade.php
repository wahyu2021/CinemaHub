@extends('layouts.app')

@section('title', 'CinemaHub - Explore Universe')

@section('content')

    @if (($category === 'popular' || $category === null) && $current_page == 1 && count($movies) > 0)
        <x-hero-slider :movies="$movies" />
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 relative z-10">

        @if(!empty($trendingMovies))
            <div class="mb-20 space-y-16">
                <x-movie-section 
                    :title="__('messages.trending')" 
                    :movies="$trendingMovies" 
                    :route="route('movies.trending')" 
                    id="trending-scroll"
                    iconColor="primary" 
                />

                <x-movie-section 
                    :title="__('messages.coming_soon')" 
                    :movies="$upcomingMovies" 
                    :route="route('movies.index', ['category' => 'upcoming'])" 
                    id="upcoming-scroll"
                    iconColor="blue-500" 
                />

                <x-movie-section 
                    :title="__('messages.top_rated_gems')" 
                    :movies="$topRatedMovies" 
                    :route="route('movies.index', ['category' => 'top_rated'])" 
                    id="top-rated-scroll"
                    iconColor="yellow-500" 
                />
            </div>
        @endif

        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
            <div>
                <h1 class="text-4xl md:text-5xl font-display font-bold mb-2 text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-500">
                    @switch($category)
                        @case('now_playing')
                            {{ __('messages.now_playing') }}
                        @break
                        @case('top_rated')
                            {{ __('messages.top_rated') }}
                        @break
                        @case('upcoming')
                            {{ __('messages.upcoming') }}
                        @break
                        @default
                            {{ __('messages.discover_movies') }}
                    @endswitch
                </h1>
                <p class="text-gray-400 font-light flex items-center gap-2">
                    <span class="w-8 h-[1px] bg-primary"></span>
                    {{ __('messages.explore_universe') }}
                </p>
            </div>
        </div>

        <x-filters.movie-filter 
            :category="$category" 
            :selectedGenre="$selected_genre" 
            :genres="$genres" 
            :selectedSort="$selected_sort" 
            :selectedYear="$selected_year" 
        />

        @if ($selected_genre || $selected_year || $selected_sort !== 'popularity.desc')
            <div class="flex flex-wrap gap-2 mb-8 animate-fade-in">
                @if ($selected_genre)
                    @foreach (explode(',', $selected_genre) as $gId)
                        @php $gName = collect($genres)->firstWhere('id', $gId)['name'] ?? 'Genre'; @endphp
                        <span class="px-3 py-1 rounded-lg bg-primary/20 border border-primary/30 text-white text-xs flex items-center gap-2">
                            {{ $gName }}
                        </span>
                    @endforeach
                @endif

                @if ($selected_year)
                    <span class="px-3 py-1 rounded-lg bg-purple-500/20 border border-purple-500/30 text-white text-xs flex items-center gap-2">
                        {{ __('messages.year') }}: {{ $selected_year }}
                    </span>
                @endif

                <a href="{{ route('movies.index') }}" class="px-3 py-1 rounded-lg bg-white/5 border border-white/10 text-gray-400 hover:text-white hover:bg-red-500/20 hover:border-red-500/30 text-xs transition-all flex items-center gap-2">
                    <i class="fas fa-times"></i> {{ __('messages.clear_filters') }}
                </a>
            </div>
        @endif

        @if (count($movies) > 0)
            <div class="movies-container grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 mb-12">
                @foreach ($movies as $index => $movie)
                    <div class="reveal" style="transition-delay: {{ $index * 50 }}ms">
                        <x-movie-card :movie="$movie" />
                    </div>
                @endforeach
            </div>

            @if ($total_pages > 1)
                <x-pagination 
                    :current-page="$current_page" 
                    :total-pages="$total_pages" 
                    :route-name="'movies.index'"
                    :route-params="request()->query()"
                />
            @endif
        @else
            <div class="flex flex-col items-center justify-center py-32 text-center">
                <div class="w-24 h-24 bg-white/5 rounded-full flex items-center justify-center mb-6 animate-pulse">
                    <i class="fas fa-film text-4xl text-gray-600"></i>
                </div>
                <h3 class="text-2xl font-bold text-white mb-2">{{ __('messages.no_movies_found') }}</h3>
                <p class="text-gray-400 mb-8 max-w-md">{{ __('messages.no_movies_desc') }}</p>
                <a href="{{ route('movies.index') }}" class="px-6 py-3 bg-white text-black font-bold rounded-full hover:bg-gray-200 transition-colors">
                    {{ __('messages.clear_filters') }}
                </a>
            </div>
        @endif
    </div>

    <script>
        function scrollHorizontal(elementId, amount) {
            const container = document.getElementById(elementId);
            if (container) {
                container.scrollBy({
                    left: amount,
                    behavior: 'smooth'
                });
            }
        }
    </script>

    <style>
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(229, 9, 20, 0.5);
            border-radius: 4px;
        }
    </style>
@endsection