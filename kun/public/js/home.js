// Sample movie data - Replace with actual API calls
const sampleMovies = [
    {
        id: 1,
        title: 'The Dark Knight',
        poster: 'https://images.unsplash.com/photo-1509347528160-9a9e33742cdb?w=300&h=450&fit=crop',
        rating: 9.0,
        year: 2008,
        duration: '2h 32m',
        age_rating: 'PG-13',
        quality: '4K',
        genres: ['Action', 'Crime', 'Drama']
    },
    {
        id: 2,
        title: 'Inception',
        poster: 'https://images.unsplash.com/photo-1440404653325-ab127d49abc1?w=300&h=450&fit=crop',
        rating: 8.8,
        year: 2010,
        duration: '2h 28m',
        age_rating: 'PG-13',
        quality: '4K',
        genres: ['Action', 'Sci-Fi', 'Thriller']
    },
    {
        id: 3,
        title: 'Interstellar',
        poster: 'https://images.unsplash.com/photo-1419242902214-272b3f66ee7a?w=300&h=450&fit=crop',
        rating: 8.6,
        year: 2014,
        duration: '2h 49m',
        age_rating: 'PG-13',
        quality: '4K',
        genres: ['Adventure', 'Drama', 'Sci-Fi']
    },
    {
        id: 4,
        title: 'The Matrix',
        poster: 'https://images.unsplash.com/photo-1518676590629-3dcbd9c5a5c9?w=300&h=450&fit=crop',
        rating: 8.7,
        year: 1999,
        duration: '2h 16m',
        age_rating: 'R',
        quality: 'HD',
        genres: ['Action', 'Sci-Fi']
    },
    {
        id: 5,
        title: 'Pulp Fiction',
        poster: 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?w=300&h=450&fit=crop',
        rating: 8.9,
        year: 1994,
        duration: '2h 34m',
        age_rating: 'R',
        quality: 'HD',
        genres: ['Crime', 'Drama']
    },
    {
        id: 6,
        title: 'The Shawshank Redemption',
        poster: 'https://images.unsplash.com/photo-1517604931442-7e0c8ed2963c?w=300&h=450&fit=crop',
        rating: 9.3,
        year: 1994,
        duration: '2h 22m',
        age_rating: 'R',
        quality: 'HD',
        genres: ['Drama']
    },
    {
        id: 7,
        title: 'Fight Club',
        poster: 'https://images.unsplash.com/photo-1594908900066-3f47337549d8?w=300&h=450&fit=crop',
        rating: 8.8,
        year: 1999,
        duration: '2h 19m',
        age_rating: 'R',
        quality: 'HD',
        genres: ['Drama']
    },
    {
        id: 8,
        title: 'Forrest Gump',
        poster: 'https://images.unsplash.com/photo-1485846234645-a62644f84728?w=300&h=450&fit=crop',
        rating: 8.8,
        year: 1994,
        duration: '2h 22m',
        age_rating: 'PG-13',
        quality: 'HD',
        genres: ['Drama', 'Romance']
    },
    {
        id: 9,
        title: 'The Godfather',
        poster: 'https://images.unsplash.com/photo-1478720568477-152d9b164e26?w=300&h=450&fit=crop',
        rating: 9.2,
        year: 1972,
        duration: '2h 55m',
        age_rating: 'R',
        quality: 'HD',
        genres: ['Crime', 'Drama']
    },
    {
        id: 10,
        title: 'The Lord of the Rings',
        poster: 'https://images.unsplash.com/photo-1536440136628-849c177e76a1?w=300&h=450&fit=crop',
        rating: 8.9,
        year: 2001,
        duration: '2h 58m',
        age_rating: 'PG-13',
        quality: '4K',
        genres: ['Adventure', 'Drama', 'Fantasy']
    }
];

// Generate movie card HTML
function createMovieCard(movie) {
    return `
        <div class="movie-card" data-movie-id="${movie.id}">
            <div class="movie-card-inner">
                <div class="movie-poster">
                    <img src="${movie.poster}" alt="${movie.title}" loading="lazy">
                    <div class="movie-overlay">
                        <div class="movie-actions">
                            <button class="action-btn play-btn" title="Play">
                                <i class="fas fa-play"></i>
                            </button>
                            <button class="action-btn add-btn" title="Add to My List">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button class="action-btn like-btn" title="Like">
                                <i class="fas fa-thumbs-up"></i>
                            </button>
                            <button class="action-btn info-btn" title="More Info">
                                <i class="fas fa-info-circle"></i>
                            </button>
                        </div>
                    </div>
                    ${movie.quality ? `<div class="quality-badge">${movie.quality}</div>` : ''}
                    ${movie.rating ? `
                        <div class="rating-badge">
                            <i class="fas fa-star"></i>
                            <span>${movie.rating}</span>
                        </div>
                    ` : ''}
                </div>
                <div class="movie-info">
                    <h3 class="movie-title">${movie.title}</h3>
                    <div class="movie-meta">
                        ${movie.year ? `<span class="meta-item">${movie.year}</span>` : ''}
                        ${movie.duration ? `<span class="meta-item">${movie.duration}</span>` : ''}
                        ${movie.age_rating ? `<span class="meta-item age-rating">${movie.age_rating}</span>` : ''}
                    </div>
                    ${movie.genres ? `
                        <div class="movie-genres">
                            ${movie.genres.slice(0, 3).map(genre => `<span class="genre-tag">${genre}</span>`).join('')}
                        </div>
                    ` : ''}
                </div>
            </div>
        </div>
    `;
}

// Shuffle array
function shuffleArray(array) {
    const newArray = [...array];
    for (let i = newArray.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [newArray[i], newArray[j]] = [newArray[j], newArray[i]];
    }
    return newArray;
}

// Load movies into a section
function loadMovies(containerId, movies) {
    const container = document.getElementById(containerId);
    if (!container) return;
    
    const moviesHTML = movies.map(movie => createMovieCard(movie)).join('');
    container.innerHTML = moviesHTML;
    
    // Initialize movie card interactions
    initMovieCardInteractions(container);
}

// Initialize movie card interactions
function initMovieCardInteractions(container) {
    // Add to list
    container.querySelectorAll('.add-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const icon = this.querySelector('i');
            if (icon.classList.contains('fa-plus')) {
                icon.classList.remove('fa-plus');
                icon.classList.add('fa-check');
                this.style.background = 'var(--success-color)';
                showToast('Added to My List', 'success');
            } else {
                icon.classList.remove('fa-check');
                icon.classList.add('fa-plus');
                this.style.background = 'rgba(255, 255, 255, 0.2)';
                showToast('Removed from My List', 'info');
            }
        });
    });
    
    // Like/Dislike
    container.querySelectorAll('.like-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const icon = this.querySelector('i');
            if (icon.classList.contains('fa-thumbs-up')) {
                icon.classList.remove('fa-thumbs-up');
                icon.classList.add('fa-thumbs-down');
                showToast('Disliked', 'info');
            } else {
                icon.classList.remove('fa-thumbs-down');
                icon.classList.add('fa-thumbs-up');
                showToast('Liked', 'success');
            }
        });
    });
    
    // Play
    container.querySelectorAll('.play-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const movieId = this.closest('.movie-card').dataset.movieId;
            window.location.href = `/movie/${movieId}/watch`;
        });
    });
    
    // Info
    container.querySelectorAll('.info-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const movieId = this.closest('.movie-card').dataset.movieId;
            window.location.href = `/movie/${movieId}`;
        });
    });
    
    // Card click
    container.querySelectorAll('.movie-card').forEach(card => {
        card.addEventListener('click', function() {
            const movieId = this.dataset.movieId;
            window.location.href = `/movie/${movieId}`;
        });
    });
}

// Toast notification
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    toast.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: ${type === 'success' ? 'var(--success-color)' : 'var(--primary-color)'};
        color: white;
        padding: 15px 25px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        z-index: 10000;
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Hero slider
let currentSlide = 0;
const slides = document.querySelectorAll('.hero-slide');
const indicators = document.querySelectorAll('.indicator');

function showSlide(index) {
    slides.forEach(slide => slide.classList.remove('active'));
    indicators.forEach(indicator => indicator.classList.remove('active'));
    
    currentSlide = index;
    if (currentSlide >= slides.length) currentSlide = 0;
    if (currentSlide < 0) currentSlide = slides.length - 1;
    
    slides[currentSlide].classList.add('active');
    indicators[currentSlide].classList.add('active');
}

function nextSlide() {
    showSlide(currentSlide + 1);
}

function prevSlide() {
    showSlide(currentSlide - 1);
}

// Auto play hero slider
let heroInterval = setInterval(nextSlide, 5000);

// Hero controls
document.getElementById('heroNext')?.addEventListener('click', () => {
    clearInterval(heroInterval);
    nextSlide();
    heroInterval = setInterval(nextSlide, 5000);
});

document.getElementById('heroPrev')?.addEventListener('click', () => {
    clearInterval(heroInterval);
    prevSlide();
    heroInterval = setInterval(nextSlide, 5000);
});

// Indicators
indicators.forEach((indicator, index) => {
    indicator.addEventListener('click', () => {
        clearInterval(heroInterval);
        showSlide(index);
        heroInterval = setInterval(nextSlide, 5000);
    });
});

// Movie row slider controls
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

// Keyboard navigation for sliders
document.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowLeft') {
        document.querySelector('.hero-control.prev')?.click();
    } else if (e.key === 'ArrowRight') {
        document.querySelector('.hero-control.next')?.click();
    }
});

// Lazy loading images with Intersection Observer
const observeImages = () => {
    const images = document.querySelectorAll('img[loading="lazy"]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.src;
                img.classList.add('loaded');
                observer.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
};

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Load movies for each section
    loadMovies('continueWatching', shuffleArray(sampleMovies).slice(0, 8));
    loadMovies('trending', shuffleArray(sampleMovies));
    loadMovies('newReleases', shuffleArray(sampleMovies));
    loadMovies('popular', shuffleArray(sampleMovies));
    loadMovies('action', shuffleArray(sampleMovies));
    loadMovies('comedy', shuffleArray(sampleMovies));
    loadMovies('horror', shuffleArray(sampleMovies));
    
    // Initialize lazy loading
    observeImages();
    
    // Add CSS animations
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
    
    console.log('Kun Movie Platform - Homepage Loaded Successfully! 🎬');
});

// Handle window resize
let resizeTimer;
window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
        // Reinitialize if needed
        console.log('Window resized');
    }, 250);
});

// Smooth scroll for all anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
