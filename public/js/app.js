// CinemaHub Application JavaScript
// Handles favorites, watch later, notifications, and UI interactions

class CinemaHub {
    constructor() {
        this.favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
        this.watchLater = JSON.parse(localStorage.getItem('watchLater') || '[]');
        this.init();
    }

    init() {
        this.updateFavoritesCount();
        this.updateWatchLaterCount();
        this.updateFavoriteButtons();
        this.updateWatchLaterButtons();
        this.initScrollEffects();
    }

    // Toast Notification System
    showToast(message, type = 'success') {
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

    // Favorites Management
    updateFavoritesCount() {
        const count = this.favorites.length;
        const badge = document.getElementById('favorites-count');
        if (badge) {
            if (count > 0) {
                badge.textContent = count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }
    }

    toggleFavorite(movieId, title, poster) {
        const index = this.favorites.findIndex(f => f.id === movieId);
        if (index > -1) {
            this.favorites.splice(index, 1);
            this.showToast('Dihapus dari favorit', 'info');
        } else {
            this.favorites.push({ id: movieId, title, poster });
            this.showToast('Ditambahkan ke favorit!', 'success');
        }
        localStorage.setItem('favorites', JSON.stringify(this.favorites));
        this.updateFavoritesCount();
        this.updateFavoriteButtons();
    }

    isFavorite(movieId) {
        return this.favorites.some(f => f.id === movieId);
    }

    updateFavoriteButtons() {
        document.querySelectorAll('[data-movie-id]').forEach(btn => {
            const movieId = parseInt(btn.dataset.movieId);
            const icon = btn.querySelector('.fav-icon');
            if (icon && this.isFavorite(movieId)) {
                icon.classList.remove('far');
                icon.classList.add('fas', 'text-primary');
            } else if (icon) {
                icon.classList.remove('fas', 'text-primary');
                icon.classList.add('far');
            }
        });
    }

    toggleFavorites() {
        const modal = document.getElementById('favorites-modal');
        const content = document.getElementById('favorites-content');
        
        if (modal.classList.contains('hidden')) {
            content.innerHTML = this.favorites.length === 0 
                ? '<p class="text-gray-400 col-span-full text-center py-8">Belum ada film favorit</p>'
                : this.favorites.map(f => `
                    <div class="relative group">
                        <a href="/movies/${f.id}">
                            <img src="https://image.tmdb.org/t/p/w200${f.poster}" alt="${f.title}" class="rounded-lg w-full hover:opacity-75 transition">
                        </a>
                        <button onclick="app.toggleFavorite(${f.id}, '${f.title.replace(/'/g, "\\'")}', '${f.poster}')" class="absolute top-2 right-2 bg-black bg-opacity-75 rounded-full p-2 opacity-0 group-hover:opacity-100 transition">
                            <i class="fas fa-times text-white"></i>
                        </button>
                    </div>
                `).join('');
            modal.classList.remove('hidden');
        } else {
            modal.classList.add('hidden');
        }
    }

    // Watch Later Management
    updateWatchLaterCount() {
        const count = this.watchLater.length;
        const badge = document.getElementById('watchlater-count');
        if (badge) {
            if (count > 0) {
                badge.textContent = count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }
    }

    toggleWatchLater(movieId, title, poster) {
        if (!movieId) {
            // Open modal
            const modal = document.getElementById('watchlater-modal');
            const content = document.getElementById('watchlater-content');
            
            if (modal.classList.contains('hidden')) {
                content.innerHTML = this.watchLater.length === 0 
                    ? '<p class="text-gray-400 col-span-full text-center py-8">Belum ada film untuk ditonton nanti</p>'
                    : this.watchLater.map(w => `
                        <div class="relative group">
                            <a href="/movies/${w.id}">
                                <img src="https://image.tmdb.org/t/p/w200${w.poster}" alt="${w.title}" class="rounded-lg w-full hover:opacity-75 transition">
                            </a>
                            <button onclick="app.removeWatchLater(${w.id})" class="absolute top-2 right-2 bg-black bg-opacity-75 rounded-full p-2 opacity-0 group-hover:opacity-100 transition">
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
        
        const index = this.watchLater.findIndex(w => w.id === movieId);
        if (index > -1) {
            this.watchLater.splice(index, 1);
            this.showToast('Dihapus dari tonton nanti', 'info');
        } else {
            this.watchLater.push({ id: movieId, title, poster });
            this.showToast('Ditambahkan ke tonton nanti!', 'success');
        }
        localStorage.setItem('watchLater', JSON.stringify(this.watchLater));
        this.updateWatchLaterCount();
        this.updateWatchLaterButtons();
    }

    removeWatchLater(movieId) {
        const index = this.watchLater.findIndex(w => w.id === movieId);
        if (index > -1) {
            this.watchLater.splice(index, 1);
            localStorage.setItem('watchLater', JSON.stringify(this.watchLater));
            this.updateWatchLaterCount();
            this.updateWatchLaterButtons();
            this.toggleWatchLater(); // Refresh modal
            this.showToast('Dihapus dari tonton nanti', 'info');
        }
    }

    isWatchLater(movieId) {
        return this.watchLater.some(w => w.id === movieId);
    }

    updateWatchLaterButtons() {
        document.querySelectorAll('[data-watchlater-id]').forEach(btn => {
            const movieId = parseInt(btn.dataset.watchlaterId);
            const icon = btn.querySelector('.watch-icon');
            if (icon && this.isWatchLater(movieId)) {
                icon.classList.add('text-blue-500');
            } else if (icon) {
                icon.classList.remove('text-blue-500');
            }
        });
    }

    // Mobile Menu
    toggleMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        const overlay = document.getElementById('mobile-overlay');
        menu.classList.toggle('open');
        overlay.classList.toggle('hidden');
    }

    // Share Movie
    shareMovie(title, id) {
        const url = window.location.origin + '/movies/' + id;
        if (navigator.share) {
            navigator.share({
                title: title,
                text: `Lihat film ${title} di CinemaHub!`,
                url: url
            }).then(() => {
                this.showToast('Berhasil dibagikan!', 'success');
            }).catch(() => {});
        } else {
            navigator.clipboard.writeText(url);
            this.showToast('Link disalin ke clipboard!', 'success');
        }
    }

    // Scroll to Top
    scrollToTop() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Initialize Scroll Effects
    initScrollEffects() {
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            const scrollBtn = document.getElementById('scroll-top-btn');
            
            if (window.scrollY > 50) {
                navbar?.classList.add('bg-dark');
                navbar?.classList.remove('bg-gradient-to-b', 'from-dark', 'to-transparent');
            } else {
                navbar?.classList.remove('bg-dark');
                navbar?.classList.add('bg-gradient-to-b', 'from-dark', 'to-transparent');
            }
            
            if (window.scrollY > 300) {
                scrollBtn?.classList.add('show');
            } else {
                scrollBtn?.classList.remove('show');
            }
        });
    }
}

// Initialize the app
const app = new CinemaHub();

// Global functions for backwards compatibility
function toggleFavorite(movieId, title, poster) {
    app.toggleFavorite(movieId, title, poster);
}

function toggleFavorites() {
    app.toggleFavorites();
}

function toggleWatchLater(movieId, title, poster) {
    app.toggleWatchLater(movieId, title, poster);
}

function toggleMobileMenu() {
    app.toggleMobileMenu();
}

function shareMovie(title, id) {
    app.shareMovie(title, id);
}

function scrollToTop() {
    app.scrollToTop();
}
