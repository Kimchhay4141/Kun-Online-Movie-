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
                        <span class="badge-kun"><i class="fas fa-clapperboard"></i> KUN ONLINE MOVIE</span>
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

    <section class="feature-strip">
        <div class="container">
            <div class="feature-grid">
                <div class="feature-card">
                    <span class="feature-icon feature-red"><i class="fas fa-clapperboard"></i></span>
                    <div>
                        <h3>Huge Collection</h3>
                        <p>Thousands of movies and TV shows</p>
                    </div>
                </div>
                <div class="feature-card">
                    <span class="feature-icon feature-purple"><i class="fas fa-crown"></i></span>
                    <div>
                        <h3>Premium Experience</h3>
                        <p>Watch without ads in HD and 4K</p>
                    </div>
                </div>
                <div class="feature-card">
                    <span class="feature-icon feature-amber"><i class="fas fa-bolt"></i></span>
                    <div>
                        <h3>Instant Access</h3>
                        <p>Stream anytime, anywhere</p>
                    </div>
                </div>
                <div class="feature-card">
                    <span class="feature-icon feature-blue"><i class="fas fa-shield-halved"></i></span>
                    <div>
                        <h3>Safe and Secure</h3>
                        <p>Your data is protected</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

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

    {{-- Full Catalog --}}
    @if($allMovies->count() > 0)
    <section class="content-row all-movies-section">
        <div class="container">
            <div class="row-header">
                <div>
                    <h2 class="row-title">
                        <i class="fas fa-table-cells-large"></i> All Movies
                    </h2>
                    <p class="row-subtitle">Explore {{ $allMovies->count() }} movies and upcoming releases</p>
                </div>
                <a href="{{ route('movies.index') }}" class="view-all">Open Catalog <i class="fas fa-chevron-right"></i></a>
            </div>
            <div class="all-movies-grid">
                @foreach($allMovies as $movie)
                <article class="catalog-card">
                    <a href="{{ route('movie.show', $movie->id) }}" class="movie-link">
                        <div class="movie-poster">
                            @if($movie->status === 'coming_soon')
                            <div class="status-badge">Coming Soon</div>
                            @elseif($movie->is_premium)
                            <div class="status-badge premium">Premium</div>
                            @endif
                            <img src="{{ $movie->thumbnail ?? 'https://via.placeholder.com/300x450/151515/ffffff?text=' . urlencode($movie->title) }}" alt="{{ $movie->title }}" loading="lazy">
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
                        <div class="movie-caption">
                            <h3 class="movie-title movie-title-clamp">{{ $movie->title }}</h3>
                            <span>{{ $movie->release_year ?? 'N/A' }} - {{ $movie->duration ?? 0 }} min</span>
                        </div>
                    </a>
                </article>
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
                <div>
                    <h2 class="row-title">Browse by Genre</h2>
                    <p class="row-subtitle">Find films by mood, style, and story</p>
                </div>
            </div>
            <div class="genres-grid">
                @foreach($genres as $genre)
                <a href="{{ route('movies.genre', $genre->slug) }}" class="genre-card">
                    <div class="genre-card-content">
                        <span class="genre-kicker">Genre</span>
                        <h3 class="genre-name">{{ $genre->name }}</h3>
                        <p class="genre-count">{{ $genre->movies_count }} {{ Str::plural('title', $genre->movies_count) }}</p>
                    </div>
                    <div class="genre-card-footer">
                        <span>Explore</span>
                        <i class="fas fa-arrow-right"></i>
                    </div>
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
    @if($trending->isEmpty() && $newReleases->isEmpty() && $popular->isEmpty() && $allMovies->isEmpty())
    <section class="empty-state">
        <div class="container">
            <div class="empty-content">
                <i class="fas fa-film empty-icon"></i>
                <h2>{{ isset($loadError) ? 'Catalog unavailable' : 'No movies available yet' }}</h2>
                <p>{{ $loadError ?? 'Our streaming catalog is being prepared. Check back soon!' }}</p>
                @if(auth()->check() && auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="btn-hero btn-play">Go to Admin Panel</a>
                @endif
            </div>
        </div>
    </section>
    @endif
</div>

@endsection

@section('styles')
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

/* ==================== Final Homepage Polish ==================== */
.streaming-home {
    background:
        linear-gradient(180deg, rgba(12, 13, 16, 0.94) 0%, rgba(8, 9, 11, 1) 56%),
        radial-gradient(circle at top right, rgba(14, 165, 233, 0.14), transparent 34%),
        #08090b;
}

.hero-banner {
    min-height: 620px;
    height: calc(100vh - 30px);
    border-bottom: 1px solid rgba(255,255,255,0.08);
}

.badge-kun,
.genre-tag,
.btn-hero,
.view-all,
.genre-pill,
.new-badge,
.content-rating,
.btn-cta,
.search-box form {
    border-radius: 8px;
}

.badge-kun {
    display: inline-flex;
    align-items: center;
    gap: 0.55rem;
    background: rgba(229, 9, 20, 0.92);
    box-shadow: 0 12px 30px rgba(229, 9, 20, 0.22);
    letter-spacing: 0;
}

.hero-title {
    letter-spacing: 0;
    max-width: 760px;
}

.hero-description {
    color: rgba(255,255,255,0.84);
}

.content-row {
    padding: 2.4rem 0;
}

.row-subtitle {
    margin-top: 0.35rem;
    color: rgba(255,255,255,0.58);
    font-size: 0.96rem;
}

.movie-poster,
.genre-card {
    border-radius: 8px;
}

.movie-slider,
.all-movies-grid {
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
}

.all-movies-section {
    padding-top: 3.5rem;
}

.all-movies-grid {
    display: grid;
    gap: 1.25rem;
}

.catalog-card {
    min-width: 0;
}

.movie-caption {
    margin-top: 0.8rem;
    min-height: 3.4rem;
}

.movie-caption span {
    display: block;
    margin-top: 0.18rem;
    color: rgba(255,255,255,0.58);
    font-size: 0.86rem;
}

.movie-title-clamp {
    display: -webkit-box;
    min-height: 2.7em;
    overflow: hidden;
    line-height: 1.35;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
}

.status-badge {
    position: absolute;
    top: 0.75rem;
    left: 0.75rem;
    z-index: 2;
    background: #0ea5e9;
    color: #fff;
    border-radius: 8px;
    padding: 0.36rem 0.7rem;
    font-size: 0.72rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0;
    box-shadow: 0 10px 24px rgba(14, 165, 233, 0.3);
}

.status-badge.premium {
    background: #f59e0b;
    color: #111;
    box-shadow: 0 10px 24px rgba(245, 158, 11, 0.28);
}

.movie-item:hover,
.genre-card:hover {
    transform: translateY(-6px);
}

.movie-item:hover .movie-poster,
.catalog-card:hover .movie-poster {
    box-shadow: 0 18px 42px rgba(0,0,0,0.55), 0 0 0 1px rgba(255,255,255,0.16);
}

.cta-section {
    background:
        linear-gradient(135deg, rgba(229, 9, 20, 0.95), rgba(10, 132, 115, 0.82)),
        #141414;
}

.genres-section {
    padding: 4.5rem 0;
    background:
        linear-gradient(180deg, rgba(255,255,255,0.025) 0%, rgba(255,255,255,0) 100%),
        #08090b;
}

.genres-grid {
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1rem;
}

.genre-card {
    min-height: 150px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 1.25rem;
    text-align: left;
    background:
        linear-gradient(145deg, rgba(255,255,255,0.075), rgba(255,255,255,0.025)),
        #101114;
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 8px;
    box-shadow: none;
}

.genre-card::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        linear-gradient(135deg, rgba(229,9,20,0.16), transparent 48%),
        linear-gradient(315deg, rgba(14,165,233,0.12), transparent 52%);
    opacity: 0;
    transition: opacity 0.25s ease;
}

.genre-card:hover {
    background:
        linear-gradient(145deg, rgba(255,255,255,0.095), rgba(255,255,255,0.035)),
        #121418;
    border-color: rgba(255,255,255,0.22);
    box-shadow: 0 16px 34px rgba(0,0,0,0.32);
}

.genre-card:hover::before {
    opacity: 1;
}

.genre-card-content,
.genre-card-footer {
    position: relative;
    z-index: 1;
}

.genre-kicker {
    display: inline-flex;
    margin-bottom: 1.15rem;
    color: rgba(255,255,255,0.52);
    font-size: 0.72rem;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

.genre-name {
    margin-bottom: 0.45rem;
    color: #fff;
    background: none;
    -webkit-text-fill-color: currentColor;
    font-size: 1.35rem;
    letter-spacing: 0;
}

.genre-count {
    color: rgba(255,255,255,0.62);
    font-size: 0.93rem;
}

.genre-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 1.5rem;
    color: rgba(255,255,255,0.76);
    font-size: 0.88rem;
    font-weight: 700;
}

.genre-card-footer i {
    transform: translateX(0);
    transition: transform 0.25s ease;
}

.genre-card:hover .genre-card-footer i {
    transform: translateX(4px);
}

@media (max-width: 768px) {
    .hero-banner {
        min-height: 560px;
        height: auto;
    }

    .row-header {
        align-items: flex-start;
        gap: 1rem;
    }

    .movie-slider,
    .all-movies-grid {
        grid-template-columns: repeat(auto-fill, minmax(145px, 1fr));
    }

    .genres-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .genre-card {
        min-height: 132px;
        padding: 1rem;
    }
}

/* ==================== Reference Streaming Layout ==================== */
.streaming-home {
    margin-top: -80px;
    background:
        radial-gradient(circle at 12% 4%, rgba(20, 75, 106, 0.28), transparent 34%),
        linear-gradient(180deg, #07111a 0%, #02060a 42%, #030407 100%);
}

.hero-banner {
    height: 565px;
    min-height: 565px;
    padding-top: 80px;
    margin-bottom: 0;
    background-position: center right;
    border-bottom: 1px solid rgba(255,255,255,0.06);
}

.hero-banner::before {
    background:
        linear-gradient(90deg, rgba(3,8,13,0.98) 0%, rgba(3,8,13,0.84) 26%, rgba(3,8,13,0.42) 58%, rgba(3,8,13,0.16) 100%),
        linear-gradient(0deg, rgba(3,6,10,0.98) 0%, rgba(3,6,10,0.25) 42%, rgba(3,6,10,0.04) 100%);
}

.hero-banner::after {
    background: none;
}

.hero-content {
    max-width: 560px;
    padding: 2.4rem 0 1.8rem;
}

.hero-badge {
    margin-bottom: 0.8rem;
    animation: none;
}

.badge-kun {
    padding: 0;
    background: transparent;
    box-shadow: none;
    color: #ff1f2d;
    font-size: 0.86rem;
    letter-spacing: 0.04em;
}

.hero-title {
    max-width: 660px;
    margin-bottom: 1.1rem;
    color: #f3f5f7;
    background: none;
    -webkit-text-fill-color: currentColor;
    font-size: clamp(3.4rem, 5.4vw, 6.2rem);
    line-height: 0.92;
    text-transform: uppercase;
    text-shadow: 0 18px 42px rgba(0,0,0,0.7);
}

.hero-meta {
    gap: 0.9rem;
    margin-bottom: 1.15rem;
    color: rgba(255,255,255,0.76);
}

.meta-item {
    position: relative;
    font-size: 0.94rem;
}

.meta-item:not(:last-child)::after {
    content: '';
    width: 1px;
    height: 13px;
    margin-left: 0.9rem;
    background: rgba(255,255,255,0.2);
}

.meta-item.rating {
    color: #fbbf24;
    text-shadow: none;
}

.content-rating {
    padding: 0.28rem 0.7rem;
    border-radius: 6px;
    background: rgba(255,255,255,0.08);
}

.hero-description {
    max-width: 520px;
    margin-bottom: 1.8rem;
    color: rgba(255,255,255,0.78);
    font-size: 1.05rem;
    line-height: 1.55;
}

.hero-buttons {
    margin-bottom: 0;
}

.btn-hero {
    min-width: 146px;
    justify-content: center;
    padding: 0.82rem 1.35rem;
    border-radius: 8px;
    font-size: 0.98rem;
    box-shadow: none;
}

.btn-play {
    background: #ef1f2d;
    color: #fff;
    border-color: #ef1f2d;
}

.btn-info {
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.22);
}

.hero-genres {
    margin-top: 1rem;
}

.genre-tag {
    background: rgba(255,255,255,0.08);
    border-color: rgba(255,255,255,0.14);
    font-size: 0.82rem;
}

.feature-strip {
    position: relative;
    z-index: 4;
    margin-top: -74px;
    padding-bottom: 1.2rem;
}

.feature-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 1rem;
}

.feature-card {
    display: flex;
    align-items: center;
    gap: 1.1rem;
    min-height: 96px;
    padding: 1.2rem 1.35rem;
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 8px;
    background: rgba(9, 18, 29, 0.74);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.04), 0 20px 44px rgba(0,0,0,0.28);
    backdrop-filter: blur(18px);
}

.feature-icon {
    width: 48px;
    height: 48px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 48px;
    border-radius: 10px;
    font-size: 1.4rem;
}

.feature-red {
    color: #fff;
    background: linear-gradient(135deg, #ef1f2d, #a20d18);
}

.feature-purple {
    color: #c084fc;
    background: rgba(126, 34, 206, 0.18);
}

.feature-amber {
    color: #fb923c;
    background: rgba(251, 146, 60, 0.14);
}

.feature-blue {
    color: #93c5fd;
    background: rgba(37, 99, 235, 0.18);
}

.feature-card h3 {
    margin: 0 0 0.25rem;
    color: #fff;
    font-size: 1rem;
    font-weight: 700;
}

.feature-card p {
    margin: 0;
    color: rgba(255,255,255,0.66);
    font-size: 0.91rem;
    line-height: 1.45;
}

.content-row {
    padding: 1.35rem 0 1.15rem;
}

.row-header {
    margin-bottom: 0.9rem;
}

.row-title {
    color: #f6f7f9;
    background: none;
    -webkit-text-fill-color: currentColor;
    font-size: 1.18rem;
    line-height: 1.2;
}

.row-title::before {
    content: '';
    width: 3px;
    height: 20px;
    display: inline-block;
    border-radius: 4px;
    background: #ef1f2d;
}

.row-title i {
    display: none;
}

.view-all {
    padding: 0;
    border: 0;
    background: transparent;
    color: rgba(255,255,255,0.62);
}

.movie-slider {
    display: flex;
    gap: 0.65rem;
    overflow-x: auto;
    overflow-y: visible;
    padding: 0.25rem 0 0.75rem;
    scrollbar-width: none;
}

.movie-slider::-webkit-scrollbar {
    display: none;
}

.movie-item,
.catalog-card {
    flex: 0 0 178px;
    transition: transform 0.22s ease;
}

.movie-item:hover {
    transform: translateY(-4px);
}

.movie-poster {
    aspect-ratio: 1 / 1.12;
    border-radius: 8px;
    background: #10151d;
    border: 1px solid rgba(255,255,255,0.1);
    box-shadow: 0 10px 28px rgba(0,0,0,0.32);
}

.movie-poster img {
    opacity: 0.96;
}

.movie-item:hover .movie-poster img {
    transform: scale(1.04);
}

.movie-overlay {
    padding: 1rem;
    background: linear-gradient(180deg, transparent 32%, rgba(0,0,0,0.88) 100%);
    backdrop-filter: none;
}

.overlay-content h4 {
    margin-bottom: 0.45rem;
    font-size: 0.98rem;
}

.movie-info,
.movie-genres {
    gap: 0.55rem;
}

.trending-number,
.new-badge,
.status-badge {
    top: 0.55rem;
    left: 0.55rem;
    right: auto;
    height: auto;
    width: auto;
    min-width: 0;
    padding: 0.28rem 0.48rem;
    border-radius: 4px;
    background: #ef1f2d;
    color: #fff;
    border: 0;
    font-size: 0.68rem;
    line-height: 1;
    text-transform: uppercase;
    box-shadow: 0 8px 18px rgba(239,31,45,0.28);
}

.trending-number::before {
    content: 'TOP ';
}

.all-movies-section {
    padding-top: 1.6rem;
}

.all-movies-grid {
    grid-template-columns: repeat(auto-fill, minmax(162px, 1fr));
    gap: 0.8rem;
}

.catalog-card {
    flex-basis: auto;
}

.movie-caption {
    min-height: 0;
}

.genres-section {
    padding: 2.4rem 0 3rem;
}

.genres-grid {
    grid-template-columns: repeat(auto-fill, minmax(185px, 1fr));
}

@media (max-width: 1100px) {
    .feature-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 768px) {
    .streaming-home {
        margin-top: -70px;
    }

    .hero-banner {
        min-height: 620px;
        height: auto;
        padding-top: 94px;
    }

    .feature-strip {
        margin-top: -38px;
    }

    .feature-grid {
        grid-template-columns: 1fr;
    }

    .movie-item {
        flex-basis: 148px;
    }

    .all-movies-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}
</style>
@endsection
