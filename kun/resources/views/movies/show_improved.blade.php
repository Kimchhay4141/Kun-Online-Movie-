@extends('layouts.app')

@section('title', $movie->title . ' - Kun Online Movie')

@section('content')
<div class="kun-movie-detail">
    {{-- Hero Banner Section --}}
    <section class="kun-hero" style="background-image: linear-gradient(to right, rgba(0,0,0,0.95) 30%, rgba(0,0,0,0.7) 50%, rgba(0,0,0,0.3) 100%), url('{{ $movie->banner ?? $movie->thumbnail ?? asset('images/placeholder.jpg') }}');">
        <div class="kun-hero-content">
            <div class="kun-poster">
                @if($movie->thumbnail)
                <img src="{{ $movie->thumbnail }}" alt="{{ $movie->title }}" loading="lazy">
                @else
                <div class="poster-placeholder">
                    <i class="fas fa-film"></i>
                    <p>No Poster</p>
                </div>
                @endif
                @if($movie->content_rating)
                <span class="content-rating">{{ $movie->content_rating }}</span>
                @endif
            </div>
            
            <div class="kun-info">
                <h1 class="kun-title">{{ $movie->title }}</h1>
                
                <div class="kun-meta">
                    @if($movie->rating)
                    <div class="rating">
                        <i class="fas fa-star"></i>
                        <span>{{ number_format($movie->rating, 1) }}</span>
                    </div>
                    @endif
                    
                    @if($movie->release_year)
                    <span class="year">{{ $movie->release_year }}</span>
                    @endif
                    
                    @if($movie->duration)
                    <span class="duration">
                        <i class="far fa-clock"></i>
                        {{ $movie->duration }} min
                    </span>
                    @endif
                    
                    <span class="views">
                        <i class="far fa-eye"></i>
                        {{ number_format($movie->view_count ?? 0) }} views
                    </span>
                </div>
                
                @if($movie->genres && $movie->genres->count() > 0)
                <div class="kun-genres">
                    @foreach($movie->genres as $genre)
                    <a href="{{ route('movies.genre', $genre->slug) }}" class="genre-tag">
                        {{ $genre->name }}
                    </a>
                    @endforeach
                </div>
                @endif
                
                <p class="kun-description">
                    {{ $movie->description ?? 'No description available for this movie.' }}
                </p>
                
                <div class="kun-actions">
                    @if($movie->status === 'published' && $movie->videos && $movie->videos->count() > 0)
                        @auth
                        <a href="{{ route('movie.watch', $movie->id) }}" class="btn-primary">
                            <i class="fas fa-play"></i>
                            <span>Watch Now</span>
                        </a>
                        @else
                        <a href="{{ route('login') }}" class="btn-primary">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Sign In to Watch</span>
                        </a>
                        @endauth
                    @else
                        <button class="btn-disabled" disabled>
                            @if($movie->status === 'coming_soon')
                                <i class="far fa-calendar"></i>
                                <span>Coming Soon</span>
                            @else
                                <i class="fas fa-exclamation-circle"></i>
                                <span>Not Available</span>
                            @endif
                        </button>
                    @endif
                    
                    @auth
                    <button class="btn-secondary" onclick="toggleWatchlist({{ $movie->id }})" id="watchlistBtn">
                        <i class="fas fa-{{ $isInWatchlist ?? false ? 'check' : 'plus' }}"></i>
                        <span>{{ $isInWatchlist ?? false ? 'In List' : 'My List' }}</span>
                    </button>
                    
                    <button class="btn-icon" onclick="toggleFavorite({{ $movie->id }})" id="favoriteBtn">
                        <i class="fas fa-heart" style="color: {{ $isFavorited ?? false ? '#e50914' : '#fff' }}"></i>
                    </button>
                    @endauth
                    
                    <button class="btn-icon" onclick="shareMovie()">
                        <i class="fas fa-share-alt"></i>
                    </button>
                </div>
                
                @if($userProgress ?? false)
                <div class="kun-progress">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $userProgress }}%"></div>
                    </div>
                    <span class="progress-text">{{ number_format($userProgress) }}% watched</span>
                </div>
                @endif
                
                <div class="kun-details">
                    @if($movie->director)
                    <div class="detail-row">
                        <strong>Director:</strong>
                        <span>{{ $movie->director }}</span>
                    </div>
                    @endif
                    
                    @if($movie->cast)
                    <div class="detail-row">
                        <strong>Cast:</strong>
                        <span>{{ $movie->cast }}</span>
                    </div>
                    @endif
                    
                    @if($movie->language)
                    <div class="detail-row">
                        <strong>Language:</strong>
                        <span>{{ $movie->language }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    
    {{-- Trailers & Videos Section --}}
    @if($movie->videos && $movie->videos->where('video_type', 'trailer')->count() > 0)
    <section class="kun-section">
        <div class="kun-container">
            <h2 class="section-title">
                <i class="fas fa-video"></i>
                Trailers & More
            </h2>
            <div class="trailers-grid">
                @foreach($movie->videos->where('video_type', 'trailer') as $video)
                <div class="trailer-card" onclick="playTrailer('{{ $video->video_url }}')">
                    <div class="trailer-thumb">
                        <img src="{{ $movie->thumbnail ?? asset('images/placeholder.jpg') }}" alt="{{ $video->title }}">
                        <div class="play-button">
                            <i class="fas fa-play"></i>
                        </div>
                    </div>
                    <h4>{{ $video->title ?? 'Trailer' }}</h4>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    
    {{-- Related Movies Section --}}
    @if($relatedMovies ?? false && $relatedMovies->count() > 0)
    <section class="kun-section">
        <div class="kun-container">
            <h2 class="section-title">
                <i class="fas fa-film"></i>
                More Like This
            </h2>
            <div class="movies-grid">
                @foreach($relatedMovies as $related)
                <a href="{{ route('movie.show', $related->id) }}" class="movie-card">
                    <div class="movie-poster">
                        @if($related->thumbnail)
                        <img src="{{ $related->thumbnail }}" alt="{{ $related->title }}" loading="lazy">
                        @else
                        <div class="poster-placeholder">
                            <i class="fas fa-film"></i>
                        </div>
                        @endif
                        <div class="movie-overlay">
                            <div class="overlay-content">
                                <h4>{{ Str::limit($related->title, 30) }}</h4>
                                <div class="overlay-meta">
                                    @if($related->rating)
                                    <span><i class="fas fa-star"></i> {{ number_format($related->rating, 1) }}</span>
                                    @endif
                                    @if($related->release_year)
                                    <span>{{ $related->release_year }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <h3 class="movie-title">{{ $related->title }}</h3>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</div>

{{-- Trailer Modal --}}
<div id="trailerModal" class="modal" onclick="closeTrailer()">
    <div class="modal-content" onclick="event.stopPropagation()">
        <button class="modal-close" onclick="closeTrailer()">&times;</button>
        <video id="trailerVideo" controls>
            <source src="" type="video/mp4">
        </video>
    </div>
</div>

<style>
/* Modern Netflix-style Design */
:root {
    --primary: #e50914;
    --dark: #141414;
    --light: #ffffff;
    --gray: #808080;
}

.kun-movie-detail {
    background: var(--dark);
    color: var(--light);
    min-height: 100vh;
}

/* Hero Section */
.kun-hero {
    position: relative;
    min-height: 85vh;
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: center;
    padding: 120px 0 60px;
}

.kun-hero-content {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 40px;
    display: grid;
    grid-template-columns: 350px 1fr;
    gap: 40px;
    width: 100%;
}

.kun-poster {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0,0,0,0.6);
    aspect-ratio: 2/3;
}

.kun-poster img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.poster-placeholder {
    width: 100%;
    height: 100%;
    background: #2a2a2a;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #666;
}

.poster-placeholder i {
    font-size: 64px;
    margin-bottom: 12px;
}

.content-rating {
    position: absolute;
    top: 16px;
    right: 16px;
    background: rgba(0,0,0,0.85);
    color: #fff;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 700;
    border: 2px solid rgba(255,255,255,0.3);
    backdrop-filter: blur(10px);
}

.kun-info {
    display: flex;
    flex-direction: column;
    gap: 20px;
    padding-top: 20px;
}

.kun-title {
    font-size: 52px;
    font-weight: 700;
    line-height: 1.1;
    margin: 0;
    text-shadow: 2px 2px 8px rgba(0,0,0,0.8);
}

.kun-meta {
    display: flex;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
    font-size: 16px;
}

.rating {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #ffd700;
    font-size: 20px;
    font-weight: 700;
}

.kun-meta span {
    display: flex;
    align-items: center;
    gap: 6px;
}

.kun-genres {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.genre-tag {
    background: rgba(255,255,255,0.1);
    color: #fff;
    padding: 8px 16px;
    border-radius: 20px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    border: 1px solid rgba(255,255,255,0.2);
    transition: all 0.3s ease;
}

.genre-tag:hover {
    background: rgba(255,255,255,0.2);
    border-color: var(--primary);
    transform: translateY(-2px);
}

.kun-description {
    font-size: 18px;
    line-height: 1.7;
    color: rgba(255,255,255,0.9);
    max-width: 800px;
}

.kun-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.btn-primary,
.btn-secondary,
.btn-disabled {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 28px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 700;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary {
    background: var(--primary);
    color: #fff;
}

.btn-primary:hover {
    background: #f40612;
    transform: scale(1.05);
}

.btn-secondary {
    background: rgba(109,109,110,0.7);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.3);
}

.btn-secondary:hover {
    background: rgba(109,109,110,0.9);
    border-color: rgba(255,255,255,0.5);
}

.btn-disabled {
    background: rgba(109,109,110,0.4);
    color: rgba(255,255,255,0.6);
    cursor: not-allowed;
}

.btn-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(109,109,110,0.7);
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 50%;
    color: #fff;
    font-size: 18px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-icon:hover {
    background: rgba(109,109,110,0.9);
    transform: scale(1.1);
}

.kun-progress {
    margin-top: 10px;
}

.progress-bar {
    width: 100%;
    max-width: 600px;
    height: 6px;
    background: rgba(255,255,255,0.2);
    border-radius: 3px;
    overflow: hidden;
    margin-bottom: 8px;
}

.progress-fill {
    height: 100%;
    background: var(--primary);
    border-radius: 3px;
    transition: width 0.3s ease;
}

.progress-text {
    font-size: 14px;
    color: rgba(255,255,255,0.7);
}

.kun-details {
    display: flex;
    flex-direction: column;
    gap: 12px;
    max-width: 600px;
}

.detail-row {
    display: flex;
    gap: 12px;
    font-size: 16px;
}

.detail-row strong {
    color: rgba(255,255,255,0.6);
    min-width: 100px;
}

.detail-row span {
    color: rgba(255,255,255,0.9);
}

/* Sections */
.kun-section {
    padding: 60px 0;
    background: var(--dark);
}

.kun-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 40px;
}

.section-title {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.section-title i {
    color: var(--primary);
}

/* Trailers Grid */
.trailers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.trailer-card {
    cursor: pointer;
    transition: transform 0.3s ease;
}

.trailer-card:hover {
    transform: translateY(-8px);
}

.trailer-thumb {
    position: relative;
    aspect-ratio: 16/9;
    border-radius: 8px;
    overflow: hidden;
    background: #2a2a2a;
    margin-bottom: 12px;
}

.trailer-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.play-button {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 64px;
    height: 64px;
    background: rgba(229,9,20,0.9);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: #fff;
    transition: all 0.3s ease;
}

.trailer-card:hover .play-button {
    transform: translate(-50%, -50%) scale(1.2);
}

.trailer-card h4 {
    font-size: 16px;
    font-weight: 600;
    margin: 0;
}

/* Movies Grid */
.movies-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
}

.movie-card {
    text-decoration: none;
    color: #fff;
    transition: transform 0.3s ease;
}

.movie-card:hover {
    transform: scale(1.05);
    z-index: 10;
}

.movie-poster {
    position: relative;
    aspect-ratio: 2/3;
    border-radius: 8px;
    overflow: hidden;
    background: #2a2a2a;
    margin-bottom: 10px;
}

.movie-poster img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.movie-card:hover .movie-poster img {
    transform: scale(1.1);
}

.movie-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to top, rgba(0,0,0,0.95) 0%, transparent 50%);
    opacity: 0;
    transition: opacity 0.3s ease;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 16px;
}

.movie-card:hover .movie-overlay {
    opacity: 1;
}

.overlay-content h4 {
    font-size: 16px;
    margin-bottom: 8px;
    font-weight: 600;
}

.overlay-meta {
    display: flex;
    gap: 12px;
    font-size: 14px;
}

.overlay-meta i {
    color: #ffd700;
}

.movie-title {
    font-size: 16px;
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin: 0;
}

/* Trailer Modal */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.9);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 40px;
}

.modal.active {
    display: flex;
}

.modal-content {
    position: relative;
    width: 100%;
    max-width: 1200px;
    aspect-ratio: 16/9;
}

.modal-close {
    position: absolute;
    top: -40px;
    right: 0;
    background: transparent;
    border: none;
    color: #fff;
    font-size: 40px;
    cursor: pointer;
    padding: 0;
    line-height: 1;
}

.modal-content video {
    width: 100%;
    height: 100%;
    border-radius: 8px;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .kun-hero-content {
        grid-template-columns: 280px 1fr;
        gap: 30px;
    }
    
    .kun-title {
        font-size: 42px;
    }
}

@media (max-width: 768px) {
    .kun-hero-content {
        grid-template-columns: 1fr;
        text-align: center;
    }
    
    .kun-poster {
        max-width: 300px;
        margin: 0 auto;
    }
    
    .kun-title {
        font-size: 32px;
    }
    
    .kun-meta,
    .kun-genres,
    .kun-actions {
        justify-content: center;
    }
    
    .kun-description {
        text-align: left;
    }
    
    .kun-details {
        margin: 0 auto;
    }
    
    .movies-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
}

@media (max-width: 480px) {
    .kun-container {
        padding: 0 20px;
    }
    
    .kun-title {
        font-size: 24px;
    }
    
    .btn-primary,
    .btn-secondary {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
function playTrailer(url) {
    const modal = document.getElementById('trailerModal');
    const video = document.getElementById('trailerVideo');
    video.querySelector('source').src = url;
    video.load();
    modal.classList.add('active');
    video.play();
}

function closeTrailer() {
    const modal = document.getElementById('trailerModal');
    const video = document.getElementById('trailerVideo');
    video.pause();
    modal.classList.remove('active');
}

function toggleWatchlist(movieId) {
    fetch('/api/watchlist/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ movie_id: movieId })
    })
    .then(response => response.json())
    .then(data => {
        const btn = document.getElementById('watchlistBtn');
        const icon = btn.querySelector('i');
        const span = btn.querySelector('span');
        
        if (data.in_watchlist) {
            icon.className = 'fas fa-check';
            span.textContent = 'In List';
        } else {
            icon.className = 'fas fa-plus';
            span.textContent = 'My List';
        }
    })
    .catch(error => console.error('Error:', error));
}

function toggleFavorite(movieId) {
    fetch('/api/favorites/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ movie_id: movieId })
    })
    .then(response => response.json())
    .then(data => {
        const btn = document.getElementById('favoriteBtn');
        const icon = btn.querySelector('i');
        
        if (data.is_favorited) {
            icon.style.color = '#e50914';
        } else {
            icon.style.color = '#fff';
        }
    })
    .catch(error => console.error('Error:', error));
}

function shareMovie() {
    if (navigator.share) {
        navigator.share({
            title: document.title,
            url: window.location.href
        }).catch(err => console.log('Error sharing:', err));
    } else {
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Link copied to clipboard!');
        });
    }
}
</script>

@endsection
