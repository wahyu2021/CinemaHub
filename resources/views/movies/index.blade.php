@extends('layouts.app')

@section('title', 'CinemaHub - Explore Universe')

@section('content')

    @if (($category === 'popular' || $category === null) && $current_page == 1 && count($movies) > 0)
        <div class="relative w-full h-[60vh] md:h-[85vh] overflow-hidden mb-12 group" id="hero-slider">

            <div class="absolute inset-0 w-full h-full">
                @foreach (array_slice($movies, 0, 5) as $index => $movie)
                    <div class="hero-slide absolute inset-0 w-full h-full transition-opacity duration-1000 ease-in-out {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}"
                        data-index="{{ $index }}">

                        <div class="absolute inset-0 overflow-hidden">
                            <div class="hero-bg absolute inset-0 bg-cover bg-center transition-transform duration-[10000ms] ease-linear transform scale-100 origin-center"
                                style="background-image: url('https://image.tmdb.org/t/p/original{{ $movie['backdrop_path'] }}');">
                            </div>
                        </div>

                        <div class="absolute inset-0 bg-gradient-to-t from-[#050505] via-[#050505]/40 to-transparent"></div>
                        <div class="absolute inset-0 bg-gradient-to-r from-[#050505] via-[#050505]/60 to-transparent"></div>
                        <div
                            class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10 mix-blend-overlay pointer-events-none">
                        </div>

                        <div class="absolute inset-0 flex items-center">
                            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                                <div
                                    class="max-w-3xl relative z-20 pl-4 border-l-4 border-primary/0 slide-content transition-all duration-500">

                                    <div class="overflow-hidden mb-4">
                                        <span
                                            class="hero-element inline-block px-3 py-1 text-xs font-bold tracking-[0.2em] text-primary border border-primary/30 rounded-sm bg-primary/5 uppercase translate-y-10 opacity-0 transition-all duration-700 delay-100">
                                            {{ __('messages.trending') }}
                                        </span>
                                    </div>

                                    <div class="overflow-hidden mb-4">
                                        <h2
                                            class="hero-element text-5xl md:text-7xl lg:text-8xl font-display font-bold leading-none text-white drop-shadow-2xl translate-y-20 opacity-0 transition-all duration-700 delay-200">
                                            {{ $movie['title'] }}
                                        </h2>
                                    </div>

                                    <div
                                        class="hero-element flex items-center gap-4 text-gray-300 text-sm font-mono mb-6 translate-y-10 opacity-0 transition-all duration-700 delay-300">
                                        <span class="flex items-center gap-1"><i class="fas fa-star text-yellow-500"></i>
                                            {{ number_format($movie['vote_average'], 1) }}</span>
                                        <span>|</span>
                                        <span>{{ \Carbon\Carbon::parse($movie['release_date'])->format('Y') }}</span>
                                        <span>|</span>
                                        <span class="uppercase">{{ $movie['original_language'] }}</span>
                                    </div>

                                    <div class="overflow-hidden mb-8">
                                        <p
                                            class="hero-element text-gray-300 text-lg md:text-xl font-light max-w-2xl leading-relaxed line-clamp-3 translate-y-10 opacity-0 transition-all duration-700 delay-400 text-shadow-sm">
                                            {{ $movie['overview'] }}
                                        </p>
                                    </div>

                                    <div
                                        class="hero-element flex flex-wrap gap-4 translate-y-10 opacity-0 transition-all duration-700 delay-500">
                                        <a href="{{ route('movies.show', $movie['id']) }}"
                                            class="relative px-8 py-4 bg-white text-black font-bold rounded-sm overflow-hidden group/btn hover:scale-105 transition-transform duration-300">
                                            <div
                                                class="absolute inset-0 bg-primary translate-x-[-100%] group-hover/btn:translate-x-0 transition-transform duration-300 ease-out z-0">
                                            </div>
                                            <span
                                                class="relative z-10 group-hover/btn:text-white transition-colors duration-300 flex items-center gap-3">
                                                <i class="fas fa-play"></i> {{ __('messages.watch_trailer') }}
                                            </span>
                                        </a>
                                        <a href="{{ route('movies.show', $movie['id']) }}"
                                            class="px-8 py-4 bg-white/5 backdrop-blur-md border border-white/20 text-white font-bold rounded-sm hover:bg-white/20 transition-all hover:scale-105 flex items-center gap-3">
                                            <i class="fas fa-info-circle"></i> {{ __('messages.details') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="absolute bottom-0 w-full z-30 pointer-events-none">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8 flex items-end justify-between">

                    <div class="flex flex-col gap-4 pointer-events-auto">
                        <div class="flex space-x-3">
                            @foreach (array_slice($movies, 0, 5) as $index => $movie)
                                <button
                                    class="indicator-btn w-12 h-1 rounded-full transition-all duration-300 bg-white/20 hover:bg-white/40 overflow-hidden relative"
                                    onclick="manualSlide({{ $index }})">
                                    <div
                                        class="progress-fill absolute top-0 left-0 h-full bg-primary w-0 {{ $index === 0 ? 'w-full' : '' }}">
                                    </div>
                                </button>
                            @endforeach
                        </div>
                        <div class="text-xs font-mono text-gray-500">
                            <span id="current-slide-num">01</span> / 05
                        </div>
                    </div>

                    <div class="flex gap-2 pointer-events-auto">
                        <button onclick="prevSlide()"
                            class="w-12 h-12 rounded-full border border-white/10 bg-black/20 backdrop-blur-md flex items-center justify-center text-white hover:bg-primary hover:border-primary transition-all group">
                            <i class="fas fa-arrow-left transition-transform"></i>
                        </button>
                        <button onclick="nextSlide()"
                            class="w-12 h-12 rounded-full border border-white/10 bg-black/20 backdrop-blur-md flex items-center justify-center text-white hover:bg-primary hover:border-primary transition-all group">
                            <i class="fas fa-arrow-right transition-transform"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 relative z-10">

            @if(!empty($trendingMovies))
                {{-- Rich Home Page Sections --}}
                <div class="mb-20 space-y-16">
                    
                    {{-- Trending Section --}}
                    <div class="relative group/section">
                        <div class="flex justify-between items-end mb-6 px-2">
                            <h2 class="text-2xl md:text-3xl font-display font-bold text-white flex items-center gap-3">
                                <span class="w-1 h-8 bg-primary rounded-full"></span>
                                {{ __('messages.trending') }}
                            </h2>
                            <a href="{{ route('movies.trending') }}" class="text-sm font-medium text-gray-400 hover:text-primary transition-colors flex items-center gap-2 group/link">
                                {{ __('messages.view_all') }} <i class="fas fa-arrow-right transform group-hover/link:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                        
                        <div class="relative">
                            <button class="absolute left-0 top-1/2 -translate-y-1/2 z-20 w-12 h-full bg-gradient-to-r from-black to-transparent opacity-0 group-hover/section:opacity-100 transition-opacity flex items-center justify-start pl-2 text-white/70 hover:text-primary" onclick="scrollHorizontal('trending-scroll', -300)">
                                <i class="fas fa-chevron-left text-2xl drop-shadow-lg"></i>
                            </button>
                            
                            <div class="flex overflow-x-auto gap-5 pb-8 pt-2 snap-x snap-mandatory hide-scrollbar scroll-smooth" id="trending-scroll">
                                @foreach(array_slice($trendingMovies, 0, 10) as $movie)
                                    <div class="min-w-[160px] md:min-w-[220px] snap-start transform transition-transform duration-300 hover:scale-105 hover:z-10">
                                        <x-movie-card :movie="$movie" />
                                    </div>
                                @endforeach
                            </div>

                            <button class="absolute right-0 top-1/2 -translate-y-1/2 z-20 w-12 h-full bg-gradient-to-l from-black to-transparent opacity-0 group-hover/section:opacity-100 transition-opacity flex items-center justify-end pr-2 text-white/70 hover:text-primary" onclick="scrollHorizontal('trending-scroll', 300)">
                                <i class="fas fa-chevron-right text-2xl drop-shadow-lg"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Upcoming Section --}}
                    <div class="relative group/section">
                        <div class="flex justify-between items-end mb-6 px-2">
                            <h2 class="text-2xl md:text-3xl font-display font-bold text-white flex items-center gap-3">
                                <span class="w-1 h-8 bg-blue-500 rounded-full"></span>
                                {{ __('messages.coming_soon') }}
                            </h2>
                            <a href="{{ route('movies.index', ['category' => 'upcoming']) }}" class="text-sm font-medium text-gray-400 hover:text-blue-500 transition-colors flex items-center gap-2 group/link">
                                {{ __('messages.view_all') }} <i class="fas fa-arrow-right transform group-hover/link:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                        
                        <div class="relative">
                            <button class="absolute left-0 top-1/2 -translate-y-1/2 z-20 w-12 h-full bg-gradient-to-r from-black to-transparent opacity-0 group-hover/section:opacity-100 transition-opacity flex items-center justify-start pl-2 text-white/70 hover:text-blue-500" onclick="scrollHorizontal('upcoming-scroll', -300)">
                                <i class="fas fa-chevron-left text-2xl drop-shadow-lg"></i>
                            </button>

                            <div class="flex overflow-x-auto gap-5 pb-8 pt-2 snap-x snap-mandatory hide-scrollbar scroll-smooth" id="upcoming-scroll">
                                @foreach(array_slice($upcomingMovies, 0, 10) as $movie)
                                    <div class="min-w-[160px] md:min-w-[220px] snap-start transform transition-transform duration-300 hover:scale-105 hover:z-10">
                                        <x-movie-card :movie="$movie" />
                                    </div>
                                @endforeach
                            </div>

                            <button class="absolute right-0 top-1/2 -translate-y-1/2 z-20 w-12 h-full bg-gradient-to-l from-black to-transparent opacity-0 group-hover/section:opacity-100 transition-opacity flex items-center justify-end pr-2 text-white/70 hover:text-blue-500" onclick="scrollHorizontal('upcoming-scroll', 300)">
                                <i class="fas fa-chevron-right text-2xl drop-shadow-lg"></i>
                            </button>
                        </div>
                    </div>

                     {{-- Top Rated Section --}}
                     <div class="relative group/section">
                        <div class="flex justify-between items-end mb-6 px-2">
                            <h2 class="text-2xl md:text-3xl font-display font-bold text-white flex items-center gap-3">
                                <span class="w-1 h-8 bg-yellow-500 rounded-full"></span>
                                {{ __('messages.top_rated_gems') }}
                            </h2>
                            <a href="{{ route('movies.index', ['category' => 'top_rated']) }}" class="text-sm font-medium text-gray-400 hover:text-yellow-500 transition-colors flex items-center gap-2 group/link">
                                {{ __('messages.view_all') }} <i class="fas fa-arrow-right transform group-hover/link:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                        
                        <div class="relative">
                            <button class="absolute left-0 top-1/2 -translate-y-1/2 z-20 w-12 h-full bg-gradient-to-r from-black to-transparent opacity-0 group-hover/section:opacity-100 transition-opacity flex items-center justify-start pl-2 text-white/70 hover:text-yellow-500" onclick="scrollHorizontal('top-rated-scroll', -300)">
                                <i class="fas fa-chevron-left text-2xl drop-shadow-lg"></i>
                            </button>

                            <div class="flex overflow-x-auto gap-5 pb-8 pt-2 snap-x snap-mandatory hide-scrollbar scroll-smooth" id="top-rated-scroll">
                                @foreach(array_slice($topRatedMovies, 0, 10) as $movie)
                                    <div class="min-w-[160px] md:min-w-[220px] snap-start transform transition-transform duration-300 hover:scale-105 hover:z-10">
                                        <x-movie-card :movie="$movie" />
                                    </div>
                                @endforeach
                            </div>

                            <button class="absolute right-0 top-1/2 -translate-y-1/2 z-20 w-12 h-full bg-gradient-to-l from-black to-transparent opacity-0 group-hover/section:opacity-100 transition-opacity flex items-center justify-end pr-2 text-white/70 hover:text-yellow-500" onclick="scrollHorizontal('top-rated-scroll', 300)">
                                <i class="fas fa-chevron-right text-2xl drop-shadow-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
                <div>
                    <h1
                        class="text-4xl md:text-5xl font-display font-bold mb-2 text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-500">
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

            <div class="relative z-50 mb-12">
                <form method="GET" action="{{ route('movies.index') }}" class="relative" id="filter-form">
                    <input type="hidden" name="category" value="{{ $category }}">
                    <input type="hidden" name="genre" id="genre-input" value="{{ $selected_genre }}">

                    <div
                        class="glass p-2 rounded-2xl border border-white/10 flex flex-col md:flex-row gap-2 shadow-2xl bg-black/40 backdrop-blur-xl">

                        <div class="relative flex-1 group">
                            <button type="button" onclick="toggleGenrePanel()"
                                class="w-full h-14 bg-white/5 hover:bg-white/10 border border-white/5 hover:border-primary/50 rounded-xl px-5 flex items-center justify-between transition-all duration-300 group-focus-within:border-primary/50 group-focus-within:shadow-[0_0_15px_rgba(229,9,20,0.2)]">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-primary/20 flex items-center justify-center text-primary">
                                        <i class="fas fa-layer-group"></i>
                                    </div>
                                    <div class="flex flex-col items-start">
                                        <span
                                            class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">Genre</span>
                                        <span class="text-sm font-medium text-white truncate max-w-[150px]"
                                            id="genre-display-text">All Genres</span>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-down text-gray-500 transition-transform duration-300"
                                    id="genre-arrow"></i>
                            </button>

                            <div id="genre-panel"
                                class="hidden absolute top-full left-0 w-full md:w-[600px] mt-4 p-6 bg-[#050505] rounded-2xl border border-white/20 shadow-[0_0_60px_rgba(0,0,0,0.9)] z-[100] transform origin-top transition-all duration-300 ring-1 ring-white/5">

                                <div
                                    class="absolute -top-10 -right-10 w-32 h-32 bg-primary/10 rounded-full blur-[50px] pointer-events-none">
                                </div>
                                <div
                                    class="absolute -bottom-10 -left-10 w-32 h-32 bg-blue-600/10 rounded-full blur-[50px] pointer-events-none">
                                </div>

                                <div class="flex justify-between items-center mb-4 relative z-10">
                                    <span
                                        class="text-sm text-gray-400 font-display uppercase tracking-widest font-bold">Select
                                        Genres</span>
                                    <span class="text-xs text-gray-600 italic">Multiple selection allowed</span>
                                </div>

                                <div
                                    class="grid grid-cols-2 md:grid-cols-3 gap-2 max-h-64 overflow-y-auto pr-2 custom-scrollbar relative z-10">
                                    @php
                                        $selectedGenres = !empty($selected_genre) ? explode(',', $selected_genre) : [];
                                    @endphp

                                    <label class="cursor-pointer group/label col-span-2 md:col-span-3 mb-2">
                                        <input type="checkbox" id="all-genres-checkbox" class="peer hidden" value=""
                                            {{ empty($selected_genre) ? 'checked' : '' }}
                                            onchange="handleAllGenresClick(this)">

                                        <div
                                            class="w-full px-4 py-3 rounded-xl border transition-all duration-300 flex items-center justify-center gap-3
                    bg-white/5 border-white/10 text-gray-400
                    hover:bg-white/10 hover:border-white/30 hover:shadow-[0_0_15px_rgba(255,255,255,0.1)]
                    peer-checked:bg-primary peer-checked:border-primary peer-checked:text-white peer-checked:shadow-[0_0_20px_rgba(229,9,20,0.5)]">

                                            <i class="fas fa-globe text-sm"></i>
                                            <span class="font-bold text-sm tracking-wide">ALL GENRES</span>
                                        </div>
                                    </label>

                                    @foreach ($genres as $genre)
                                        <label class="cursor-pointer group/label">
                                            <input type="checkbox" class="peer hidden genre-checkbox"
                                                value="{{ $genre['id'] }}" data-name="{{ $genre['name'] }}"
                                                {{ in_array($genre['id'], $selectedGenres) ? 'checked' : '' }}
                                                onchange="handleGenreClick(this)">

                                            <div
                                                class="px-4 py-2.5 rounded-lg border transition-all duration-200 text-sm flex items-center justify-between
                        bg-[#0f0f0f] border-white/5 text-gray-400
                        hover:bg-[#1a1a1a] hover:text-white hover:border-white/20
                        peer-checked:bg-primary/10 peer-checked:border-primary/50 peer-checked:text-primary">

                                                <span>{{ $genre['name'] }}</span>
                                                <i
                                                    class="fas fa-check text-[10px] opacity-0 peer-checked:opacity-100 transition-opacity transform scale-50 peer-checked:scale-100"></i>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>

                                <div class="mt-4 pt-4 border-t border-white/10 flex justify-end gap-3 relative z-10">
                                    <button type="button" onclick="clearGenres()"
                                        class="px-4 py-2 rounded-lg text-xs font-bold text-gray-500 hover:text-white hover:bg-white/5 transition-colors">
                                        RESET
                                    </button>
                                    <button type="button" onclick="toggleGenrePanel()"
                                        class="px-6 py-2 rounded-lg bg-white text-black text-xs font-bold hover:bg-gray-200 transition-colors shadow-[0_0_10px_rgba(255,255,255,0.3)]">
                                        DONE
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Sort Filter --}}
                        <div class="relative w-full md:w-64 group">
                            <input type="hidden" name="sort_by" id="sort-input" value="{{ $selected_sort }}">

                            <button type="button" onclick="toggleSortPanel()"
                                class="w-full h-14 bg-white/5 hover:bg-white/10 border border-white/5 hover:border-blue-500/50 rounded-xl px-5 flex items-center justify-between transition-all duration-300 group-focus-within:border-blue-500/50">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center text-blue-400">
                                        <i class="fas fa-sort-amount-down"></i>
                                    </div>
                                    <div class="flex flex-col items-start">
                                        <span class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">{{ __('messages.sort_by') }}</span>
                                        <span class="text-sm font-medium text-white truncate max-w-[150px]" id="sort-display-text">
                                            @switch($selected_sort)
                                                @case('popularity.asc') {{ __('messages.sort_popularity_asc') }} @break
                                                @case('vote_average.desc') {{ __('messages.sort_rating_desc') }} @break
                                                @case('release_date.desc') {{ __('messages.sort_date_desc') }} @break
                                                @default {{ __('messages.sort_popularity_desc') }}
                                            @endswitch
                                        </span>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-down text-gray-500 transition-transform duration-300" id="sort-arrow"></i>
                            </button>

                            <div id="sort-panel" class="hidden absolute top-full left-0 w-full mt-4 p-2 bg-[#050505] rounded-2xl border border-white/20 shadow-[0_0_60px_rgba(0,0,0,0.9)] z-[100] ring-1 ring-white/5 origin-top">
                                <div class="flex flex-col gap-1">
                                    @foreach([
                                        'popularity.desc' => __('messages.sort_popularity_desc'),
                                        'popularity.asc' => __('messages.sort_popularity_asc'),
                                        'vote_average.desc' => __('messages.sort_rating_desc'),
                                        'release_date.desc' => __('messages.sort_date_desc')
                                    ] as $val => $label)
                                    <button type="button" onclick="handleSortClick('{{ $val }}')"
                                        class="w-full px-4 py-3 rounded-xl text-left text-sm transition-all duration-200 flex items-center justify-between group/item
                                        {{ $selected_sort == $val ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20' : 'text-gray-400 hover:bg-white/5 hover:text-white border border-transparent' }}">
                                        <span>{{ $label }}</span>
                                        @if($selected_sort == $val)
                                            <i class="fas fa-check text-xs"></i>
                                        @endif
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Year Filter --}}
                        <div class="relative w-full md:w-48 group">
                            <input type="hidden" name="year" id="year-input" value="{{ $selected_year }}">

                            <button type="button" onclick="toggleYearPanel()"
                                class="w-full h-14 bg-white/5 hover:bg-white/10 border border-white/5 hover:border-purple-500/50 rounded-xl px-5 flex items-center justify-between transition-all duration-300 group-focus-within:border-purple-500/50">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-purple-500/20 flex items-center justify-center text-purple-400">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div class="flex flex-col items-start">
                                        <span class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">{{ __('messages.year') }}</span>
                                        <span class="text-sm font-medium text-white truncate" id="year-display-text">
                                            {{ $selected_year ?: __('messages.all_years') }}
                                        </span>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-down text-gray-500 transition-transform duration-300" id="year-arrow"></i>
                            </button>

                            <div id="year-panel" class="hidden absolute top-full right-0 md:left-auto md:right-0 w-64 mt-4 p-4 bg-[#050505] rounded-2xl border border-white/20 shadow-[0_0_60px_rgba(0,0,0,0.9)] z-[100] ring-1 ring-white/5 origin-top">
                                <div class="grid grid-cols-3 gap-2 max-h-64 overflow-y-auto custom-scrollbar pr-2">
                                    <button type="button" onclick="handleYearClick('')"
                                        class="col-span-3 px-3 py-2 rounded-lg text-xs font-bold transition-all duration-200 border
                                        {{ !$selected_year ? 'bg-purple-500 text-white border-purple-500 shadow-lg shadow-purple-500/20' : 'bg-white/5 text-gray-400 border-white/5 hover:bg-white/10 hover:text-white' }}">
                                        {{ __('messages.all_years') }}
                                    </button>
                                    @for ($y = date('Y') + 1; $y >= 1990; $y--)
                                        <button type="button" onclick="handleYearClick('{{ $y }}')"
                                            class="px-3 py-2 rounded-lg text-xs font-medium transition-all duration-200 border
                                            {{ $selected_year == $y ? 'bg-purple-500/20 text-purple-400 border-purple-500/50' : 'bg-[#0f0f0f] text-gray-400 border-white/5 hover:bg-[#1a1a1a] hover:text-white hover:border-white/20' }}">
                                            {{ $y }}
                                        </button>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <button type="submit"
                            class="h-14 px-8 bg-primary hover:bg-primary-glow text-white font-bold rounded-xl transition-all shadow-lg shadow-primary/20 hover:shadow-primary/40 flex items-center justify-center gap-2 group">
                            <span>Filter</span>
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </div>
                </form>
            </div>

            @if ($selected_genre || $selected_year || $selected_sort !== 'popularity.desc')
                <div class="flex flex-wrap gap-2 mb-8 animate-fade-in">
                    @if ($selected_genre)
                        @foreach (explode(',', $selected_genre) as $gId)
                            @php $gName = collect($genres)->firstWhere('id', $gId)['name'] ?? 'Genre'; @endphp
                            <span
                                class="px-3 py-1 rounded-lg bg-primary/20 border border-primary/30 text-white text-xs flex items-center gap-2">
                                {{ $gName }}
                            </span>
                        @endforeach
                    @endif

                    @if ($selected_year)
                        <span
                            class="px-3 py-1 rounded-lg bg-purple-500/20 border border-purple-500/30 text-white text-xs flex items-center gap-2">
                            Year: {{ $selected_year }}
                        </span>
                    @endif

                    <a href="{{ route('movies.index') }}"
                        class="px-3 py-1 rounded-lg bg-white/5 border border-white/10 text-gray-400 hover:text-white hover:bg-red-500/20 hover:border-red-500/30 text-xs transition-all flex items-center gap-2">
                        <i class="fas fa-times"></i> Reset All
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
                    <div class="flex justify-center items-center gap-2 mt-12">
                        @if ($current_page > 1)
                            <a href="{{ route('movies.index', array_merge(request()->all(), ['page' => $current_page - 1])) }}"
                                class="w-10 h-10 flex items-center justify-center rounded-full glass hover:bg-primary hover:border-primary transition-all text-white group">
                                <i class="fas fa-chevron-left group-hover:-translate-x-0.5 transition-transform"></i>
                            </a>
                        @endif

                        <div class="flex gap-2 bg-black/30 p-1 rounded-full backdrop-blur-md border border-white/5">
                            @php
                                $start = max(1, $current_page - 2);
                                $end = min($total_pages, $current_page + 2);
                            @endphp

                            @if ($start > 1)
                                <a href="{{ route('movies.index', array_merge(request()->all(), ['page' => 1])) }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-full text-sm text-gray-400 hover:text-white hover:bg-white/10 transition-all">1</a>
                                @if ($start > 2)
                                    <span class="w-8 h-8 flex items-center justify-center text-gray-600">...</span>
                                @endif
                            @endif

                            @for ($i = $start; $i <= $end; $i++)
                                <a href="{{ route('movies.index', array_merge(request()->all(), ['page' => $i])) }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-full text-sm font-bold transition-all {{ $i == $current_page ? 'bg-primary text-white shadow-lg shadow-primary/40 scale-110' : 'text-gray-400 hover:text-white hover:bg-white/10' }}">
                                    {{ $i }}
                                </a>
                            @endfor

                            @if ($end < $total_pages)
                                @if ($end < $total_pages - 1)
                                    <span class="w-8 h-8 flex items-center justify-center text-gray-600">...</span>
                                @endif
                                <a href="{{ route('movies.index', array_merge(request()->all(), ['page' => $total_pages])) }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-full text-sm text-gray-400 hover:text-white hover:bg-white/10 transition-all">{{ $total_pages }}</a>
                            @endif
                        </div>

                        @if ($current_page < $total_pages)
                            <a href="{{ route('movies.index', array_merge(request()->all(), ['page' => $current_page + 1])) }}"
                                class="w-10 h-10 flex items-center justify-center rounded-full glass hover:bg-primary hover:border-primary transition-all text-white group">
                                <i class="fas fa-chevron-right group-hover:translate-x-0.5 transition-transform"></i>
                            </a>
                        @endif
                    </div>
                @endif
            @else
                <div class="flex flex-col items-center justify-center py-32 text-center">
                    <div class="w-24 h-24 bg-white/5 rounded-full flex items-center justify-center mb-6 animate-pulse">
                        <i class="fas fa-film text-4xl text-gray-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">{{ __('messages.no_movies_found') }}</h3>
                    <p class="text-gray-400 mb-8 max-w-md">{{ __('messages.no_movies_desc') }}</p>
                    <a href="{{ route('movies.index') }}"
                        class="px-6 py-3 bg-white text-black font-bold rounded-full hover:bg-gray-200 transition-colors">
                        {{ __('messages.clear_filters') }}
                    </a>
                </div>
            @endif
        </div>

        <script>
            // Horizontal Scroll Helper
            function scrollHorizontal(elementId, amount) {
                const container = document.getElementById(elementId);
                if (container) {
                    container.scrollBy({
                        left: amount,
                        behavior: 'smooth'
                    });
                }
            }

            // Slider Logic (Simple Vanilla JS)
            let currentSlide = 0;
            const slides = document.querySelectorAll('.hero-slide');

            function nextSlide() {
                if (slides.length === 0) return;

                slides[currentSlide].classList.remove('opacity-100', 'z-10');
                slides[currentSlide].classList.add('opacity-0', 'z-0');

                currentSlide = (currentSlide + 1) % slides.length;

                slides[currentSlide].classList.remove('opacity-0', 'z-0');
                slides[currentSlide].classList.add('opacity-100', 'z-10');
            }

            if (slides.length > 0) {
                setInterval(nextSlide, 6000);
            }

            // Genre Dropdown Logic
            const genreInput = document.getElementById('genre-input');
            const genreDisplayText = document.getElementById('genre-display-text');
            const allGenresCheckbox = document.getElementById('all-genres-checkbox');
            const genreCheckboxes = document.querySelectorAll('.genre-checkbox');

            // 1. Handle klik "All Genres"
            function handleAllGenresClick(el) {
                if (el.checked) {
                    // Uncheck semua genre lain
                    genreCheckboxes.forEach(cb => cb.checked = false);
                } else {
                    // Jika user mencoba uncheck "All" tapi tidak ada genre lain yg dipilih,
                    // paksa tetap checked (state tidak boleh kosong)
                    const anyOtherChecked = Array.from(genreCheckboxes).some(cb => cb.checked);
                    if (!anyOtherChecked) el.checked = true;
                }
                updateGenreState();
            }

            // 2. Handle klik genre spesifik
            function handleGenreClick(el) {
                if (el.checked) {
                    // Jika genre dipilih, matikan "All Genres"
                    allGenresCheckbox.checked = false;
                } else {
                    // Jika semua genre di-uncheck, nyalakan "All Genres" otomatis
                    const anyOtherChecked = Array.from(genreCheckboxes).some(cb => cb.checked);
                    if (!anyOtherChecked) allGenresCheckbox.checked = true;
                }
                updateGenreState();
            }

            // 3. Update UI & Input Value
            function updateGenreState() {
                const checkedBoxes = Array.from(genreCheckboxes).filter(cb => cb.checked);
                const selectedValues = checkedBoxes.map(cb => cb.value);
                const selectedNames = checkedBoxes.map(cb => cb.dataset.name);

                // Update hidden input untuk form submit
                genreInput.value = selectedValues.join(',');

                // Update Label di tombol utama
                if (allGenresCheckbox.checked || selectedValues.length === 0) {
                    genreDisplayText.textContent = "{{ __('messages.all_genres') }}";
                    genreDisplayText.classList.remove('text-primary');
                    genreDisplayText.classList.add('text-white');
                } else if (selectedValues.length === 1) {
                    genreDisplayText.textContent = selectedNames[0];
                    genreDisplayText.classList.add('text-primary');
                } else {
                    genreDisplayText.textContent = `${selectedValues.length} {{ __('messages.genre') }}`;
                    genreDisplayText.classList.add('text-primary');
                }
            }

            // Panel Toggle Logic
            const genrePanel = document.getElementById('genre-panel');
            const genreArrow = document.getElementById('genre-arrow');
            const sortPanel = document.getElementById('sort-panel');
            const sortArrow = document.getElementById('sort-arrow');
            const yearPanel = document.getElementById('year-panel');
            const yearArrow = document.getElementById('year-arrow');
            const filterForm = document.getElementById('filter-form');

            function toggleGenrePanel() {
                genrePanel.classList.toggle('hidden');
                genreArrow.classList.toggle('rotate-180');
                // Close others
                if (sortPanel && !sortPanel.classList.contains('hidden')) {
                    sortPanel.classList.add('hidden');
                    sortArrow.classList.remove('rotate-180');
                }
                if (yearPanel && !yearPanel.classList.contains('hidden')) {
                    yearPanel.classList.add('hidden');
                    yearArrow.classList.remove('rotate-180');
                }
            }

            function toggleSortPanel() {
                sortPanel.classList.toggle('hidden');
                sortArrow.classList.toggle('rotate-180');
                // Close others
                if (!genrePanel.classList.contains('hidden')) {
                    genrePanel.classList.add('hidden');
                    genreArrow.classList.remove('rotate-180');
                }
                if (yearPanel && !yearPanel.classList.contains('hidden')) {
                    yearPanel.classList.add('hidden');
                    yearArrow.classList.remove('rotate-180');
                }
            }

            function toggleYearPanel() {
                yearPanel.classList.toggle('hidden');
                yearArrow.classList.toggle('rotate-180');
                // Close others
                if (!genrePanel.classList.contains('hidden')) {
                    genrePanel.classList.add('hidden');
                    genreArrow.classList.remove('rotate-180');
                }
                if (sortPanel && !sortPanel.classList.contains('hidden')) {
                    sortPanel.classList.add('hidden');
                    sortArrow.classList.remove('rotate-180');
                }
            }

            function handleSortClick(value) {
                document.getElementById('sort-input').value = value;
                filterForm.submit();
            }

            function handleYearClick(value) {
                document.getElementById('year-input').value = value;
                filterForm.submit();
            }

            // Close when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.group')) {
                    if (!genrePanel.classList.contains('hidden')) {
                        genrePanel.classList.add('hidden');
                        genreArrow.classList.remove('rotate-180');
                    }
                    if (sortPanel && !sortPanel.classList.contains('hidden')) {
                        sortPanel.classList.add('hidden');
                        sortArrow.classList.remove('rotate-180');
                    }
                    if (yearPanel && !yearPanel.classList.contains('hidden')) {
                        yearPanel.classList.add('hidden');
                        yearArrow.classList.remove('rotate-180');
                    }
                }
            });

            // Init state saat load
            document.addEventListener('DOMContentLoaded', () => {
                // Validasi state visual saat refresh
                if (!allGenresCheckbox.checked && Array.from(genreCheckboxes).every(cb => !cb.checked)) {
                    allGenresCheckbox.checked = true;
                }
                updateGenreState();
            });

            document.addEventListener('DOMContentLoaded', () => {
                const slides = document.querySelectorAll('.hero-slide');
                const indicators = document.querySelectorAll('.indicator-btn .progress-fill');
                const numDisplay = document.getElementById('current-slide-num');
                let currentSlide = 0;
                let slideInterval;
                const duration = 6000; // 6 detik per slide

                // Initialize first slide
                activateSlide(0);

                function activateSlide(index) {
                    // Reset all slides
                    slides.forEach((slide) => {
                        slide.classList.remove('opacity-100', 'z-10', 'active');
                        slide.classList.add('opacity-0', 'z-0');

                        // Reset BG Zoom
                        const bg = slide.querySelector('.hero-bg');
                        bg.style.transition = 'none';
                        bg.style.transform = 'scale(1)';
                    });

                    // Reset indicators
                    indicators.forEach((bar) => {
                        bar.style.width = '0%';
                        bar.style.transition = 'none';
                    });

                    // Activate new slide
                    const activeSlide = slides[index];
                    activeSlide.classList.remove('opacity-0', 'z-0');
                    activeSlide.classList.add('opacity-100', 'z-10');

                    // Trigger Animations (Force Reflow)
                    void activeSlide.offsetWidth;
                    activeSlide.classList.add('active');

                    // Ken Burns Effect (JS trigger for better control)
                    const activeBg = activeSlide.querySelector('.hero-bg');
                    activeBg.style.transition =
                    `transform ${duration + 500}ms ease-out`; // Sedikit lebih lama dari interval agar mulus
                    activeBg.style.transform = 'scale(1.15)';

                    // Animate Progress Bar
                    const activeBar = indicators[index];
                    activeBar.style.width = '0%';
                    void activeBar.offsetWidth; // Force reflow
                    activeBar.style.transition = `width ${duration}ms linear`;
                    activeBar.style.width = '100%';

                    // Update Number
                    numDisplay.textContent = `0${index + 1}`;

                    currentSlide = index;
                }

                function nextSlide() {
                    let next = (currentSlide + 1) % slides.length;
                    activateSlide(next);
                }

                window.prevSlide = function() {
                    let prev = (currentSlide - 1 + slides.length) % slides.length;
                    activateSlide(prev);
                    resetTimer();
                }

                window.nextSlide = function() {
                    nextSlide();
                    resetTimer();
                }

                window.manualSlide = function(index) {
                    if (index === currentSlide) return;
                    activateSlide(index);
                    resetTimer();
                }

                function startTimer() {
                    slideInterval = setInterval(nextSlide, duration);
                }

                function resetTimer() {
                    clearInterval(slideInterval);
                    startTimer();
                }

                // Start the loop
                startTimer();
            });
        </script>

        <style>
            /* Hide Scrollbar */
            .hide-scrollbar::-webkit-scrollbar {
                display: none;
            }
            .hide-scrollbar {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }

            /* Custom Scrollbar for Dropdown */
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

            .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: rgba(229, 9, 20, 0.8);
            }

            /* Utility for smooth Ken Burns Effect */
            .scale-125 {
                transform: scale(1.25);
            }

            /* Text Shadow Utility */
            .text-shadow-sm {
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
            }

            /* Active Slide Animation States */
            .hero-slide.active .hero-bg {
                transform: scale(1.15);
                /* Zoom in slowly */
            }

            .hero-slide.active .hero-element {
                opacity: 1;
                transform: translateY(0);
            }

            .hero-slide.active .slide-content {
                border-color: #e50914;
                /* Border Primary */
            }
        </style>
    @endsection
