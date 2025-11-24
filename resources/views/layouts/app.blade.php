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
        
        /* Toast Notifications */
        .toast {
            animation: slideInRight 0.3s ease-out;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        .toast.hiding {
            animation: slideOutRight 0.3s ease-in;
        }
        
        /* Scroll to top button */
        .scroll-to-top {
            transition: all 0.3s ease;
            opacity: 0;
            visibility: hidden;
        }
        
        .scroll-to-top.show {
            opacity: 1;
            visibility: visible;
        }
        
        /* Star rating */
        .star-rating .star {
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .star-rating .star:hover,
        .star-rating .star.active {
            color: #fbbf24;
            transform: scale(1.2);
        }
        

        /* Badge animations */
        .badge-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        /* Mobile menu animation */
        .mobile-menu {
            transition: transform 0.3s ease-in-out;
            transform: translateX(-100%);
        }
        
        .mobile-menu.open {
            transform: translateX(0);
        }
    </style>
</head>
<body class="bg-darker dark:bg-darker text-white min-h-screen">
    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 transition-all duration-300 bg-gradient-to-b from-dark to-transparent" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-8">
                    <!-- Mobile Menu Button -->
                    <button onclick="toggleMobileMenu()" class="md:hidden text-white hover:text-primary transition">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                    
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
                    <form action="{{ route('movies.search') }}" method="GET" class="relative hidden md:block">
                        <input 
                            type="text" 
                            name="q" 
                            placeholder="Cari film..." 
                            value="{{ request()->get('q') }}"
                            class="bg-dark border border-gray-700 rounded-full px-4 py-2 pl-10 w-64 focus:outline-none focus:border-primary transition"
                        >
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </form>
                    
                    <!-- Watch Later -->
                    <button onclick="toggleWatchLater()" class="relative hover:text-primary transition" title="Tonton Nanti">
                        <i class="fas fa-clock text-xl"></i>
                        <span id="watchlater-count" class="absolute -top-2 -right-2 bg-blue-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">0</span>
                    </button>
                    
                    <!-- Favorites -->
                    <button onclick="toggleFavorites()" class="relative hover:text-primary transition" title="Favorit">
                        <i class="fas fa-heart text-xl"></i>
                        <span id="favorites-count" class="absolute -top-2 -right-2 bg-primary text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">0</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div class="mobile-menu fixed top-0 left-0 w-64 h-full bg-dark z-40 md:hidden shadow-2xl" id="mobile-menu">
        <div class="p-4">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-xl font-bold text-primary">Menu</h2>
                <button onclick="toggleMobileMenu()" class="text-white hover:text-primary">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <!-- Mobile Search -->
            <form action="{{ route('movies.search') }}" method="GET" class="mb-6">
                <input 
                    type="text" 
                    name="q" 
                    placeholder="Cari film..." 
                    class="w-full bg-darker border border-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:border-primary"
                >
            </form>
            
            <!-- Mobile Navigation Links -->
            <nav class="space-y-4">
                <a href="{{ route('movies.index') }}" class="block py-2 hover:text-primary transition {{ request()->routeIs('movies.index') && !request()->has('category') ? 'text-primary' : 'text-gray-300' }}">
                    <i class="fas fa-fire mr-2"></i> Populer
                </a>
                <a href="{{ route('movies.index', ['category' => 'now_playing']) }}" class="block py-2 hover:text-primary transition {{ request()->get('category') === 'now_playing' ? 'text-primary' : 'text-gray-300' }}">
                    <i class="fas fa-play-circle mr-2"></i> Sedang Tayang
                </a>
                <a href="{{ route('movies.index', ['category' => 'top_rated']) }}" class="block py-2 hover:text-primary transition {{ request()->get('category') === 'top_rated' ? 'text-primary' : 'text-gray-300' }}">
                    <i class="fas fa-star mr-2"></i> Rating Tertinggi
                </a>
                <a href="{{ route('movies.index', ['category' => 'upcoming']) }}" class="block py-2 hover:text-primary transition {{ request()->get('category') === 'upcoming' ? 'text-primary' : 'text-gray-300' }}">
                    <i class="fas fa-calendar mr-2"></i> Akan Datang
                </a>
                <a href="{{ route('movies.trending') }}" class="block py-2 hover:text-primary transition {{ request()->routeIs('movies.trending') ? 'text-primary' : 'text-gray-300' }}">
                    <i class="fas fa-bolt mr-2"></i> Sedang Trending
                </a>
            </nav>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden" id="mobile-overlay" onclick="toggleMobileMenu()"></div>

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
                <h2 class="text-2xl font-bold"><i class="fas fa-heart text-primary mr-2"></i> Film Favorit Saya</h2>
                <button onclick="toggleFavorites()" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            <div id="favorites-content" class="p-6 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4"></div>
        </div>
    </div>

    <!-- Watch Later Modal -->
    <div id="watchlater-modal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4">
        <div class="bg-dark rounded-lg max-w-4xl w-full max-h-[80vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-800 flex justify-between items-center">
                <h2 class="text-2xl font-bold"><i class="fas fa-clock text-blue-500 mr-2"></i> Tonton Nanti</h2>
                <button onclick="toggleWatchLater()" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            <div id="watchlater-content" class="p-6 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4"></div>
        </div>
    </div>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed top-20 right-4 z-50 space-y-2"></div>

    <!-- Scroll to Top Button -->
    <button onclick="scrollToTop()" class="scroll-to-top fixed bottom-8 right-8 bg-primary hover:bg-red-700 text-white rounded-full p-4 shadow-lg z-40" id="scroll-top-btn">
        <i class="fas fa-arrow-up text-xl"></i>
    </button>

    <script>
        // Favorites Management
        let favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
        let watchLater = JSON.parse(localStorage.getItem('watchLater') || '[]');
        
        // Toast Notification System
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            const icons = {
                success: 'fa-check-circle',
                error: 'fa-exclamation-circle',
                info: 'fa-info-circle'
            };
            const colors = {
                success: 'bg-green-600',
                error: 'bg-red-600',
                info: 'bg-blue-600'
            };
            
            toast.className = `toast ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-3 min-w-[300px]`;
            toast.innerHTML = `
                <i class="fas ${icons[type]} text-xl"></i>
                <span>${message}</span>
            `;
            
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.add('hiding');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
        
        // Favorites Functions
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
                showToast('Dihapus dari favorit', 'info');
            } else {
                favorites.push({ id: movieId, title, poster });
                showToast('Ditambahkan ke favorit!', 'success');
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
                const icon = btn.querySelector('.fav-icon');
                if (icon && isFavorite(movieId)) {
                    icon.classList.remove('far');
                    icon.classList.add('fas', 'text-primary');
                } else if (icon) {
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
                            <a href="/movies/${f.id}">
                                <img src="https://image.tmdb.org/t/p/w200${f.poster}" alt="${f.title}" class="rounded-lg w-full hover:opacity-75 transition">
                            </a>
                            <button onclick="toggleFavorite(${f.id}, '${f.title.replace(/'/g, "\\'")}', '${f.poster}')" class="absolute top-2 right-2 bg-black bg-opacity-75 rounded-full p-2 opacity-0 group-hover:opacity-100 transition">
                                <i class="fas fa-times text-white"></i>
                            </button>
                        </div>
                    `).join('');
                modal.classList.remove('hidden');
            } else {
                modal.classList.add('hidden');
            }
        }
        
        // Watch Later Functions
        function updateWatchLaterCount() {
            const count = watchLater.length;
            const badge = document.getElementById('watchlater-count');
            if (count > 0) {
                badge.textContent = count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }
        
        function toggleWatchLater(movieId, title, poster) {
            if (!movieId) {
                // Open modal
                const modal = document.getElementById('watchlater-modal');
                const content = document.getElementById('watchlater-content');
                
                if (modal.classList.contains('hidden')) {
                    content.innerHTML = watchLater.length === 0 
                        ? '<p class="text-gray-400 col-span-full text-center py-8">Belum ada film untuk ditonton nanti</p>'
                        : watchLater.map(w => `
                            <div class="relative group">
                                <a href="/movies/${w.id}">
                                    <img src="https://image.tmdb.org/t/p/w200${w.poster}" alt="${w.title}" class="rounded-lg w-full hover:opacity-75 transition">
                                </a>
                                <button onclick="removeWatchLater(${w.id})" class="absolute top-2 right-2 bg-black bg-opacity-75 rounded-full p-2 opacity-0 group-hover:opacity-100 transition">
                                    <i class="fas fa-times text-white"></i>
                                </button>
                            </div>
                        `).join('');
                    modal.classList.remove('hidden');
                } else {
                    modal.classList.add('hidden');
                }
                return;
            }
            
            const index = watchLater.findIndex(w => w.id === movieId);
            if (index > -1) {
                watchLater.splice(index, 1);
                showToast('Dihapus dari tonton nanti', 'info');
            } else {
                watchLater.push({ id: movieId, title, poster });
                showToast('Ditambahkan ke tonton nanti!', 'success');
            }
            localStorage.setItem('watchLater', JSON.stringify(watchLater));
            updateWatchLaterCount();
            updateWatchLaterButtons();
        }
        
        function removeWatchLater(movieId) {
            const index = watchLater.findIndex(w => w.id === movieId);
            if (index > -1) {
                watchLater.splice(index, 1);
                localStorage.setItem('watchLater', JSON.stringify(watchLater));
                updateWatchLaterCount();
                updateWatchLaterButtons();
                toggleWatchLater(); // Refresh modal
                showToast('Dihapus dari tonton nanti', 'info');
            }
        }
        
        function isWatchLater(movieId) {
            return watchLater.some(w => w.id === movieId);
        }
        
        function updateWatchLaterButtons() {
            document.querySelectorAll('[data-watchlater-id]').forEach(btn => {
                const movieId = parseInt(btn.dataset.watchlaterId);
                const icon = btn.querySelector('.watch-icon');
                if (icon && isWatchLater(movieId)) {
                    icon.classList.add('text-blue-500');
                } else if (icon) {
                    icon.classList.remove('text-blue-500');
                }
            });
        }
        
        // Mobile Menu Toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const overlay = document.getElementById('mobile-overlay');
            menu.classList.toggle('open');
            overlay.classList.toggle('hidden');
        }
        
        // Share Movie
        function shareMovie(title, id) {
            const url = window.location.origin + '/movies/' + id;
            if (navigator.share) {
                navigator.share({
                    title: title,
                    text: `Lihat film ${title} di CinemaHub!`,
                    url: url
                }).then(() => {
                    showToast('Berhasil dibagikan!', 'success');
                }).catch(() => {});
            } else {
                navigator.clipboard.writeText(url);
                showToast('Link disalin ke clipboard!', 'success');
            }
        }
        
        // Scroll to Top
        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        
        // Navbar scroll effect & Scroll to top button
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            const scrollBtn = document.getElementById('scroll-top-btn');
            
            if (window.scrollY > 50) {
                navbar.classList.add('bg-dark');
                navbar.classList.remove('bg-gradient-to-b', 'from-dark', 'to-transparent');
            } else {
                navbar.classList.remove('bg-dark');
                navbar.classList.add('bg-gradient-to-b', 'from-dark', 'to-transparent');
            }
            
            if (window.scrollY > 300) {
                scrollBtn.classList.add('show');
            } else {
                scrollBtn.classList.remove('show');
            }
        });
        
        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            updateFavoritesCount();
            updateWatchLaterCount();
            updateFavoriteButtons();
            updateWatchLaterButtons();
        });
    </script>
    
    <!-- External JavaScript -->
    <script src="{{ asset('js/app.js') }}"></script>
    
    @stack('scripts')
</body>
</html>
