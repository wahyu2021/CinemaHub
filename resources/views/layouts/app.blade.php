<!DOCTYPE html>
<html lang="en" class="dark scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CinemaHub - Stream Future')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
                        primary: '#e50914', // Netflix Red
                        'primary-glow': '#ff0f1f',
                        secondary: '#564d4d',
                        dark: '#0f0f0f',
                        darker: '#050505',
                        'glass-bg': 'rgba(20, 20, 20, 0.7)',
                        'glass-border': 'rgba(255, 255, 255, 0.1)',
                    },
                    animation: {
                        'blob': 'blob 7s infinite',
                        'fade-in': 'fadeIn 0.5s ease-out forwards',
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #050505;
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(229, 9, 20, 0.08) 0%, transparent 25%), 
                radial-gradient(circle at 85% 30%, rgba(59, 130, 246, 0.08) 0%, transparent 25%);
            background-attachment: fixed;
            color: #ffffff;
            overflow-x: hidden;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #0a0a0a; 
        }
        ::-webkit-scrollbar-thumb {
            background: #333; 
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #e50914; 
        }

        /* Glassmorphism Utils */
        .glass {
            background: rgba(15, 15, 15, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        .glass-heavy {
            background: rgba(10, 10, 10, 0.9);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .glass-card {
            background: linear-gradient(145deg, rgba(255,255,255,0.03) 0%, rgba(255,255,255,0.01) 100%);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            border-color: rgba(229, 9, 20, 0.5);
            box-shadow: 0 0 20px rgba(229, 9, 20, 0.2);
            transform: translateY(-5px);
        }

        /* Neon Text */
        .text-glow {
            text-shadow: 0 0 10px rgba(229, 9, 20, 0.5);
        }

        /* Loader */
        #preloader {
            position: fixed;
            inset: 0;
            background: #050505;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s ease-out, visibility 0.5s ease-out;
        }
        
        .loader-spinner {
            width: 50px;
            height: 50px;
            border: 3px solid rgba(229, 9, 20, 0.3);
            border-radius: 50%;
            border-top-color: #e50914;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Smooth Reveal */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.5, 0, 0, 1);
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="antialiased selection:bg-primary selection:text-white">

    <!-- Preloader -->
    <div id="preloader">
        <div class="flex flex-col items-center gap-4">
            <div class="loader-spinner"></div>
            <div class="text-primary font-display font-bold tracking-widest text-xl animate-pulse">CINEMAHUB</div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 transition-all duration-300" id="navbar">
        <div class="absolute inset-0 glass opacity-0 transition-opacity duration-300" id="navbar-bg"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo & Links -->
                <div class="flex items-center gap-12">
                    <a href="{{ route('movies.index') }}" class="text-3xl font-display font-bold tracking-tighter text-white hover:text-primary transition-colors flex items-center gap-2 group">
                        <i class="fas fa-play-circle text-primary group-hover:text-white transition-colors duration-300"></i>
                        <span class="group-hover:text-glow transition-all">CinemaHub</span>
                    </a>
                    
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('movies.index') }}" class="text-sm font-medium hover:text-primary transition-colors {{ request()->routeIs('movies.index') && !request()->has('category') ? 'text-primary font-bold' : 'text-gray-400' }}">
                            Home
                        </a>
                        <a href="{{ route('movies.index', ['category' => 'now_playing']) }}" class="text-sm font-medium hover:text-primary transition-colors {{ request()->get('category') === 'now_playing' ? 'text-primary font-bold' : 'text-gray-400' }}">
                            Now Playing
                        </a>
                        <a href="{{ route('movies.trending') }}" class="text-sm font-medium hover:text-primary transition-colors {{ request()->routeIs('movies.trending') ? 'text-primary font-bold' : 'text-gray-400' }}">
                            Trending
                        </a>
                        <a href="{{ route('movies.index', ['category' => 'upcoming']) }}" class="text-sm font-medium hover:text-primary transition-colors {{ request()->get('category') === 'upcoming' ? 'text-primary font-bold' : 'text-gray-400' }}">
                            Upcoming
                        </a>
                    </div>
                </div>
                
                <!-- Right Side -->
                <div class="flex items-center gap-6">
                    <!-- Search -->
                    <form action="{{ route('movies.search') }}" method="GET" class="relative hidden md:block group">
                        <input 
                            type="text" 
                            name="q" 
                            placeholder="Search movies..." 
                            value="{{ request()->get('q') }}"
                            class="bg-white/5 border border-white/10 rounded-full px-5 py-2 pl-10 w-48 focus:w-64 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-primary focus:bg-black/50 transition-all duration-300"
                        >
                        <i class="fas fa-search absolute left-3.5 top-2.5 text-gray-500 group-focus-within:text-primary transition-colors"></i>
                    </form>

                    @auth
                        <a href="{{ route('watchlist.index') }}" class="text-gray-400 hover:text-primary transition-colors relative" title="My Watchlist">
                            <i class="fas fa-bookmark text-lg"></i>
                        </a>

                        <!-- User Menu -->
                        <div class="relative group">
                            <button class="flex items-center gap-3 focus:outline-none">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-primary to-purple-600 p-[2px]">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=000&color=fff" alt="Avatar" class="w-full h-full rounded-full object-cover border-2 border-black">
                                </div>
                            </button>
                            
                            <!-- Dropdown -->
                            <div class="absolute right-0 mt-4 w-56 glass rounded-xl shadow-2xl opacity-0 invisible transform translate-y-2 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 transition-all duration-200 z-50 overflow-hidden">
                                <div class="px-4 py-4 border-b border-white/10 bg-white/5">
                                    <p class="text-sm font-bold text-white">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <div class="py-2">
                                    <a href="{{ route('watchlist.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-white/10 transition-colors">
                                        <i class="fas fa-bookmark mr-2 w-5 text-primary"></i> Watchlist
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-500 hover:text-red-400 hover:bg-white/10 transition-colors">
                                            <i class="fas fa-sign-out-alt mr-2 w-5"></i> Sign Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-4">
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-300 hover:text-white transition-colors">Sign In</a>
                            <a href="{{ route('register') }}" class="bg-white text-black px-5 py-2 rounded-full text-sm font-bold hover:bg-primary hover:text-white hover:shadow-[0_0_15px_rgba(229,9,20,0.5)] transition-all duration-300">
                                Get Started
                            </a>
                        </div>
                    @endauth

                    <!-- Mobile Toggle -->
                    <button onclick="toggleMobileMenu()" class="md:hidden text-white text-xl hover:text-primary transition">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div class="fixed inset-0 z-[60] pointer-events-none">
        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300" id="mobile-overlay" onclick="toggleMobileMenu()"></div>
        
        <!-- Panel -->
        <div class="absolute top-0 right-0 h-full w-3/4 max-w-xs bg-[#111] border-l border-white/10 transform translate-x-full transition-transform duration-300 pointer-events-auto shadow-2xl" id="mobile-menu">
            <div class="p-6 flex flex-col h-full">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-2xl font-display font-bold text-white">Menu</h2>
                    <button onclick="toggleMobileMenu()" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>

                <form action="{{ route('movies.search') }}" method="GET" class="mb-8">
                    <div class="relative">
                        <input type="text" name="q" placeholder="Search..." class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-primary focus:outline-none">
                        <i class="fas fa-search absolute right-4 top-3.5 text-gray-500"></i>
                    </div>
                </form>

                <nav class="space-y-2 flex-1">
                    <a href="{{ route('movies.index') }}" class="block px-4 py-3 rounded-lg hover:bg-white/5 text-gray-300 hover:text-white transition">Home</a>
                    <a href="{{ route('movies.index', ['category' => 'now_playing']) }}" class="block px-4 py-3 rounded-lg hover:bg-white/5 text-gray-300 hover:text-white transition">Now Playing</a>
                    <a href="{{ route('movies.trending') }}" class="block px-4 py-3 rounded-lg hover:bg-white/5 text-gray-300 hover:text-white transition">Trending</a>
                    <a href="{{ route('movies.index', ['category' => 'upcoming']) }}" class="block px-4 py-3 rounded-lg hover:bg-white/5 text-gray-300 hover:text-white transition">Upcoming</a>
                </nav>

                @auth
                    <div class="border-t border-white/10 pt-6 mt-6">
                        <div class="flex items-center gap-3 mb-4 px-2">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=e50914&color=fff" class="w-10 h-10 rounded-full">
                            <div>
                                <p class="font-bold text-white">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500">User</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="w-full bg-red-600/20 text-red-500 py-3 rounded-lg font-semibold hover:bg-red-600 hover:text-white transition-all">Sign Out</button>
                        </form>
                    </div>
                @else
                    <div class="border-t border-white/10 pt-6 mt-6 space-y-3">
                        <a href="{{ route('login') }}" class="block w-full text-center py-3 rounded-lg border border-white/20 hover:bg-white hover:text-black transition">Sign In</a>
                        <a href="{{ route('register') }}" class="block w-full text-center py-3 rounded-lg bg-primary text-white font-bold shadow-lg shadow-primary/30">Get Started</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="pt-20 min-h-screen relative z-10">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="relative z-10 mt-20 border-t border-white/5 bg-[#050505]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="text-center md:text-left">
                    <h3 class="text-2xl font-display font-bold text-white mb-2">CinemaHub</h3>
                    <p class="text-gray-500 text-sm max-w-xs">Your premium destination for the latest movies, trending series, and cinematic masterpieces.</p>
                </div>
                <div class="flex gap-6">
                    <a href="#" class="text-gray-500 hover:text-primary transition text-xl"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-gray-500 hover:text-primary transition text-xl"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-gray-500 hover:text-primary transition text-xl"><i class="fab fa-github"></i></a>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-white/5 text-center text-gray-600 text-sm">
                <p>&copy; 2025 CinemaHub. Powered by TMDB API. Crafted for the Future.</p>
            </div>
        </div>
    </footer>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed top-24 right-6 z-50 space-y-3">
        @if(session('success'))
            <div class="toast glass bg-green-500/20 border-green-500/30 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center space-x-4 min-w-[320px] backdrop-blur-xl animate-fade-in">
                <div class="bg-green-500 rounded-full p-1"><i class="fas fa-check text-xs text-black"></i></div>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('info'))
            <div class="toast glass bg-blue-500/20 border-blue-500/30 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center space-x-4 min-w-[320px] backdrop-blur-xl animate-fade-in">
                <div class="bg-blue-500 rounded-full p-1"><i class="fas fa-info text-xs text-black"></i></div>
                <span class="font-medium">{{ session('info') }}</span>
            </div>
        @endif
    </div>

    <!-- Scroll to Top -->
    <button onclick="scrollToTop()" class="fixed bottom-8 right-8 bg-white text-black w-12 h-12 rounded-full shadow-[0_0_20px_rgba(255,255,255,0.3)] z-40 opacity-0 invisible transition-all duration-300 hover:scale-110 flex items-center justify-center group" id="scroll-top-btn">
        <i class="fas fa-arrow-up group-hover:-translate-y-1 transition-transform"></i>
    </button>

    <!-- Global Scripts -->
    <script>
        // Preloader
        window.addEventListener('load', () => {
            const preloader = document.getElementById('preloader');
            preloader.style.opacity = '0';
            preloader.style.visibility = 'hidden';
        });

        // Navbar Scroll Effect
        window.addEventListener('scroll', () => {
            const navbarBg = document.getElementById('navbar-bg');
            const scrollBtn = document.getElementById('scroll-top-btn');
            
            if (window.scrollY > 20) {
                navbarBg.classList.remove('opacity-0');
                navbarBg.classList.add('opacity-100');
            } else {
                navbarBg.classList.add('opacity-0');
                navbarBg.classList.remove('opacity-100');
            }
            
            if (window.scrollY > 400) {
                scrollBtn.classList.remove('opacity-0', 'invisible');
            } else {
                scrollBtn.classList.add('opacity-0', 'invisible');
            }
        });

        // Scroll Reveal Animation using Intersection Observer
        const observerOptions = {
            threshold: 0.1,
            rootMargin: "0px 0px -50px 0px"
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
            
            // Auto-hide toasts
            const serverToasts = document.querySelectorAll('.toast');
            serverToasts.forEach(toast => {
                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(100%)';
                    setTimeout(() => toast.remove(), 500);
                }, 4000);
            });
        });

        // Mobile Menu
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const overlay = document.getElementById('mobile-overlay');
            
            if (menu.classList.contains('translate-x-full')) {
                menu.classList.remove('translate-x-full');
                overlay.classList.remove('opacity-0', 'pointer-events-none');
            } else {
                menu.classList.add('translate-x-full');
                overlay.classList.add('opacity-0', 'pointer-events-none');
            }
        }

        // Scroll Top
        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Client-side Toast
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            const styles = type === 'success' 
                ? 'bg-green-500/20 border-green-500/30' 
                : 'bg-blue-500/20 border-blue-500/30';
            
            const icon = type === 'success' ? 'fa-check' : 'fa-info';
            const iconColor = type === 'success' ? 'bg-green-500' : 'bg-blue-500';

            toast.className = `toast glass ${styles} text-white px-6 py-4 rounded-xl shadow-2xl flex items-center space-x-4 min-w-[320px] backdrop-blur-xl animate-fade-in transition-all duration-500`;
            toast.innerHTML = `
                <div class="${iconColor} rounded-full p-1"><i class="fas ${icon} text-xs text-black"></i></div>
                <span class="font-medium">${message}</span>
            `;
            
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => toast.remove(), 500);
            }, 4000);
        }
    </script>
    
    @stack('scripts')
</body>
</html>
