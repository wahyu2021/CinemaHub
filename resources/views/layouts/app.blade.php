<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CinemaHub - Temukan Film Favorit')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#e50914',
                        secondary: '#564d4d',
                        dark: '#141414',
                        darker: '#0a0a0a'
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        * { font-family: 'Inter', sans-serif; }
        
        .skeleton {
            background: linear-gradient(90deg, #2a2a2a 25%, #3a3a3a 50%, #2a2a2a 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }
        
        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        .movie-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .movie-card:hover {
            transform: scale(1.05);
            z-index: 10;
        }
        
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="bg-darker dark:bg-darker text-white min-h-screen">
    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 transition-all duration-300 bg-gradient-to-b from-dark to-transparent" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-8">
                    <a href="{{ route('movies.index') }}" class="text-primary text-3xl font-bold tracking-tight">
                        CinemaHub
                    </a>
                    <div class="hidden md:flex space-x-4">
                        <a href="{{ route('movies.index') }}" class="hover:text-primary transition {{ request()->routeIs('movies.index') && !request()->has('category') ? 'text-primary' : 'text-gray-300' }}">
                            Populer
                        </a>
                        <a href="{{ route('movies.index', ['category' => 'now_playing']) }}" class="hover:text-primary transition {{ request()->get('category') === 'now_playing' ? 'text-primary' : 'text-gray-300' }}">
                            Sedang Tayang
                        </a>
                        <a href="{{ route('movies.index', ['category' => 'top_rated']) }}" class="hover:text-primary transition {{ request()->get('category') === 'top_rated' ? 'text-primary' : 'text-gray-300' }}">
                            Rating Tertinggi
                        </a>
                        <a href="{{ route('movies.index', ['category' => 'upcoming']) }}" class="hover:text-primary transition {{ request()->get('category') === 'upcoming' ? 'text-primary' : 'text-gray-300' }}">
                            Akan Datang
                        </a>
                        <a href="{{ route('movies.trending') }}" class="hover:text-primary transition {{ request()->routeIs('movies.trending') ? 'text-primary' : 'text-gray-300' }}">
                            Sedang Trending
                        </a>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- Search -->
                    <form action="{{ route('movies.search') }}" method="GET" class="relative">
                        <input 
                            type="text" 
                            name="q" 
                            placeholder="Cari film..." 
                            value="{{ request()->get('q') }}"
                            class="bg-dark border border-gray-700 rounded-full px-4 py-2 pl-10 w-64 focus:outline-none focus:border-primary transition"
                        >
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </form>
                    
                    <!-- Favorites -->
                    <button onclick="toggleFavorites()" class="relative hover:text-primary transition">
                        <i class="fas fa-heart text-xl"></i>
                        <span id="favorites-count" class="absolute -top-2 -right-2 bg-primary text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">0</span>
                    </button>
                    
                    <!-- Theme Toggle -->
                    <button onclick="toggleTheme()" class="hover:text-primary transition">
                        <i class="fas fa-moon text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-16">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark mt-20 py-8 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-gray-400">Dibuat dengan <i class="fas fa-heart text-primary"></i> menggunakan TMDB API</p>
            <p class="text-gray-500 text-sm mt-2">© 2025 CinemaHub. Hak cipta dilindungi.</p>
        </div>
    </footer>

    <!-- Favorites Modal -->
    <div id="favorites-modal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4">
        <div class="bg-dark rounded-lg max-w-4xl w-full max-h-[80vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-800 flex justify-between items-center">
                <h2 class="text-2xl font-bold">Film Favorit Saya</h2>
                <button onclick="toggleFavorites()" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            <div id="favorites-content" class="p-6 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4"></div>
        </div>
    </div>

    <script>
        // Favorites Management
        let favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
        
        function updateFavoritesCount() {
            const count = favorites.length;
            const badge = document.getElementById('favorites-count');
            if (count > 0) {
                badge.textContent = count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }
        
        function toggleFavorite(movieId, title, poster) {
            const index = favorites.findIndex(f => f.id === movieId);
            if (index > -1) {
                favorites.splice(index, 1);
            } else {
                favorites.push({ id: movieId, title, poster });
            }
            localStorage.setItem('favorites', JSON.stringify(favorites));
            updateFavoritesCount();
            updateFavoriteButtons();
        }
        
        function isFavorite(movieId) {
            return favorites.some(f => f.id === movieId);
        }
        
        function updateFavoriteButtons() {
            document.querySelectorAll('[data-movie-id]').forEach(btn => {
                const movieId = parseInt(btn.dataset.movieId);
                const icon = btn.querySelector('i');
                if (isFavorite(movieId)) {
                    icon.classList.remove('far');
                    icon.classList.add('fas', 'text-primary');
                } else {
                    icon.classList.remove('fas', 'text-primary');
                    icon.classList.add('far');
                }
            });
        }
        
        function toggleFavorites() {
            const modal = document.getElementById('favorites-modal');
            const content = document.getElementById('favorites-content');
            
            if (modal.classList.contains('hidden')) {
                content.innerHTML = favorites.length === 0 
                    ? '<p class="text-gray-400 col-span-full text-center py-8">Belum ada film favorit</p>'
                    : favorites.map(f => `
                        <div class="relative group">
                            <img src="https://image.tmdb.org/t/p/w200${f.poster}" alt="${f.title}" class="rounded-lg w-full">
                            <button onclick="toggleFavorite(${f.id}, '${f.title}', '${f.poster}')" class="absolute top-2 right-2 bg-black bg-opacity-75 rounded-full p-2 opacity-0 group-hover:opacity-100 transition">
                                <i class="fas fa-times text-white"></i>
                            </button>
                        </div>
                    `).join('');
                modal.classList.remove('hidden');
            } else {
                modal.classList.add('hidden');
            }
        }
        
        // Theme Toggle
        function toggleTheme() {
            document.documentElement.classList.toggle('dark');
        }
        
        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('bg-dark');
                navbar.classList.remove('bg-gradient-to-b', 'from-dark', 'to-transparent');
            } else {
                navbar.classList.remove('bg-dark');
                navbar.classList.add('bg-gradient-to-b', 'from-dark', 'to-transparent');
            }
        });
        
        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            updateFavoritesCount();
            updateFavoriteButtons();
        });
    </script>
    
    @stack('scripts')
</body>
</html>
