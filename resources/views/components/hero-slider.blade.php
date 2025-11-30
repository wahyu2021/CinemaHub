@props(['movies'])

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
                <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10 mix-blend-overlay pointer-events-none"></div>

                <div class="absolute inset-0 flex items-center">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                        <div class="max-w-3xl relative z-20 pl-4 border-l-4 border-primary/0 slide-content transition-all duration-500 pb-32 md:pb-0">

                            <div class="overflow-hidden mb-4">
                                <span class="inline-block px-3 py-1 text-xs font-bold tracking-[0.2em] text-primary border border-primary/30 rounded-sm bg-primary/5 uppercase">
                                    {{ __('messages.trending') }}
                                </span>
                            </div>

                            <div class="overflow-hidden mb-4">
                                <h2 class="text-4xl md:text-7xl lg:text-8xl font-display font-bold leading-none text-white drop-shadow-2xl">
                                    {{ $movie['title'] }}
                                </h2>
                            </div>

                            <div class="flex items-center gap-4 text-gray-300 text-sm font-mono mb-6">
                                <span class="flex items-center gap-1"><i class="fas fa-star text-yellow-500"></i>
                                    {{ number_format($movie['vote_average'], 1) }}</span>
                                <span>|</span>
                                <span>{{ \Carbon\Carbon::parse($movie['release_date'])->format('Y') }}</span>
                                <span>|</span>
                                <span class="uppercase">{{ $movie['original_language'] }}</span>
                            </div>

                            <div class="overflow-hidden mb-8">
                                <p class="text-gray-300 text-lg md:text-xl font-light max-w-2xl leading-relaxed line-clamp-2 md:line-clamp-3 text-shadow-sm">
                                    {{ $movie['overview'] }}
                                </p>
                            </div>

                            <div class="flex flex-wrap gap-4">
                                <a href="{{ route('movies.show', $movie['id']) }}"
                                    class="relative px-6 py-3 md:px-8 md:py-4 bg-white text-black font-bold rounded-sm overflow-hidden group/btn hover:scale-105 transition-transform duration-300 text-sm md:text-base">
                                    <div class="absolute inset-0 bg-primary translate-x-[-100%] group-hover/btn:translate-x-0 transition-transform duration-300 ease-out z-0"></div>
                                    <span class="relative z-10 group-hover/btn:text-white transition-colors duration-300 flex items-center gap-3">
                                        <i class="fas fa-play"></i> {{ __('messages.watch_trailer') }}
                                    </span>
                                </a>
                                <a href="{{ route('movies.show', $movie['id']) }}"
                                    class="px-6 py-3 md:px-8 md:py-4 bg-white/5 backdrop-blur-md border border-white/20 text-white font-bold rounded-sm hover:bg-white/20 transition-all hover:scale-105 flex items-center gap-3 text-sm md:text-base">
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-6 md:pb-8 flex items-end justify-between gap-4">
            <div class="flex flex-col gap-4 pointer-events-auto">
                <div class="flex space-x-2 md:space-x-3">
                    @foreach (array_slice($movies, 0, 5) as $index => $movie)
                        <button class="indicator-btn w-8 md:w-12 h-1 rounded-full transition-all duration-300 bg-white/20 hover:bg-white/40 overflow-hidden relative"
                            onclick="manualSlide({{ $index }})">
                            <div class="progress-fill absolute top-0 left-0 h-full bg-primary w-0 {{ $index === 0 ? 'w-full' : '' }}"></div>
                        </button>
                    @endforeach
                </div>
                <div class="hidden md:block text-xs font-mono text-gray-500">
                    <span id="current-slide-num">01</span> / 05
                </div>
            </div>

            <div class="flex gap-2 pointer-events-auto">
                <button onclick="prevSlide()" class="w-10 h-10 md:w-12 md:h-12 rounded-full border border-white/10 bg-black/20 backdrop-blur-md flex items-center justify-center text-white hover:bg-primary hover:border-primary transition-all group">
                    <i class="fas fa-arrow-left transition-transform text-sm md:text-base"></i>
                </button>
                <button onclick="nextSlide()" class="w-10 h-10 md:w-12 md:h-12 rounded-full border border-white/10 bg-black/20 backdrop-blur-md flex items-center justify-center text-white hover:bg-primary hover:border-primary transition-all group">
                    <i class="fas fa-arrow-right transition-transform text-sm md:text-base"></i>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const slides = document.querySelectorAll('.hero-slide');
        const indicators = document.querySelectorAll('.indicator-btn .progress-fill');
        const numDisplay = document.getElementById('current-slide-num');
        let currentSlide = 0;
        let slideInterval;
        const duration = 6000;

        activateSlide(0);

        function activateSlide(index) {
            slides.forEach((slide) => {
                slide.classList.remove('opacity-100', 'z-10', 'active');
                slide.classList.add('opacity-0', 'z-0');
                const bg = slide.querySelector('.hero-bg');
                bg.style.transition = 'none';
                bg.style.transform = 'scale(1)';
            });

            indicators.forEach((bar) => {
                bar.style.width = '0%';
                bar.style.transition = 'none';
            });

            const activeSlide = slides[index];
            activeSlide.classList.remove('opacity-0', 'z-0');
            activeSlide.classList.add('opacity-100', 'z-10');
            
            // Reset BG Zoom
            const bg = activeSlide.querySelector('.hero-bg');
            // Force reflow
            void bg.offsetWidth;
            bg.style.transition = `transform ${duration + 500}ms ease-out`;
            bg.style.transform = 'scale(1.15)';

            const activeBar = indicators[index];
            activeBar.style.width = '0%';
            void activeBar.offsetWidth;
            activeBar.style.transition = `width ${duration}ms linear`;
            activeBar.style.width = '100%';

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

        startTimer();
    });
</script>
@endpush