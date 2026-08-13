/**
 * Movies Page JavaScript
 * Handles movie browsing, filtering, and interactions
 */

document.addEventListener('DOMContentLoaded', function() {
    initializeMovieInteractions();
    initializeFilters();
    initializeLazyLoading();
});

/**
 * Initialize movie card interactions
 */
function initializeMovieInteractions() {
    // Add to watchlist
    document.querySelectorAll('.add-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const movieCard = this.closest('.movie-card');
            const movieId = movieCard.dataset.movieId;
            toggleWatchlist(movieId, this);
        });
    });

    // Toggle favorite
    document.querySelectorAll('.like-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const movieCard = this.closest('.movie-card');
            const movieId = movieCard.dataset.movieId;
            toggleFavorite(movieId, this);
        });
    });

    // Play button
    document.querySelectorAll('.play-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const movieCard = this.closest('.movie-card');
            const movieId = movieCard.dataset.movieId;
            window.location.href = `/movie/${movieId}/watch`;
        });
    });

    // Info button
    document.querySelectorAll('.info-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const movieCard = this.closest('.movie-card');
            const movieId = movieCard.dataset.movieId;
            window.location.href = `/movie/${movieId}`;
        });
    });

    // Movie card click
    document.querySelectorAll('.movie-card').forEach(card => {
        card.addEventListener('click', function() {
            const movieId = this.dataset.movieId;
            window.location.href = `/movie/${movieId}`;
        });
    });
}

/**
 * Toggle watchlist
 */
function toggleWatchlist(movieId, button) {
    fetch('/api/watchlist/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken()
        },
        body: JSON.stringify({ movie_id: movieId })
    })
    .then(response => response.json())
    .then(data => {
        const icon = button.querySelector('i');
        if (data.in_watchlist) {
            icon.classList.remove('fa-plus');
            icon.classList.add('fa-check');
            button.style.background = 'var(--success-color)';
        } else {
            icon.classList.remove('fa-check');
            icon.classList.add('fa-plus');
            button.style.background = 'rgba(255, 255, 255, 0.2)';
        }
        showToast(data.message, 'success');
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Please login to add to watchlist', 'error');
    });
}

/**
 * Toggle favorite
 */
function toggleFavorite(movieId, button) {
    fetch('/api/favorites/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken()
        },
        body: JSON.stringify({ movie_id: movieId })
    })
    .then(response => response.json())
    .then(data => {
        const icon = button.querySelector('i');
        if (data.favorited) {
            icon.style.color = '#e50914';
        } else {
            icon.style.color = 'inherit';
        }
        showToast(data.message, 'success');
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Please login to add to favorites', 'error');
    });
}

/**
 * Initialize filters
 */
function initializeFilters() {
    const filterForm = document.querySelector('.filters-form');
    if (!filterForm) return;

    // Save filter state to localStorage
    const filters = filterForm.querySelectorAll('select');
    filters.forEach(filter => {
        const savedValue = localStorage.getItem('filter_' + filter.name);
        if (savedValue) {
            filter.value = savedValue;
        }

        filter.addEventListener('change', function() {
            localStorage.setItem('filter_' + this.name, this.value);
        });
    });

    // Clear filters
    const clearBtn = document.querySelector('.filters-form .btn-secondary');
    if (clearBtn) {
        clearBtn.addEventListener('click', function(e) {
            e.preventDefault();
            filters.forEach(filter => {
                localStorage.removeItem('filter_' + filter.name);
            });
            window.location.href = filterForm.action;
        });
    }
}

/**
 * Initialize lazy loading for images
 */
function initializeLazyLoading() {
    const images = document.querySelectorAll('img[loading="lazy"]');
    
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.src; // Trigger load
                    img.classList.add('loaded');
                    observer.unobserve(img);
                }
            });
        });

        images.forEach(img => imageObserver.observe(img));
    }
}

/**
 * Show toast notification
 */
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    
    const colors = {
        success: '#46d369',
        error: '#e50914',
        info: '#0084ff'
    };
    
    toast.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: ${colors[type] || colors.info};
        color: #fff;
        padding: 15px 25px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        z-index: 10000;
        animation: slideIn 0.3s ease;
        font-weight: 600;
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

/**
 * Get CSRF token
 */
function getCsrfToken() {
    const token = document.querySelector('meta[name="csrf-token"]');
    return token ? token.content : '';
}

/**
 * Search functionality
 */
const searchInput = document.querySelector('.search-input');
if (searchInput) {
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length >= 3) {
            searchTimeout = setTimeout(() => {
                searchMovies(query);
            }, 500);
        }
    });
}

/**
 * Search movies (autocomplete)
 */
function searchMovies(query) {
    fetch(`/api/movies/search?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            displaySearchResults(data);
        })
        .catch(error => {
            console.error('Search error:', error);
        });
}

/**
 * Display search results
 */
function displaySearchResults(results) {
    // Implement autocomplete dropdown here
    console.log('Search results:', results);
}

/**
 * Movie row slider controls
 */
document.querySelectorAll('.slider-control').forEach(control => {
    control.addEventListener('click', function() {
        const slider = this.dataset.slider;
        const row = document.getElementById(slider);
        if (!row) return;
        
        const scrollAmount = 400;
        if (this.classList.contains('next')) {
            row.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        } else {
            row.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        }
    });
});

/**
 * Infinite scroll (optional)
 */
let loadingMore = false;
const observeInfiniteScroll = () => {
    const sentinel = document.querySelector('.pagination-wrapper');
    if (!sentinel) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !loadingMore) {
                const nextPageLink = document.querySelector('.pagination a[rel="next"]');
                if (nextPageLink) {
                    loadMoreMovies(nextPageLink.href);
                }
            }
        });
    });

    observer.observe(sentinel);
};

/**
 * Load more movies
 */
function loadMoreMovies(url) {
    loadingMore = true;
    
    fetch(url)
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newMovies = doc.querySelectorAll('.movie-card');
            const moviesGrid = document.querySelector('.movies-grid');
            
            newMovies.forEach(movie => {
                moviesGrid.appendChild(movie);
            });
            
            loadingMore = false;
            initializeMovieInteractions();
        })
        .catch(error => {
            console.error('Load more error:', error);
            loadingMore = false;
        });
}

/**
 * Keyboard shortcuts
 */
document.addEventListener('keydown', (e) => {
    // Escape key to close modals
    if (e.key === 'Escape') {
        closeAllModals();
    }
    
    // Ctrl/Cmd + K for search
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        const searchInput = document.querySelector('.search-input');
        if (searchInput) {
            searchInput.focus();
        }
    }
});

/**
 * Close all modals
 */
function closeAllModals() {
    document.querySelectorAll('.modal, .dropdown-menu').forEach(modal => {
        modal.classList.remove('active');
    });
}

/**
 * Add CSS animations
 */
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    img.loaded {
        animation: fadeIn 0.5s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
`;
document.head.appendChild(style);

// Log initialization
console.log('Movies.js loaded successfully! 🎬');
