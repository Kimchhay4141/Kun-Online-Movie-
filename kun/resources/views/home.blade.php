@extends('layouts.app')

@section('title', 'Kun Online Movie - Stream Unlimited Movies & TV Shows')

@section('content')
<div class="streaming-home">
    {{-- Hero Section - Featured Content --}}
    @if($featured)
    <section class="hero-banner" style="background-image: url('{{ $featured->banner ?? $featured->thumbnail ?? 'https://via.placeholder.com/1920x1080/1a1a1a/ffffff?text=Featured+Movie' }}');">
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
                            <i class="fas fa-star"></i> {{ number_format($featured->rating ?? 0, 1) }}
                        </span>
                        <span class="meta-item">{{ $featured->release_year ?? 'N/A' }}</span>
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
                                <div class="progress-fill" style="width: {{ $movie->progress ?? 45 }}%"></div>
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
                            <img src="{{ $movie->thumbnail ?? 'https://via.placeholder.com/300x450/1a1a1a/ffffff?text=' . urlencode($movie->title) }}" alt="{{ $movie->title }}" loading="lazy">
                            <div class="movie-overlay">
                                <div class="overlay-content">
                                    <h4>{{ $movie->title }}</h4>
                                    <div class="movie-info">
                                        <span><i class="fas fa-star"></i> {{ number_format($movie->rating ?? 0, 1) }}</span>
                                        <span>{{ $movie->release_year ?? 'N/A' }}</span>
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
                            <img src="{{ $movie->thumbnail ?? 'https://via.placeholder.com/300x450/1a1a1a/ffffff?text=' . urlencode($movie->title) }}" alt="{{ $movie->title }}" loading="lazy">
                            <div class="movie-overlay">
                                <div class="overlay-content">
                                    <h4>{{ $movie->title }}</h4>
                                    <div class="movie-info">
                                        <span><i class="fas fa-star"></i> {{ number_format($movie->rating ?? 0, 1) }}</span>
                                        <span>{{ $movie->release_year ?? 'N/A' }}</span>
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
                            <img src="{{ $movie->thumbnail ?? 'https://via.placeholder.com/300x450/1a1a1a/ffffff?text=' . urlencode($movie->title) }}" alt="{{ $movie->title }}" loading="lazy">
                            <div class="movie-overlay">
                                <div class="overlay-content">
                                    <h4>{{ $movie->title }}</h4>
                                    <div class="movie-info">
                                        <span><i class="fas fa-star"></i> {{ number_format($movie->rating ?? 0, 1) }}</span>
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
/* ==================== Enhanced Base Styles ==================== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.streaming-home {
    min-height: 100vh;
    background: linear-gradient(135deg, #0a0a0a 0%, #141414 50%, #1a1a1a 100%);
    color: #fff;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    animation: fadeIn 0.8s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2.5rem;
}

/* ==================== Enhanced Hero Banner ==================== */
.hero-banner {
    position: relative;
    height: 90vh;
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
    overflow: hidden;
}

.hero-banner::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(
        to right,
        rgba(0,0,0,0.98) 0%,
        rgba(0,0,0,0.85) 30%,
        rgba(0,0,0,0.6) 50%,
        rgba(0,0,0,0.3) 70%,
        transparent 100%
    ),
    linear-gradient(
        to top,
        rgba(0,0,0,0.98) 0%,
        rgba(0,0,0,0.7) 30%,
        transparent 60%
    );
    z-index: 1;
}

.hero-banner::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(ellipse at center, transparent 0%, rgba(0,0,0,0.3) 100%);
    z-index: 1;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1;
}

.hero-content-wrapper {
    position: relative;
    z-index: 2;
    width: 100%;
}

.hero-content {
    max-width: 650px;
    padding: 4rem 0;
    animation: slideIn 1s ease-out;
}

@keyframes slideIn {
    from { 
        opacity: 0;
        transform: translateX(-30px);
    }
    to { 
        opacity: 1;
        transform: translateX(0);
    }
}

.hero-badge {
    margin-bottom: 1.5rem;
    animation: badgePulse 2s ease-in-out infinite;
}

@keyframes badgePulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.badge-kun {
    display: inline-block;
    background: linear-gradient(135deg, #e50914 0%, #ff2d37 50%, #e50914 100%);
    background-size: 200% 200%;
    padding: 0.5rem 1.25rem;
    border-radius: 25px;
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    box-shadow: 0 4px 15px rgba(229, 9, 20, 0.4);
    animation: gradientShift 3s ease infinite;
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.hero-title {
    font-size: 4.5rem;
    font-weight: 900;
    line-height: 1.1;
    margin-bottom: 1.5rem;
    text-shadow: 3px 3px 15px rgba(0,0,0,0.9), 0 0 30px rgba(229, 9, 20, 0.3);
    background: linear-gradient(135deg, #fff 0%, #e0e0e0 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-meta {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 2rem;
    font-size: 1.05rem;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: rgba(255,255,255,0.9);
    font-weight: 500;
}

.meta-item.rating {
    color: #ffd700;
    font-weight: 700;
    font-size: 1.1rem;
    text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
}

.content-rating {
    background: rgba(255,255,255,0.15);
    padding: 0.35rem 0.85rem;
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.3);
    font-size: 0.85rem;
    font-weight: 700;
    backdrop-filter: blur(10px);
}

.hero-description {
    font-size: 1.35rem;
    line-height: 1.7;
    margin-bottom: 2.5rem;
    color: rgba(255,255,255,0.95);
    text-shadow: 2px 2px 8px rgba(0,0,0,0.8);
    max-width: 550px;
}

.hero-buttons {
    display: flex;
    gap: 1.25rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}

.btn-hero {
    display: inline-flex;
    align-items: center;
    gap: 0.85rem;
    padding: 1rem 2.5rem;
    font-size: 1.15rem;
    font-weight: 700;
    border-radius: 50px;
    text-decoration: none;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border: none;
    cursor: pointer;
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
}

.btn-play {
    background: linear-gradient(135deg, #fff 0%, #f0f0f0 100%);
    color: #000;
    border: 2px solid rgba(255,255,255,0.5);
}

.btn-play:hover {
    background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%);
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 8px 25px rgba(255,255,255,0.4);
}

.btn-info {
    background: rgba(109,109,110,0.8);
    color: #fff;
    backdrop-filter: blur(15px);
    border: 2px solid rgba(255,255,255,0.2);
}

.btn-info:hover {
    background: rgba(109,109,110,0.9);
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.4);
    border-color: rgba(255,255,255,0.4);
}

.hero-genres {
    display: flex;
    gap: 0.85rem;
    flex-wrap: wrap;
}

.genre-tag {
    background: rgba(255,255,255,0.12);
    padding: 0.5rem 1.25rem;
    border-radius: 25px;
    font-size: 0.9rem;
    font-weight: 600;
    border: 1px solid rgba(255,255,255,0.25);
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}

.genre-tag:hover {
    background: rgba(255,255,255,0.2);
    border-color: rgba(255,255,255,0.4);
    transform: translateY(-2px);
}

/* ==================== Enhanced Content Rows ==================== */
.content-row {
    padding: 3rem 0;
    animation: fadeInUp 0.8s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.row-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.row-title {
    font-size: 2rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    gap: 0.85rem;
    background: linear-gradient(135deg, #fff 0%, #e0e0e0 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.row-title i {
    color: #e50914;
    font-size: 1.8rem;
    filter: drop-shadow(0 0 10px rgba(229, 9, 20, 0.5));
}

.view-all {
    color: #e5e5e5;
    text-decoration: none;
    font-size: 1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
}

.view-all:hover {
    color: #fff;
    background: rgba(255,255,255,0.1);
    transform: translateX(5px);
    border-color: rgba(255,255,255,0.2);
}

/* ==================== Enhanced Movie Slider ==================== */
.movie-slider {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1.5rem;
}

.movie-item {
    position: relative;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.movie-item:hover {
    transform: scale(1.08) translateY(-10px);
    z-index: 10;
}

.movie-link {
    display: block;
    text-decoration: none;
    color: #fff;
}

.movie-poster {
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    aspect-ratio: 2/3;
    background: linear-gradient(135deg, #2a2a2a 0%, #1a1a1a 100%);
    box-shadow: 0 4px 20px rgba(0,0,0,0.4);
    transition: all 0.4s ease;
}

.movie-item:hover .movie-poster {
    box-shadow: 0 15px 40px rgba(0,0,0,0.6), 0 0 30px rgba(229, 9, 20, 0.2);
}

.movie-poster img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.4s ease;
}

.movie-item:hover .movie-poster img {
    transform: scale(1.1);
}

.movie-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.98) 0%, rgba(0,0,0,0.7) 40%, transparent 70%);
    opacity: 0;
    transition: opacity 0.4s ease;
    display: flex;
    align-items: flex-end;
    padding: 1.5rem;
    backdrop-filter: blur(5px);
}

.movie-item:hover .movie-overlay {
    opacity: 1;
}

.play-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0.8);
    font-size: 4.5rem;
    color: rgba(255,255,255,0.95);
    opacity: 0;
    transition: all 0.4s ease;
    filter: drop-shadow(0 0 20px rgba(0,0,0,0.8));
}

.movie-item:hover .play-icon {
    opacity: 1;
    transform: translate(-50%, -50%) scale(1);
}

.overlay-content h4 {
    font-size: 1.2rem;
    margin-bottom: 0.75rem;
    font-weight: 700;
    text-shadow: 2px 2px 8px rgba(0,0,0,0.8);
}

.movie-info {
    display: flex;
    gap: 1rem;
    font-size: 0.9rem;
    color: rgba(255,255,255,0.9);
    margin-bottom: 0.75rem;
    font-weight: 500;
}

.movie-info i {
    color: #ffd700;
}

.movie-genres {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.genre-pill {
    background: rgba(255,255,255,0.2);
    padding: 0.3rem 0.85rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.3);
}

.movie-title {
    margin-top: 1rem;
    font-size: 1.05rem;
    font-weight: 600;
    color: #e5e5e5;
    transition: color 0.3s ease;
}

.movie-item:hover .movie-title {
    color: #fff;
}

.progress-bar {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: rgba(255,255,255,0.2);
    border-radius: 0 0 16px 16px;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #e50914 0%, #ff2d37 100%);
    border-radius: 0 0 16px 16px;
    box-shadow: 0 0 10px rgba(229, 9, 20, 0.5);
}

.trending-number {
    position: absolute;
    top: 0.75rem;
    left: 0.75rem;
    background: linear-gradient(135deg, #e50914 0%, #ff2d37 100%);
    color: #fff;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-weight: 800;
    font-size: 1.2rem;
    z-index: 2;
    box-shadow: 0 4px 15px rgba(229, 9, 20, 0.5);
    border: 2px solid rgba(255,255,255,0.2);
}

.new-badge {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    background: linear-gradient(135deg, #46d369 0%, #34d399 100%);
    color: #000;
    padding: 0.4rem 0.9rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 800;
    z-index: 2;
    box-shadow: 0 4px 15px rgba(70, 211, 105, 0.4);
    letter-spacing: 1px;
}

/* ==================== Enhanced Genres Section ==================== */
.genres-section {
    padding: 4rem 0;
    background: linear-gradient(180deg, transparent 0%, rgba(0,0,0,0.3) 100%);
}

.genres-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1.5rem;
}

.genre-card {
    background: linear-gradient(135deg, rgba(255,255,255,0.08) 0%, rgba(255,255,255,0.03) 100%);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 16px;
    padding: 2.5rem 2rem;
    text-align: center;
    text-decoration: none;
    color: #fff;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    backdrop-filter: blur(10px);
    position: relative;
    overflow: hidden;
}

.genre-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(229, 9, 20, 0.1) 0%, transparent 100%);
    opacity: 0;
    transition: opacity 0.4s ease;
}

.genre-card:hover::before {
    opacity: 1;
}

.genre-card:hover {
    background: linear-gradient(135deg, rgba(255,255,255,0.12) 0%, rgba(255,255,255,0.05) 100%);
    border-color: #e50914;
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 15px 40px rgba(0,0,0,0.4), 0 0 30px rgba(229, 9, 20, 0.2);
}

.genre-icon {
    font-size: 3rem;
    margin-bottom: 1.25rem;
    display: block;
    transition: transform 0.4s ease;
    filter: drop-shadow(0 0 15px rgba(255,255,255,0.3));
}

.genre-card:hover .genre-icon {
    transform: scale(1.15) rotate(5deg);
}

.genre-name {
    font-size: 1.35rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    background: linear-gradient(135deg, #fff 0%, #e0e0e0 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.genre-count {
    font-size: 0.95rem;
    color: rgba(255,255,255,0.7);
    font-weight: 500;
}

/* ==================== Enhanced CTA Section ==================== */
.cta-section {
    background: linear-gradient(135deg, #e50914 0%, #b20710 50%, #e50914 100%);
    background-size: 200% 200%;
    padding: 6rem 0;
    margin: 4rem 0;
    position: relative;
    overflow: hidden;
    animation: gradientShift 8s ease infinite;
}

.cta-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 50%, rgba(255,255,255,0.1) 0%, transparent 50%);
}

.cta-content {
    text-align: center;
    position: relative;
    z-index: 2;
}

.cta-text h2 {
    font-size: 3.5rem;
    font-weight: 900;
    margin-bottom: 1.5rem;
    text-shadow: 3px 3px 15px rgba(0,0,0,0.3);
}

.cta-text p {
    font-size: 1.6rem;
    margin-bottom: 1rem;
    opacity: 0.95;
}

.cta-subtitle {
    font-size: 1.2rem !important;
    opacity: 0.9;
    margin-bottom: 3rem !important;
    font-weight: 500;
}

.cta-buttons {
    display: flex;
    justify-content: center;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.btn-cta {
    display: inline-flex;
    align-items: center;
    gap: 0.85rem;
    padding: 1.25rem 3rem;
    font-size: 1.2rem;
    font-weight: 700;
    border-radius: 50px;
    text-decoration: none;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

.btn-cta-primary {
    background: linear-gradient(135deg, #fff 0%, #f0f0f0 100%);
    color: #e50914;
    border: 2px solid rgba(255,255,255,0.5);
}

.btn-cta-primary:hover {
    background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%);
    transform: translateY(-5px) scale(1.05);
    box-shadow: 0 10px 30px rgba(255,255,255,0.4);
}

.btn-cta-secondary {
    background: rgba(255,255,255,0.15);
    color: #fff;
    border: 2px solid rgba(255,255,255,0.4);
}

.btn-cta-secondary:hover {
    background: rgba(255,255,255,0.25);
    transform: translateY(-5px);
    border-color: rgba(255,255,255,0.6);
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

/* ==================== Enhanced Empty State ==================== */
.empty-state {
    padding: 8rem 0;
    animation: fadeIn 1s ease-out;
}

.empty-content {
    text-align: center;
}

.empty-icon {
    font-size: 6rem;
    color: rgba(255,255,255,0.15);
    margin-bottom: 2.5rem;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-20px); }
}

.empty-content h2 {
    font-size: 2.5rem;
    margin-bottom: 1.5rem;
    font-weight: 800;
    background: linear-gradient(135deg, #fff 0%, #e0e0e0 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.empty-content p {
    font-size: 1.3rem;
    color: rgba(255,255,255,0.6);
    margin-bottom: 2.5rem;
    font-weight: 500;
}

/* ==================== Enhanced Responsive ==================== */
@media (max-width: 1024px) {
    .hero-title { font-size: 3.5rem; }
    .movie-slider { grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); }
    .container { padding: 0 2rem; }
}

@media (max-width: 768px) {
    .container { padding: 0 1.5rem; }
    .hero-title { font-size: 2.5rem; }
    .hero-description { font-size: 1.1rem; }
    .btn-hero { padding: 0.85rem 2rem; font-size: 1rem; }
    .movie-slider { grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem; }
    .row-title { font-size: 1.5rem; }
    .cta-text h2 { font-size: 2.5rem; }
    .cta-text p { font-size: 1.3rem; }
    .genres-grid { grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); }
    .hero-banner { height: 80vh; }
}

@media (max-width: 480px) {
    .hero-banner { height: 70vh; }
    .hero-title { font-size: 2rem; }
    .hero-content { padding: 2rem 0; }
    .movie-slider { grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 0.75rem; }
    .btn-hero { padding: 0.75rem 1.5rem; font-size: 0.95rem; }
    .row-title { font-size: 1.3rem; }
    .cta-text h2 { font-size: 2rem; }
    .cta-text p { font-size: 1.1rem; }
    .genres-grid { grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); }
    .container { padding: 0 1rem; }
    .hero-title { font-size: 1.75rem; }
    .hero-meta { gap: 0.75rem; font-size: 0.85rem; }
    .hero-buttons { flex-direction: column; }
    .btn-hero { width: 100%; justify-content: center; }
    .movie-slider { grid-template-columns: repeat(2, 1fr); }
}
</style>
@endpush
@endsection
