<!DOCTYPE html>
<html lang="en" class="dark scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CinemaHub - Future Stream')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Space+Grotesk:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                        display: ['Space Grotesk', 'sans-serif'],
                    },
                    colors: {
                        primary: '#e50914',
                        'primary-glow': '#ff0f1f',
                        dark: '#050505',
                        'glass-border': 'rgba(255, 255, 255, 0.08)',
                    },
                    animation: {
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0)'
                            },
                            '50%': {
                                transform: 'translateY(-20px)'
                            },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --cursor-size: 20px;
            --cursor-outline-size: 40px;
        }

        body {
            background-color: #030303;
            color: #ffffff;
            overflow-x: hidden;
            cursor: none;
            /* Hide default cursor */
        }

        /* Futuristic Background Grid */
        .bg-grid {
            position: fixed;
            top: 0;
            left: 0;
            width: 200vw;
            height: 200vh;
            background-image:
                linear-gradient(rgba(229, 9, 20, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(229, 9, 20, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            transform: perspective(500px) rotateX(60deg) translateY(-100px) translateZ(-200px);
            animation: gridMove 20s linear infinite;
            z-index: -2;
            pointer-events: none;
        }

        @keyframes gridMove {
            0% {
                transform: perspective(500px) rotateX(60deg) translateY(0) translateZ(-200px);
            }

            100% {
                transform: perspective(500px) rotateX(60deg) translateY(50px) translateZ(-200px);
            }
        }

        /* Noise Texture */
        .noise-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 9000;
            opacity: 0.03;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
        }

        /* Custom Cursor */
        #cursor-dot,
        #cursor-outline {
            position: fixed;
            top: 0;
            left: 0;
            transform: translate(-50%, -50%);
            border-radius: 50%;
            z-index: 9999;
            pointer-events: none;
        }

        #cursor-dot {
            width: var(--cursor-size);
            height: var(--cursor-size);
            background-color: #e50914;
            box-shadow: 0 0 10px #e50914;
        }

        #cursor-outline {
            width: var(--cursor-outline-size);
            height: var(--cursor-outline-size);
            border: 1px solid rgba(255, 255, 255, 0.5);
            transition: width 0.2s, height 0.2s, background-color 0.2s;
        }

        /* Glassmorphism */
        .glass {
            background: rgba(10, 10, 10, 0.6);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        .glass-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.03), rgba(255, 255, 255, 0.01));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #050505;
        }

        ::-webkit-scrollbar-thumb {
            background: #e50914;
            border-radius: 10px;
        }

        /* Utilities */
        .text-glow {
            text-shadow: 0 0 20px rgba(229, 9, 20, 0.6);
        }

        /* 3D Tilt Wrapper */
        .tilt-card {
            transform-style: preserve-3d;
            transform: perspective(1000px);
        }

        .tilt-content {
            transform: translateZ(20px);
        }
    </style>
</head>

<body class="antialiased selection:bg-primary selection:text-white">
    <div id="page-transition"class="fixed inset-0 z-[9999] bg-black pointer-events-none opacity-0 transition-opacity duration-500"></div>

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
                    @foreach ([['name' => 'Home', 'route' => 'movies.index', 'params' => []], ['name' => 'Now Playing', 'route' => 'movies.index', 'params' => ['category' => 'now_playing']], ['name' => 'Trending', 'route' => 'movies.trending', 'params' => []], ['name' => 'Upcoming', 'route' => 'movies.index', 'params' => ['category' => 'upcoming']]] as $link)
                        <a href="{{ route($link['route'], $link['params']) }}"
                            class="relative text-sm font-medium text-gray-400 hover:text-white transition-colors py-2 group cursor-hover">
                            {{ $link['name'] }}
                            <span
                                class="absolute bottom-0 left-0 w-0 h-[2px] bg-primary transition-all duration-300 group-hover:w-full box-shadow-glow"></span>
                        </a>
                    @endforeach
                </div>

                <div class="flex items-center gap-6">
                    <button class="cursor-hover text-gray-400 hover:text-primary transition-transform hover:scale-110" onclick="toggleSearch(true)">
                        <i class="fas fa-search text-xl"></i>
                    </button>

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
    </nav>

    <main class="pt-24 min-h-screen relative z-10 px-4 sm:px-6 lg:px-8 max-w-[1600px] mx-auto">
        @yield('content')
    </main>

    <footer class="relative z-10 mt-32 border-t border-white/5 bg-black/50 backdrop-blur-lg">
        <div class="max-w-7xl mx-auto px-8 py-12 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-gray-500 text-sm font-display">
                &copy; 2025 CINEMAHUB <span class="text-primary mx-2">•</span> FUTURE OF STREAMING
            </div>
            <div class="flex gap-6">
                <a href="#" class="text-gray-500 hover:text-primary transition-colors cursor-hover"><i
                        class="fab fa-github text-xl"></i></a>
                <a href="#" class="text-gray-500 hover:text-primary transition-colors cursor-hover"><i
                        class="fab fa-twitter text-xl"></i></a>
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
                            autocomplete="off">
                        <div
                            class="hidden md:flex items-center gap-2 text-xs text-gray-500 font-mono border border-white/10 px-2 py-1 rounded">
                            <span>ESC</span> to close
                        </div>
                    </div>

                    <div class="border-t border-white/5 bg-white/[0.02] p-4">
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

    <script>
        // Cursor Logic
        const cursorDot = document.getElementById('cursor-dot');
        const cursorOutline = document.getElementById('cursor-outline');

        window.addEventListener('mousemove', (e) => {
            const posX = e.clientX;
            const posY = e.clientY;

            // Dot follows instantly
            cursorDot.style.left = `${posX}px`;
            cursorDot.style.top = `${posY}px`;

            // Outline follows with delay
            cursorOutline.animate({
                left: `${posX}px`,
                top: `${posY}px`
            }, {
                duration: 500,
                fill: "forwards"
            });
        });

        // Hover Effect for Cursor
        document.querySelectorAll('a, button, .cursor-hover').forEach(el => {
            el.addEventListener('mouseenter', () => {
                cursorOutline.style.transform = 'translate(-50%, -50%) scale(1.5)';
                cursorOutline.style.backgroundColor = 'rgba(255, 255, 255, 0.1)';
                cursorDot.style.transform = 'translate(-50%, -50%) scale(0.5)';
            });
            el.addEventListener('mouseleave', () => {
                cursorOutline.style.transform = 'translate(-50%, -50%) scale(1)';
                cursorOutline.style.backgroundColor = 'transparent';
                cursorDot.style.transform = 'translate(-50%, -50%) scale(1)';
            });
        });

        // 3D Tilt Logic for Cards
        document.addEventListener('mousemove', (e) => {
            document.querySelectorAll('.tilt-card').forEach(card => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                if (x >= 0 && y >= 0 && x <= rect.width && y <= rect.height) {
                    const centerX = rect.width / 2;
                    const centerY = rect.height / 2;

                    const rotateX = ((y - centerY) / centerY) * -10; // Max rotation 10deg
                    const rotateY = ((x - centerX) / centerX) * 10;

                    card.style.transform =
                        `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
                } else {
                    card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale3d(1, 1, 1)';
                }
            });
        });

        function toggleSearch(show) {
            const modal = document.getElementById('search-modal');
            const input = document.getElementById('search-input');

            if (show) {
                modal.classList.remove('hidden');
                // Small delay to allow display:block to apply before animation
                setTimeout(() => {
                    modal.classList.remove('opacity-0', 'scale-95');
                    modal.classList.add('opacity-100', 'scale-100');
                    input.focus();
                }, 10);
            } else {
                modal.classList.remove('opacity-100', 'scale-100');
                modal.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }
        }

        // Keyboard shortcut (Ctrl/Cmd + K)
        document.addEventListener('keydown', (e) => {
            if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                e.preventDefault();
                toggleSearch(true);
            }
            if (e.key === 'Escape') {
                toggleSearch(false);
                closeUserDropdown();
            }
        });

        // User Dropdown
        function toggleUserDropdown() {
            const menu = document.getElementById('user-dropdown-menu');
            if (menu) {
                menu.classList.toggle('hidden');
            }
        }

        function closeUserDropdown() {
            const menu = document.getElementById('user-dropdown-menu');
            if (menu) {
                menu.classList.add('hidden');
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            const dropdown = document.getElementById('user-dropdown');
            if (dropdown && !dropdown.contains(e.target)) {
                closeUserDropdown();
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
