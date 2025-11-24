@extends('layouts.app')

@section('title', 'CinemaHub - Temukan Film')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold mb-2">
            @switch($category)
                @case('now_playing') Sedang Tayang @break
                @case('top_rated') Rating Tertinggi @break
                @case('upcoming') Akan Datang @break
                @default Film Populer
            @endswitch
        </h1>
        <p class="text-gray-400">Temukan film-film menarik untuk ditonton</p>
    </div>

    <!-- Filters -->
    <div class="bg-dark rounded-lg p-6 mb-8 border border-gray-800">
        <form method="GET" action="{{ route('movies.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="hidden" name="category" value="{{ $category }}">
            
            <!-- Genre Filter -->
            <div class="relative">
                <label class="block text-sm font-medium mb-2 text-gray-300">Genre</label>
                <button 
                    type="button" 
                    onclick="toggleGenreDropdown()" 
                    class="w-full bg-darker border border-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:border-primary flex items-center justify-between text-left"
                >
                    <span id="genre-label">Pilih Genre</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div id="genre-dropdown" class="hidden absolute z-10 w-full mt-2 bg-darker border border-gray-700 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                    <div class="p-2">
                        <label class="flex items-center p-2 hover:bg-dark rounded cursor-pointer">
                            <input type="checkbox" value="" class="genre-checkbox mr-2" onchange="updateGenreSelection()"> 
                            <span>Semua Genre</span>
                        </label>
                        @foreach($genres as $genre)
                            <label class="flex items-center p-2 hover:bg-dark rounded cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    name="genres[]" 
                                    value="{{ $genre['id'] }}" 
                                    class="genre-checkbox mr-2"
                                    {{ $selected_genre == $genre['id'] ? 'checked' : '' }}
                                    onchange="updateGenreSelection()"
                                > 
                                <span>{{ $genre['name'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <input type="hidden" name="genre" id="genre-input" value="{{ $selected_genre }}">
            </div>
            
            <script>
                function toggleGenreDropdown() {
                    const dropdown = document.getElementById('genre-dropdown');
                    dropdown.classList.toggle('hidden');
                }
                
                function updateGenreSelection() {
                    const checkboxes = document.querySelectorAll('.genre-checkbox:checked');
                    const values = Array.from(checkboxes).map(cb => cb.value).filter(v => v !== '');
                    const label = document.getElementById('genre-label');
                    const input = document.getElementById('genre-input');
                    
                    if (values.length === 0) {
                        label.textContent = 'Semua Genre';
                        input.value = '';
                    } else if (values.length === 1) {
                        const checkbox = Array.from(checkboxes).find(cb => cb.value === values[0]);
                        label.textContent = checkbox.parentElement.querySelector('span').textContent;
                        input.value = values[0];
                    } else {
                        label.textContent = `${values.length} Genre Dipilih`;
                        input.value = values.join(',');
                    }
                }
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    const dropdown = document.getElementById('genre-dropdown');
                    const button = event.target.closest('[onclick="toggleGenreDropdown()"]');
                    if (!dropdown?.contains(event.target) && !button) {
                        dropdown?.classList.add('hidden');
                    }
                });
            </script>

            <!-- Sort By -->
            <div>
                <label class="block text-sm font-medium mb-2 text-gray-300">Urutkan</label>
                <select name="sort_by" class="w-full bg-darker border border-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:border-primary">
                    <option value="popularity.desc" {{ $selected_sort === 'popularity.desc' ? 'selected' : '' }}>Popularitas ↓</option>
                    <option value="popularity.asc" {{ $selected_sort === 'popularity.asc' ? 'selected' : '' }}>Popularitas ↑</option>
                    <option value="vote_average.desc" {{ $selected_sort === 'vote_average.desc' ? 'selected' : '' }}>Rating ↓</option>
                    <option value="vote_average.asc" {{ $selected_sort === 'vote_average.asc' ? 'selected' : '' }}>Rating ↑</option>
                    <option value="release_date.desc" {{ $selected_sort === 'release_date.desc' ? 'selected' : '' }}>Tanggal Rilis ↓</option>
                    <option value="release_date.asc" {{ $selected_sort === 'release_date.asc' ? 'selected' : '' }}>Tanggal Rilis ↑</option>
                </select>
            </div>

            <!-- Year Filter -->
            <div>
                <label class="block text-sm font-medium mb-2 text-gray-300">Tahun</label>
                <select name="year" class="w-full bg-darker border border-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:border-primary">
                    <option value="">Semua Tahun</option>
                    @for($y = date('Y') + 1; $y >= 1990; $y--)
                        <option value="{{ $y }}" {{ $selected_year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <!-- Submit Button -->
            <div class="flex items-end">
                <button type="submit" class="w-full bg-primary hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition">
                    <i class="fas fa-filter mr-2"></i> Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Movies Grid -->
    @if(count($movies) > 0)
        <div class="movies-container grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 mb-8">
            @foreach($movies as $movie)
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
            @endforeach
        </div>

        <!-- Pagination -->
        @if($total_pages > 1)
            <div class="flex justify-center items-center space-x-2">
                @if($current_page > 1)
                    <a href="{{ route('movies.index', array_merge(request()->all(), ['page' => $current_page - 1])) }}" 
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
                        <a href="{{ route('movies.index', array_merge(request()->all(), ['page' => 1])) }}" 
                           class="bg-dark hover:bg-gray-800 px-4 py-2 rounded-lg transition">1</a>
                        @if($start > 2)
                            <span class="px-4 py-2">...</span>
                        @endif
                    @endif

                    @for($i = $start; $i <= $end; $i++)
                        <a href="{{ route('movies.index', array_merge(request()->all(), ['page' => $i])) }}" 
                           class="px-4 py-2 rounded-lg transition {{ $i == $current_page ? 'bg-primary' : 'bg-dark hover:bg-gray-800' }}">
                            {{ $i }}
                        </a>
                    @endfor

                    @if($end < $total_pages)
                        @if($end < $total_pages - 1)
                            <span class="px-4 py-2">...</span>
                        @endif
                        <a href="{{ route('movies.index', array_merge(request()->all(), ['page' => $total_pages])) }}" 
                           class="bg-dark hover:bg-gray-800 px-4 py-2 rounded-lg transition">{{ $total_pages }}</a>
                    @endif
                </div>

                @if($current_page < $total_pages)
                    <a href="{{ route('movies.index', array_merge(request()->all(), ['page' => $current_page + 1])) }}" 
                       class="bg-dark hover:bg-gray-800 px-4 py-2 rounded-lg transition">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                @endif
            </div>
        @endif
    @else
        <div class="text-center py-20">
            <i class="fas fa-film text-6xl text-gray-600 mb-4"></i>
            <p class="text-gray-400 text-xl">Tidak ada film ditemukan</p>
        </div>
    @endif
</div>
@endsection
