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
    
    // Live Search Logic
    let searchTimeout;
    function handleSearchInput(query) {
        clearTimeout(searchTimeout);
        const resultsContainer = document.getElementById('search-results-container');
        const defaultLinks = document.getElementById('default-search-links');
        
        if (!query || query.length < 2) {
            resultsContainer.classList.add('hidden');
            resultsContainer.innerHTML = '';
            defaultLinks.classList.remove('hidden');
            return;
        }

        searchTimeout = setTimeout(() => {
            fetch(`{{ route('movies.search.json') }}?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    resultsContainer.innerHTML = '';
                    
                    if (data.results.length > 0) {
                        resultsContainer.classList.remove('hidden');
                        defaultLinks.classList.add('hidden');
                        
                        data.results.forEach(movie => {
                            const releaseYear = movie.release_date ? new Date(movie.release_date).getFullYear() : 'N/A';
                            const poster = movie.poster_path 
                                ? `https://image.tmdb.org/t/p/w92${movie.poster_path}` 
                                : 'https://via.placeholder.com/92x138/000000/FFFFFF/?text=No+Image';
                                
                            const html = `
                                <a href="/movies/${movie.id}" class="flex items-center gap-4 p-3 rounded-xl hover:bg-white/5 transition group">
                                    <img src="${poster}" class="w-12 h-16 object-cover rounded-md shadow-md" alt="${movie.title}">
                                    <div>
                                        <h4 class="text-white font-bold group-hover:text-primary transition-colors">${movie.title}</h4>
                                        <div class="flex items-center gap-2 text-xs text-gray-400 mt-1">
                                            <span class="bg-white/10 px-1.5 py-0.5 rounded">${releaseYear}</span>
                                            <span><i class="fas fa-star text-yellow-500 text-[10px]"></i> ${movie.vote_average ? movie.vote_average.toFixed(1) : 'N/A'}</span>
                                        </div>
                                    </div>
                                    <i class="fas fa-chevron-right ml-auto text-gray-600 group-hover:text-white transition-transform group-hover:translate-x-1"></i>
                                </a>
                            `;
                            resultsContainer.insertAdjacentHTML('beforeend', html);
                        });
                        
                        // 'View all' link
                        const viewAllHtml = `
                            <button type="submit" class="w-full text-center py-3 text-sm text-gray-400 hover:text-white transition-colors border-t border-white/5 mt-2">
                                View all results for "${query}"
                            </button>
                        `;
                        resultsContainer.insertAdjacentHTML('beforeend', viewAllHtml);
                        
                    } else {
                            resultsContainer.classList.remove('hidden');
                            defaultLinks.classList.add('hidden');
                            resultsContainer.innerHTML = `
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-film text-3xl mb-3 opacity-50"></i>
                                <p>No movies found for "${query}"</p>
                            </div>
                            `;
                    }
                })
                .catch(err => console.error(err));
        }, 300); // Debounce 300ms
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
        
        const langDropdown = document.getElementById('lang-dropdown');
        if (langDropdown && !langDropdown.contains(e.target)) {
            const menu = document.getElementById('lang-dropdown-menu');
            if (menu) menu.classList.add('hidden');
        }
    });
    
    function toggleLangDropdown() {
        const menu = document.getElementById('lang-dropdown-menu');
        if (menu) menu.classList.toggle('hidden');
    }

    // Page Transition Loader
    window.addEventListener('beforeunload', () => {
        const loader = document.getElementById('loading-overlay');
        if (loader) {
            loader.classList.remove('opacity-0', 'pointer-events-none');
        }
    });

    // Hide loader on load (in case of back button or initial load)
    window.addEventListener('pageshow', () => {
        const loader = document.getElementById('loading-overlay');
        if (loader) {
            loader.classList.add('opacity-0', 'pointer-events-none');
        }
    });
</script>
