<!DOCTYPE html>
<html lang="en" class="dark scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CinemaHub - Future Stream')</title>
    <meta name="description" content="@yield('description', 'Explore the cinematic universe. Discover new movies, track your watchlist, and stay updated with the latest trends.')">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'CinemaHub - Future Stream')">
    <meta property="og:description" content="@yield('description', 'Explore the cinematic universe. Discover new movies, track your watchlist, and stay updated with the latest trends.')">
    <meta property="og:image" content="@yield('image', 'https://images.unsplash.com/photo-1536440136628-849c177e76a1?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80')">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', 'CinemaHub - Future Stream')">
    <meta property="twitter:description" content="@yield('description', 'Explore the cinematic universe. Discover new movies, track your watchlist, and stay updated with the latest trends.')">
    <meta property="twitter:image" content="@yield('image', 'https://images.unsplash.com/photo-1536440136628-849c177e76a1?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80')">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Space+Grotesk:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    @include('layouts.partials.styles')
</head>

<body class="antialiased selection:bg-primary selection:text-white">
    <div id="page-transition"class="fixed inset-0 z-[9999] bg-black pointer-events-none opacity-0 transition-opacity duration-500"></div>
    
    <div id="loading-overlay" class="fixed inset-0 z-[10000] bg-[#020202] flex items-center justify-center transition-opacity duration-500 pointer-events-none opacity-0">
        <div class="relative flex flex-col items-center">
            <!-- Ambient Glow -->
            <div class="absolute w-96 h-96 bg-primary/20 rounded-full blur-[120px] animate-pulse"></div>
            
            <div class="relative z-10 flex flex-col items-center gap-6">
                <!-- Icon -->
                <div class="relative">
                    <i class="fas fa-film text-6xl text-white drop-shadow-[0_0_15px_rgba(229,9,20,0.5)] animate-bounce"></i>
                </div>
                
                <!-- Text -->
                <div class="text-center">
                    <h2 class="text-3xl font-display font-bold text-white tracking-tighter mb-3">
                        CINEMA<span class="text-primary">HUB</span>
                    </h2>
                    <!-- Loading Dots -->
                    <div class="flex items-center justify-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-white animate-pulse" style="animation-delay: 0ms"></span>
                        <span class="w-3 h-3 rounded-full bg-primary animate-pulse" style="animation-delay: 150ms"></span>
                        <span class="w-3 h-3 rounded-full bg-white animate-pulse" style="animation-delay: 300ms"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="cursor-dot" class="hidden md:block"></div>
    <div id="cursor-outline" class="hidden md:block"></div>

    <div class="bg-grid"></div>
    <div class="noise-overlay"></div>

    <div
        class="fixed top-0 left-0 w-[500px] h-[500px] bg-primary/20 rounded-full blur-[120px] -translate-x-1/2 -translate-y-1/2 pointer-events-none z-[-1]">
    </div>
    <div
        class="fixed bottom-0 right-0 w-[500px] h-[500px] bg-blue-600/10 rounded-full blur-[120px] translate-x-1/2 translate-y-1/2 pointer-events-none z-[-1]">
    </div>

    <nav class="fixed top-0 w-full z-50 transition-all duration-300 border-b border-white/5" id="navbar">
        <div class="absolute inset-0 glass opacity-90"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <a href="{{ route('movies.index') }}" class="relative group cursor-hover">
                    <div class="flex items-center gap-2">
                        <i
                            class="fas fa-film text-3xl text-primary group-hover:rotate-12 transition-transform duration-300"></i>
                        <span
                            class="text-2xl font-display font-bold tracking-tighter text-white group-hover:text-glow transition-all">
                            CINEMA<span class="text-primary">HUB</span>
                        </span>
                    </div>
                </a>

                <div class="hidden md:flex items-center gap-8">
                    @foreach ([
                        ['name' => __('messages.home'), 'route' => 'movies.index', 'params' => []],
                        ['name' => __('messages.now_playing'), 'route' => 'movies.index', 'params' => ['category' => 'now_playing']],
                        ['name' => __('messages.trending'), 'route' => 'movies.trending', 'params' => []],
                        ['name' => __('messages.upcoming'), 'route' => 'movies.index', 'params' => ['category' => 'upcoming']]
                    ] as $link)
                        @php
                            $isActive = false;
                            if ($link['route'] === 'movies.index' && isset($link['params']['category'])) {
                                $isActive = request()->routeIs('movies.index') && request('category') === $link['params']['category'];
                            } elseif ($link['route'] === 'movies.index' && empty($link['params'])) {
                                $isActive = request()->routeIs('movies.index') && !request('category');
                            } else {
                                $isActive = request()->routeIs($link['route']);
                            }
                        @endphp
                        <a href="{{ route($link['route'], $link['params']) }}"
                            class="relative text-sm font-medium transition-colors py-2 group cursor-hover {{ $isActive ? 'text-white' : 'text-gray-400 hover:text-white' }}">
                            {{ $link['name'] }}
                            <span
                                class="absolute bottom-0 left-0 h-[2px] bg-primary transition-all duration-300 box-shadow-glow {{ $isActive ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                        </a>
                    @endforeach
                </div>

                <div class="flex items-center gap-6">
                    {{-- Language Switcher --}}
                    <div class="hidden md:block relative group" id="lang-dropdown">
                        <button onclick="toggleLangDropdown()" class="flex items-center gap-2 text-gray-400 hover:text-white transition-colors cursor-hover">
                            <i class="fas fa-globe"></i>
                            <span class="text-sm font-bold uppercase">{{ app()->getLocale() }}</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div id="lang-dropdown-menu" class="hidden absolute right-0 mt-2 w-32 glass rounded-xl border border-white/10 shadow-xl py-1 z-50 overflow-hidden">
                            <a href="{{ route('lang.switch', 'en') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-white/10 hover:text-white {{ app()->getLocale() == 'en' ? 'bg-white/5 text-primary' : '' }}">
                                <span class="w-6 inline-block">🇺🇸</span> English
                            </a>
                            <a href="{{ route('lang.switch', 'id') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-white/10 hover:text-white {{ app()->getLocale() == 'id' ? 'bg-white/5 text-primary' : '' }}">
                                <span class="w-6 inline-block">🇮🇩</span> Indonesia
                            </a>
                        </div>
                    </div>

                    <button class="cursor-hover text-gray-400 hover:text-primary transition-transform hover:scale-110" onclick="toggleSearch(true)">
                        <i class="fas fa-search text-xl"></i>
                    </button>

                    {{-- Mobile Menu Button --}}
                    <button onclick="toggleMobileMenu()" class="md:hidden text-gray-400 hover:text-white transition-colors focus:outline-none">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>

                    <div class="hidden md:flex items-center gap-6">
                        @auth
                            <a href="{{ route('watchlist.index') }}"
                                class="cursor-hover relative text-gray-400 hover:text-primary transition-transform hover:scale-110">
                                <i class="fas fa-bookmark text-xl"></i>
                                <span class="absolute -top-1 -right-1 w-2 h-2 bg-primary rounded-full animate-pulse"></span>
                            </a>

                            <div class="relative" id="user-dropdown">
                                <button onclick="toggleUserDropdown()" class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-black p-[2px] cursor-hover hover:shadow-[0_0_15px_rgba(229,9,20,0.5)] transition-all">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=000&color=fff"
                                        class="w-full h-full rounded-full object-cover">
                                </button>

                                <div id="user-dropdown-menu" class="hidden absolute right-0 mt-4 w-64 glass rounded-2xl border border-white/10 shadow-2xl py-2 z-50">
                                    <div class="px-4 py-3 border-b border-white/10">
                                        <p class="text-white font-bold">{{ Auth::user()->name }}</p>
                                        <p class="text-gray-400 text-sm truncate">{{ Auth::user()->email }}</p>
                                        @if(!Auth::user()->hasVerifiedEmail())
                                            <span class="text-xs text-yellow-400 bg-yellow-500/20 px-2 py-0.5 rounded-full mt-1 inline-block">Unverified</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-white/5 hover:text-white transition-colors">
                                        <i class="fas fa-user w-5"></i>
                                        <span>Profil Saya</span>
                                    </a>
                                    <a href="{{ route('watchlist.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-white/5 hover:text-white transition-colors">
                                        <i class="fas fa-bookmark w-5"></i>
                                        <span>Watchlist</span>
                                    </a>
                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-white/5 hover:text-white transition-colors">
                                        <i class="fas fa-cog w-5"></i>
                                        <span>Pengaturan</span>
                                    </a>
                                    <div class="border-t border-white/10 mt-2 pt-2">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex items-center gap-3 px-4 py-3 text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-colors w-full text-left">
                                                <i class="fas fa-sign-out-alt w-5"></i>
                                                <span>Logout</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}"
                                class="cursor-hover px-6 py-2 rounded-full border border-white/20 hover:bg-white hover:text-black transition-all font-medium text-sm">Sign In</a>
                            <a href="{{ route('register') }}"
                                class="cursor-hover px-6 py-2 rounded-full bg-primary hover:bg-red-700 text-white transition-all font-medium text-sm">Sign Up</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="hidden md:hidden absolute top-20 left-0 w-full glass border-b border-white/10 shadow-2xl transition-all duration-300">
            <div class="px-4 py-4 space-y-2">
                 @foreach ([
                    ['name' => __('messages.home'), 'route' => 'movies.index', 'params' => []],
                    ['name' => __('messages.now_playing'), 'route' => 'movies.index', 'params' => ['category' => 'now_playing']],
                    ['name' => __('messages.trending'), 'route' => 'movies.trending', 'params' => []],
                    ['name' => __('messages.upcoming'), 'route' => 'movies.index', 'params' => ['category' => 'upcoming']]
                ] as $link)
                    <a href="{{ route($link['route'], $link['params']) }}" 
                       class="block px-4 py-3 rounded-xl text-base font-medium transition-colors hover:bg-white/10 {{ request()->fullUrl() === route($link['route'], $link['params']) ? 'bg-primary/20 text-white' : 'text-gray-300' }}">
                        {{ $link['name'] }}
                    </a>
                @endforeach
                
                <div class="border-t border-white/10 my-2 pt-2"></div>

                {{-- Mobile Auth & Language --}}
                <div class="flex items-center justify-between px-4 py-2">
                     {{-- Language --}}
                    <div class="flex gap-4">
                         <a href="{{ route('lang.switch', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'text-white font-bold' : 'text-gray-500' }}">EN</a>
                         <span class="text-gray-600">|</span>
                         <a href="{{ route('lang.switch', 'id') }}" class="{{ app()->getLocale() == 'id' ? 'text-white font-bold' : 'text-gray-500' }}">ID</a>
                    </div>
                </div>

                @auth
                    <div class="px-4 py-2 flex items-center gap-3 border-t border-white/10 mt-2 pt-4">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=000&color=fff" class="w-10 h-10 rounded-full">
                        <div>
                            <p class="text-white font-bold text-sm">{{ Auth::user()->name }}</p>
                            <a href="{{ route('profile.show') }}" class="text-xs text-primary hover:underline">View Profile</a>
                        </div>
                    </div>
                    <a href="{{ route('watchlist.index') }}" class="block px-4 py-3 text-gray-300 hover:text-white">Watchlist</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-3 text-red-400 hover:text-red-300">Logout</button>
                    </form>
                @else
                    <div class="grid grid-cols-2 gap-4 px-4 pt-4">
                        <a href="{{ route('login') }}" class="text-center py-2 rounded-lg border border-white/20 text-white">Sign In</a>
                        <a href="{{ route('register') }}" class="text-center py-2 rounded-lg bg-primary text-white">Sign Up</a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <main class="pt-24 min-h-screen relative z-10 px-4 sm:px-6 lg:px-8 max-w-[1600px] mx-auto">
        @yield('content')
    </main>

    <footer class="relative z-10 mt-32 border-t border-white/5 bg-[#050505] pt-20 pb-10 overflow-hidden">
        {{-- Decorative Elements --}}
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[1px] bg-gradient-to-r from-transparent via-primary/50 to-transparent"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-primary/5 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-600/5 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                {{-- Brand Column --}}
                <div class="space-y-6">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-film text-3xl text-primary"></i>
                        <span class="text-2xl font-display font-bold tracking-tighter text-white">
                            CINEMA<span class="text-primary">HUB</span>
                        </span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        {{ __('messages.footer_desc') }}
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center text-gray-400 hover:bg-primary hover:text-white transition-all duration-300 hover:-translate-y-1">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center text-gray-400 hover:bg-primary hover:text-white transition-all duration-300 hover:-translate-y-1">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center text-gray-400 hover:bg-primary hover:text-white transition-all duration-300 hover:-translate-y-1">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center text-gray-400 hover:bg-primary hover:text-white transition-all duration-300 hover:-translate-y-1">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h3 class="text-white font-bold text-lg mb-6 relative inline-block">
                        {{ __('messages.quick_links') }}
                        <span class="absolute -bottom-2 left-0 w-1/2 h-[2px] bg-primary"></span>
                    </h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('movies.index') }}" class="text-gray-400 hover:text-primary transition-colors flex items-center gap-2 text-sm"><i class="fas fa-chevron-right text-xs opacity-50"></i> {{ __('messages.home') }}</a></li>
                        <li><a href="{{ route('movies.trending') }}" class="text-gray-400 hover:text-primary transition-colors flex items-center gap-2 text-sm"><i class="fas fa-chevron-right text-xs opacity-50"></i> {{ __('messages.trending') }}</a></li>
                        <li><a href="{{ route('movies.index', ['category' => 'now_playing']) }}" class="text-gray-400 hover:text-primary transition-colors flex items-center gap-2 text-sm"><i class="fas fa-chevron-right text-xs opacity-50"></i> {{ __('messages.now_playing') }}</a></li>
                        <li><a href="{{ route('movies.index', ['category' => 'upcoming']) }}" class="text-gray-400 hover:text-primary transition-colors flex items-center gap-2 text-sm"><i class="fas fa-chevron-right text-xs opacity-50"></i> {{ __('messages.upcoming') }}</a></li>
                    </ul>
                </div>

                {{-- Categories --}}
                <div>
                    <h3 class="text-white font-bold text-lg mb-6 relative inline-block">
                        {{ __('messages.categories') }}
                        <span class="absolute -bottom-2 left-0 w-1/2 h-[2px] bg-primary"></span>
                    </h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('movies.index', ['category' => 'popular']) }}" class="text-gray-400 hover:text-primary transition-colors flex items-center gap-2 text-sm"><i class="fas fa-chevron-right text-xs opacity-50"></i> Action Movies</a></li>
                        <li><a href="{{ route('movies.index', ['category' => 'top_rated']) }}" class="text-gray-400 hover:text-primary transition-colors flex items-center gap-2 text-sm"><i class="fas fa-chevron-right text-xs opacity-50"></i> {{ __('messages.top_rated') }}</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-primary transition-colors flex items-center gap-2 text-sm"><i class="fas fa-chevron-right text-xs opacity-50"></i> TV Shows</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-primary transition-colors flex items-center gap-2 text-sm"><i class="fas fa-chevron-right text-xs opacity-50"></i> Reviews</a></li>
                    </ul>
                </div>

                {{-- Newsletter --}}
                <div>
                    <h3 class="text-white font-bold text-lg mb-6 relative inline-block">
                        {{ __('messages.newsletter') }}
                        <span class="absolute -bottom-2 left-0 w-1/2 h-[2px] bg-primary"></span>
                    </h3>
                    <p class="text-gray-400 text-sm mb-4">{{ __('messages.newsletter_desc') }}</p>
                    <form class="relative">
                        <input type="email" placeholder="{{ __('messages.subscribe_placeholder') }}" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-primary/50 transition-all">
                        <button type="button" class="absolute right-1.5 top-1.5 bg-primary hover:bg-primary-glow text-white w-8 h-8 rounded-md flex items-center justify-center transition-all">
                            <i class="fas fa-paper-plane text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Bottom Bar --}}
            <div class="pt-8 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-500 text-sm font-display">
                    &copy; 2025 <span class="text-white font-bold">CINEMAHUB</span>. {{ __('messages.rights_reserved') }}
                </p>
                <div class="flex gap-6 text-sm text-gray-500">
                    <a href="#" class="hover:text-white transition-colors">{{ __('messages.privacy') }}</a>
                    <a href="#" class="hover:text-white transition-colors">{{ __('messages.terms') }}</a>
                    <a href="#" class="hover:text-white transition-colors">{{ __('messages.cookies') }}</a>
                </div>
            </div>
        </div>
    </footer>

    <div id="search-modal"
        class="fixed inset-0 z-[100] bg-black/90 backdrop-blur-2xl hidden transition-all duration-300 opacity-0 scale-95"
        onclick="toggleSearch(false)">

        <div class="max-w-3xl w-full mx-auto mt-20 px-4" onclick="event.stopPropagation()">
            <div class="relative group">
                <div
                    class="absolute -inset-1 bg-gradient-to-r from-primary to-blue-600 rounded-2xl blur opacity-25 group-focus-within:opacity-100 transition duration-1000 group-hover:opacity-100">
                </div>

                <form action="{{ route('movies.search') }}"
                    class="relative bg-black rounded-2xl border border-white/10 shadow-2xl overflow-hidden">
                    <div class="flex items-center px-6 py-4">
                        <i class="fas fa-search text-2xl text-gray-500 mr-4"></i>
                        <input type="text" name="q" id="search-input"
                            placeholder="Search movies, genres, actors..."
                            class="w-full bg-transparent text-2xl font-display font-bold text-white placeholder-gray-700 focus:outline-none h-12"
                            autocomplete="off"
                            oninput="handleSearchInput(this.value)">
                        <div
                            class="hidden md:flex items-center gap-2 text-xs text-gray-500 font-mono border border-white/10 px-2 py-1 rounded">
                            <span>ESC</span> to close
                        </div>
                    </div>

                    <div id="search-results-container" class="hidden border-t border-white/5 bg-white/[0.02] p-4 max-h-[60vh] overflow-y-auto custom-scrollbar">
                        <!-- Results injected here -->
                    </div>

                    <div id="default-search-links" class="border-t border-white/5 bg-white/[0.02] p-4">
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mb-3 px-2">Quick Links</p>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                            <a href="{{ route('movies.trending') }}"
                                class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 transition group cursor-pointer">
                                <div
                                    class="w-8 h-8 rounded-lg bg-primary/20 flex items-center justify-center text-primary">
                                    <i class="fas fa-fire"></i>
                                </div>
                                <span class="text-sm text-gray-300 group-hover:text-white">Trending Now</span>
                            </a>
                            <a href="{{ route('movies.index', ['category' => 'top_rated']) }}"
                                class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 transition group cursor-pointer">
                                <div
                                    class="w-8 h-8 rounded-lg bg-yellow-500/20 flex items-center justify-center text-yellow-500">
                                    <i class="fas fa-star"></i>
                                </div>
                                <span class="text-sm text-gray-300 group-hover:text-white">Top Rated</span>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('layouts.partials.scripts')
    @stack('scripts')
</body>
</html>
