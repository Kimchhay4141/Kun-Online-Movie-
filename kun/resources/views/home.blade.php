@extends('layouts.app')

@section('title', 'Kun Online Movie - Stream Unlimited Movies & TV Shows')

@section('content')
<div class="streaming-home">
    {{-- Hero Section - Featured Content --}}
    @if($featured)
    <section class="hero-banner" style="background-image: url('{{ $featured->banner ?? $featured->thumbnail ?? 'https://via.placeholder.com/1920x1080' }}');">
        <div class="hero-overlay"></div>
        <div class="hero-content-wrapper">
            <div class="container">
                <div class="hero-content">
                    <div class="hero-badge">
                        <span class="badge-kun">🎬 KUN ONLINE MOVIE</span>
                    </div>
                    <h1 class="hero-title">{{ $featured->title }}</h1>
                    <div class="hero-meta">
                        <span class="meta-item rating">
                            <i class="fas fa-star"></i> {{ number_format($featured->rating, 1) }}
                        </span>
                        <span class="meta-item">{{ $featured->release_year }}</span>
                        <span class="meta-item">{{ $featured->duration ?? 120 }} min</span>
                        @if($featured->content_rating)
                        <span class="meta-item content-rating">{{ $featured->content_rating }}</span>
                        @endif
                    </div>
                    <p class="hero-description">{{ Str::limit($featured->description ?? 'Experience unlimited streaming of movies online.', 250) }}</p>
                    
                    <div class="hero-buttons">
                        @auth
                            <a href="{{ route('movie.watch', $featured->id) }}" class="btn-hero btn-play">
                                <i class="fas fa-play"></i> Play Now
                            </a>
                            <a href="{{ route('movie.show', $featured->id) }}" class="btn-hero btn-info">
                                <i class="fas fa-info-circle"></i> More Info
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn-hero btn-play">
                                <i class="fas fa-play"></i> Start Streaming
                            </a>
                            <a href="{{ route('login') }}" class="btn-hero btn-info">
                                <i class="fas fa-sign-in-alt"></i> Sign In
                            </a>
                        @endauth
                    </div>

                    @if($featured->genres && $featured->genres->count() > 0)
                    <div class="hero-genres">
                        @foreach($featured->genres->take(3) as $genre)
                        <span class="genre-tag">{{ $genre->name }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Continue Watching (Auth Only) --}}
    @auth
    @if($continueWatching->count() > 0)
    <section class="content-row">
        <div class="container">
            <div class="row-header">
                <h2 class="row-title">
                    <i class="fas fa-history"></i> Continue Watching
                </h2>
            </div>
            <div class="movie-slider">
                @foreach($continueWatching as $movie)
                <div class="movie-item">
                    <a href="{{ route('movie.watch', $movie->id) }}" class="movie-link">
                        <div class="movie-poster">
                            <img src="{{ $movie->thumbnail ?? 'https://via.placeholder.com/300x450' }}" alt="{{ $movie->title }}" loading="lazy">
                            <div class="movie-overlay">
                                <div class="play-icon">
                                    <i class="fas fa-play-circle"></i>
                                </div>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 45%"></div>
                            </div>
                        </div>
                        <h3 class="movie-title">{{ $movie->title }}</h3>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    @endauth

    {{-- Trending Now --}}
    @if($trending->count() > 0)
    <section class="content-row">
        <div class="container">
            <div class="row-header">
                <h2 class="row-title">
                    <i class="fas fa-fire"></i> Trending Now
                </h2>
                <a href="{{ route('movies.index') }}" class="view-all">View All <i class="fas fa-chevron-right"></i></a>
            </div>
            <div class="movie-slider">
                @foreach($trending as $index => $movie)
                <div class="movie-item">
                    <a href="{{ route('movie.show', $movie->id) }}" class="movie-link">
                        <div class="movie-poster">
                            <div class="trending-number">{{ $index + 1 }}</div>
                            <img src="{{ $movie->thumbnail ?? 'https://via.placeholder.com/300x450' }}" alt="{{ $movie->title }}" loading="lazy">
                            <div class="movie-overlay">
                                <div class="overlay-content">
                                    <h4>{{ $movie->title }}</h4>
                                    <div class="movie-info">
                                        <span><i class="fas fa-star"></i> {{ number_format($movie->rating, 1) }}</span>
                                        <span>{{ $movie->release_year }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- New Releases --}}
    @if($newReleases->count() > 0)
    <section class="content-row">
        <div class="container">
            <div class="row-header">
                <h2 class="row-title">
                    <i class="fas fa-sparkles"></i> New Releases
                </h2>
                <a href="{{ route('movies.index') }}" class="view-all">View All <i class="fas fa-chevron-right"></i></a>
            </div>
            <div class="movie-slider">
                @foreach($newReleases as $movie)
                <div class="movie-item">
                    <a href="{{ route('movie.show', $movie->id) }}" class="movie-link">
                        <div class="movie-poster">
                            <div class="new-badge">NEW</div>
                            <img src="{{ $movie->thumbnail ?? 'https://via.placeholder.com/300x450' }}" alt="{{ $movie->title }}" loading="lazy">
                            <div class="movie-overlay">
                                <div class="overlay-content">
                                    <h4>{{ $movie->title }}</h4>
                                    <div class="movie-info">
                                        <span><i class="fas fa-star"></i> {{ number_format($movie->rating, 1) }}</span>
                                        <span>{{ $movie->release_year }}</span>
                                    </div>
                                    @if($movie->genres && $movie->genres->count() > 0)
                                    <div class="movie-genres">
                                        @foreach($movie->genres->take(2) as $genre)
                                        <span class="genre-pill">{{ $genre->name }}</span>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Popular Movies --}}
    @if($popular->count() > 0)
    <section class="content-row">
        <div class="container">
            <div class="row-header">
                <h2 class="row-title">
                    <i class="fas fa-crown"></i> Popular on Kun
                </h2>
                <a href="{{ route('movies.index') }}" class="view-all">View All <i class="fas fa-chevron-right"></i></a>
            </div>
            <div class="movie-slider">
                @foreach($popular as $movie)
                <div class="movie-item">
                    <a href="{{ route('movie.show', $movie->id) }}" class="movie-link">
                        <div class="movie-poster">
                            <img src="{{ $movie->thumbnail ?? 'https://via.placeholder.com/300x450' }}" alt="{{ $movie->title }}" loading="lazy">
                            <div class="movie-overlay">
                                <div class="overlay-content">
                                    <h4>{{ $movie->title }}</h4>
                                    <div class="movie-info">
                                        <span><i class="fas fa-star"></i> {{ number_format($movie->rating, 1) }}</span>
                                        <span>{{ $movie->duration ?? 120 }} min</span>
                                    </div>
                                    @if($movie->genres && $movie->genres->count() > 0)
                                    <div class="movie-genres">
                                        @foreach($movie->genres->take(2) as $genre)
                                        <span class="genre-pill">{{ $genre->name }}</span>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Browse by Genre --}}
    @if($genres->count() > 0)
    <section class="genres-section">
        <div class="container">
            <div class="row-header">
                <h2 class="row-title">
                    <i class="fas fa-th-large"></i> Browse by Genre
                </h2>
            </div>
            <div class="genres-grid">
                @foreach($genres as $genre)
                <a href="{{ route('movies.genre', $genre->slug) }}" class="genre-card">
                    <div class="genre-icon">
                        @if($genre->icon)
                            {{ $genre->icon }}
                        @else
                            <i class="fas fa-film"></i>
                        @endif
                    </div>
                    <h3 class="genre-name">{{ $genre->name }}</h3>
                    <p class="genre-count">{{ $genre->movies_count }} titles</p>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- CTA Section for Guests --}}
    @guest
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <div class="cta-text">
                    <h2>Unlimited movies, TV shows, and more</h2>
                    <p>Stream anywhere. Cancel anytime.</p>
                    <p class="cta-subtitle">Ready to watch? Create your free account to start streaming movies online now.</p>
                </div>
                <div class="cta-buttons">
                    <a href="{{ route('register') }}" class="btn-cta btn-cta-primary">
                        Get Started Free <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="{{ route('movies.index') }}" class="btn-cta btn-cta-secondary">
                        Browse Catalog
                    </a>
                </div>
            </div>
        </div>
    </section>
    @endguest

    {{-- Empty State --}}
    @if($trending->isEmpty() && $newReleases->isEmpty() && $popular->isEmpty())
    <section class="empty-state">
        <div class="container">
            <div class="empty-content">
                <i class="fas fa-film empty-icon"></i>
                <h2>No movies available yet</h2>
                <p>Our streaming catalog is being prepared. Check back soon!</p>
                @if(auth()->check() && auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="btn-hero btn-play">Go to Admin Panel</a>
                @endif
            </div>
        </div>
    </section>
    @endif
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
/* ==================== Base Styles ==================== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.streaming-home {
    min-height: 100vh;
    background: #141414;
    color: #fff;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2.5rem;
}

/* ==================== Hero Banner ==================== */
.hero-banner {
    position: relative;
    height: 85vh;
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(
        to right,
        rgba(0,0,0,0.95) 0%,
        rgba(0,0,0,0.7) 40%,
        rgba(0,0,0,0.4) 60%,
        transparent 100%
    ),
    linear-gradient(
        to top,
        rgba(0,0,0,0.95) 0%,
        transparent 40%
    );
}

.hero-content-wrapper {
    position: relative;
    z-index: 2;
    width: 100%;
}

.hero-content {
    max-width: 600px;
    padding: 3rem 0;
}

.hero-badge {
    margin-bottom: 1rem;
}

.badge-kun {
    display: inline-block;
    background: linear-gradient(135deg, #e50914 0%, #ff2d37 100%);
    padding: 0.4rem 1rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
}

.hero-title {
    font-size: 4rem;
    font-weight: 800;
    line-height: 1.1;
    margin-bottom: 1.25rem;
    text-shadow: 2px 2px 8px rgba(0,0,0,0.8);
}

.hero-meta {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    margin-bottom: 1.5rem;
    font-size: 1rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

.meta-item.rating {
    color: #ffd700;
    font-weight: 600;
}

.content-rating {
    background: rgba(255,255,255,0.15);
    padding: 0.25rem 0.75rem;
    border-radius: 4px;
    border: 1px solid rgba(255,255,255,0.3);
    font-size: 0.85rem;
    font-weight: 600;
}

.hero-description {
    font-size: 1.25rem;
    line-height: 1.6;
    margin-bottom: 2rem;
    color: rgba(255,255,255,0.9);
    text-shadow: 1px 1px 4px rgba(0,0,0,0.8);
}

.hero-buttons {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.btn-hero {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.85rem 2rem;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-play {
    background: #fff;
    color: #000;
}

.btn-play:hover {
    background: rgba(255,255,255,0.85);
    transform: scale(1.05);
}

.btn-info {
    background: rgba(109,109,110,0.7);
    color: #fff;
    backdrop-filter: blur(10px);
}

.btn-info:hover {
    background: rgba(109,109,110,0.5);
}

.hero-genres {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.genre-tag {
    background: rgba(255,255,255,0.1);
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    border: 1px solid rgba(255,255,255,0.2);
}

/* ==================== Content Rows ==================== */
.content-row {
    padding: 2.5rem 0;
}

.row-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.row-title {
    font-size: 1.75rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.row-title i {
    color: #e50914;
}

.view-all {
    color: #e5e5e5;
    text-decoration: none;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: color 0.3s;
}

.view-all:hover {
    color: #fff;
}

/* ==================== Movie Slider ==================== */
.movie-slider {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
}

.movie-item {
    position: relative;
    transition: transform 0.3s ease;
}

.movie-item:hover {
    transform: scale(1.05);
    z-index: 10;
}

.movie-link {
    display: block;
    text-decoration: none;
    color: #fff;
}

.movie-poster {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    aspect-ratio: 2/3;
    background: #2a2a2a;
}

.movie-poster img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.movie-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.95), transparent 50%);
    opacity: 0;
    transition: opacity 0.3s ease;
    display: flex;
    align-items: flex-end;
    padding: 1rem;
}

.movie-item:hover .movie-overlay {
    opacity: 1;
}

.play-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 4rem;
    color: rgba(255,255,255,0.9);
}

.overlay-content h4 {
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.movie-info {
    display: flex;
    gap: 0.75rem;
    font-size: 0.85rem;
    color: rgba(255,255,255,0.8);
    margin-bottom: 0.5rem;
}

.movie-genres {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.genre-pill {
    background: rgba(255,255,255,0.15);
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
}

.movie-title {
    margin-top: 0.75rem;
    font-size: 1rem;
    font-weight: 500;
    color: #e5e5e5;
}

.progress-bar {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: rgba(255,255,255,0.2);
}

.progress-fill {
    height: 100%;
    background: #e50914;
}

.trending-number {
    position: absolute;
    top: 0.5rem;
    left: 0.5rem;
    background: #e50914;
    color: #fff;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-weight: 700;
    font-size: 1.1rem;
    z-index: 2;
}

.new-badge {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    background: #46d369;
    color: #000;
    padding: 0.3rem 0.75rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 700;
    z-index: 2;
}

/* ==================== Genres Section ==================== */
.genres-section {
    padding: 3rem 0;
}

.genres-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 1.25rem;
}

.genre-card {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 10px;
    padding: 2rem 1.5rem;
    text-align: center;
    text-decoration: none;
    color: #fff;
    transition: all 0.3s ease;
}

.genre-card:hover {
    background: rgba(255,255,255,0.1);
    border-color: #e50914;
    transform: translateY(-5px);
}

.genre-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    display: block;
}

.genre-name {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.genre-count {
    font-size: 0.9rem;
    color: rgba(255,255,255,0.6);
}

/* ==================== CTA Section ==================== */
.cta-section {
    background: linear-gradient(135deg, #e50914 0%, #b20710 100%);
    padding: 5rem 0;
    margin: 3rem 0;
}

.cta-content {
    text-align: center;
}

.cta-text h2 {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 1rem;
}

.cta-text p {
    font-size: 1.5rem;
    margin-bottom: 0.75rem;
}

.cta-subtitle {
    font-size: 1.1rem !important;
    opacity: 0.9;
    margin-bottom: 2.5rem !important;
}

.cta-buttons {
    display: flex;
    justify-content: center;
    gap: 1.25rem;
    flex-wrap: wrap;
}

.btn-cta {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 2.5rem;
    font-size: 1.15rem;
    font-weight: 600;
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-cta-primary {
    background: #fff;
    color: #e50914;
}

.btn-cta-primary:hover {
    background: rgba(255,255,255,0.9);
    transform: scale(1.05);
}

.btn-cta-secondary {
    background: transparent;
    color: #fff;
    border: 2px solid #fff;
}

.btn-cta-secondary:hover {
    background: rgba(255,255,255,0.1);
}

/* ==================== Empty State ==================== */
.empty-state {
    padding: 6rem 0;
}

.empty-content {
    text-align: center;
}

.empty-icon {
    font-size: 5rem;
    color: rgba(255,255,255,0.2);
    margin-bottom: 2rem;
}

.empty-content h2 {
    font-size: 2rem;
    margin-bottom: 1rem;
}

.empty-content p {
    font-size: 1.2rem;
    color: rgba(255,255,255,0.6);
    margin-bottom: 2rem;
}

/* ==================== Responsive ==================== */
@media (max-width: 1024px) {
    .hero-title { font-size: 3rem; }
    .movie-slider { grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); }
}

@media (max-width: 768px) {
    .container { padding: 0 1.5rem; }
    .hero-title { font-size: 2.25rem; }
    .hero-description { font-size: 1rem; }
    .btn-hero { padding: 0.75rem 1.5rem; font-size: 1rem; }
    .movie-slider { grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 0.75rem; }
    .row-title { font-size: 1.4rem; }
    .cta-text h2 { font-size: 2rem; }
    .cta-text p { font-size: 1.2rem; }
    .genres-grid { grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); }
}

@media (max-width: 480px) {
    .hero-banner { height: 70vh; }
    .hero-title { font-size: 1.75rem; }
    .hero-meta { gap: 0.75rem; font-size: 0.85rem; }
    .hero-buttons { flex-direction: column; }
    .btn-hero { width: 100%; justify-content: center; }
    .movie-slider { grid-template-columns: repeat(2, 1fr); }
}
</style>
@endpush
@endsection
