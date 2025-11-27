@extends('layouts.app')

@section('title', 'CinemaHub - Temukan Film')

@section('content')

<!-- Hero Slider (Only on Home Page) -->
@if(($category === 'popular' || $category === null) && $current_page == 1 && count($movies) > 0)
<div class="relative w-full h-[50vh] md:h-[70vh] overflow-hidden mb-8 group" id="hero-slider">
    @foreach(array_slice($movies, 0, 5) as $index => $movie)
        <div class="hero-slide absolute inset-0 w-full h-full transition-opacity duration-1000 ease-in-out {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}" data-index="{{ $index }}">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-[10000ms] ease-linear hover:scale-105" 
                 style="background-image: url('https://image.tmdb.org/t/p/original{{ $movie['backdrop_path'] }}');">
            </div>
            <!-- Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-darker via-darker/50 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-darker via-darker/50 to-transparent"></div>
            
            <!-- Content -->
            <div class="absolute inset-0 flex items-center">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                    <div class="max-w-2xl">
                        <span class="text-primary font-semibold tracking-wider uppercase text-sm mb-2 block animate-fade-in-up">Sedang Populer</span>
                        <h2 class="text-4xl md:text-6xl font-bold mb-4 leading-tight animate-fade-in-up" style="animation-delay: 0.1s">{{ $movie['title'] }}</h2>
                        <p class="text-gray-300 text-lg mb-6 line-clamp-3 animate-fade-in-up" style="animation-delay: 0.2s">{{ $movie['overview'] }}</p>
                        
                        <div class="flex flex-wrap gap-4 animate-fade-in-up" style="animation-delay: 0.3s">
                            <a href="{{ route('movies.show', $movie['id']) }}" class="bg-primary hover:bg-red-700 text-white px-8 py-3 rounded-lg font-bold transition flex items-center shadow-lg hover:shadow-red-900/50">
                                <i class="fas fa-play mr-2"></i> Lihat Detail
                            </a>
                            <button class="bg-gray-800 hover:bg-gray-700 text-white px-8 py-3 rounded-lg font-bold transition flex items-center bg-opacity-80 backdrop-blur-sm">
                                <i class="fas fa-info-circle mr-2"></i> Selengkapnya
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Indicators -->
    <div class="absolute bottom-8 right-0 left-0 flex justify-center z-20 space-x-3">
        @foreach(array_slice($movies, 0, 5) as $index => $movie)
            <button class="w-3 h-3 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-primary w-8' : 'bg-gray-400 hover:bg-white' }}" 
                    onclick="goToSlide({{ $index }})"></button>
        @endforeach
    </div>
    
    <!-- Navigation Arrows -->
    <button onclick="prevSlide()" class="absolute left-4 top-1/2 transform -translate-y-1/2 z-20 bg-black bg-opacity-50 hover:bg-primary text-white p-3 rounded-full transition opacity-0 group-hover:opacity-100">
        <i class="fas fa-chevron-left"></i>
    </button>
    <button onclick="nextSlide()" class="absolute right-4 top-1/2 transform -translate-y-1/2 z-20 bg-black bg-opacity-50 hover:bg-primary text-white p-3 rounded-full transition opacity-0 group-hover:opacity-100">
        <i class="fas fa-chevron-right"></i>
    </button>
</div>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        opacity: 0; /* Start hidden */
    }
</style>

<script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.hero-slide');
    const indicators = document.querySelectorAll('#hero-slider .bottom-8 button');
    let slideInterval;

    function showSlide(index) {
        slides.forEach(slide => {
            slide.classList.remove('opacity-100', 'z-10');
            slide.classList.add('opacity-0', 'z-0');
        });
        
        indicators.forEach(ind => {
            ind.classList.remove('bg-primary', 'w-8');
            ind.classList.add('bg-gray-400');
        });

        slides[index].classList.remove('opacity-0', 'z-0');
        slides[index].classList.add('opacity-100', 'z-10');
        
        // Reset animations
        const animatedElements = slides[index].querySelectorAll('.animate-fade-in-up');
        animatedElements.forEach(el => {
            el.style.animationName = 'none';
            void el.offsetWidth; // trigger reflow
            el.style.animationName = 'fadeInUp';
        });

        indicators[index].classList.remove('bg-gray-400');
        indicators[index].classList.add('bg-primary', 'w-8');
        
        currentSlide = index;
    }

    function nextSlide() {
        let next = (currentSlide + 1) % slides.length;
        showSlide(next);
    }

    function prevSlide() {
        let prev = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(prev);
    }
    
    function goToSlide(index) {
        showSlide(index);
        resetTimer();
    }

    function startTimer() {
        slideInterval = setInterval(nextSlide, 6000);
    }

    function resetTimer() {
        clearInterval(slideInterval);
        startTimer();
    }

    document.addEventListener('DOMContentLoaded', () => {
        startTimer();
    });
</script>
@endif

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
                <x-movie-card :movie="$movie" />
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
