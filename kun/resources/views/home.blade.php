@extends('layouts.app')

@section('title', 'Kun - Watch Movies Online')

@section('content')
<div class="home-page">
    {{-- Hero Section --}}
    @if($featured)
    <section class="hero-section" style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.7)), url('{{ $featured->banner ?? $featured->thumbnail }}');">
        <div class="hero-content">
            <h1 class="hero-title">{{ $featured->title }}</h1>
            <div class="hero-meta">
                <span class="rating">⭐ {{ number_format($featured->rating, 1) }}</span>
                <span>{{ $featured->release_year }}</span>
                <span>{{ $featured->duration }} min</span>
                @if($featured->content_rating)
                <span class="badge">{{ $featured->content_rating }}</span>
                @endif
            </div>
            <p class="hero-description">{{ Str::limit($featured->description, 200) }}</p>
            <div class="hero-actions">
                @auth
                    <a href="{{ route('movie.watch', $featured->id) }}" class="btn btn-primary btn-lg">
                        ▶ Watch Now
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                        Sign Up to Watch
                    </a>
                @endauth
                <a href="{{ route('movie.show', $featured->id) }}" class="btn btn-secondary btn-lg">
                    More Info
                </a>
            </div>
        </div>
    </section>
    @endif

    {{-- Continue Watching (Authenticated Users Only) --}}
    @auth
    @if($continueWatching->count() > 0)
    <section class="movie-section">
        <div class="container">
            <h2 class="section-title">Continue Watching</h2>
            <div class="movie-grid">
                @foreach($continueWatching as $movie)
                <div class="movie-card">
                    <a href="{{ route('movie.watch', $movie->id) }}">
                        <img src="{{ $movie->thumbnail }}" alt="{{ $movie->title }}">
                        <div class="movie-info">
                            <h3>{{ $movie->title }}</h3>
                            <p>{{ $movie->release_year }}</p>
                        </div>
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
    <section class="movie-section">
        <div class="container">
            <h2 class="section-title">Trending Now</h2>
            <div class="movie-grid">
                @foreach($trending as $movie)
                <div class="movie-card">
                    <a href="{{ route('movie.show', $movie->id) }}">
                        <img src="{{ $movie->thumbnail }}" alt="{{ $movie->title }}">
                        <div class="movie-overlay">
                            <div class="movie-info">
                                <h3>{{ $movie->title }}</h3>
                                <div class="movie-meta">
                                    <span>⭐ {{ number_format($movie->rating, 1) }}</span>
                                    <span>{{ $movie->release_year }}</span>
                                </div>
                                @if($movie->genres->count() > 0)
                                <div class="movie-genres">
                                    @foreach($movie->genres->take(2) as $genre)
                                    <span class="genre-badge">{{ $genre->name }}</span>
                                    @endforeach
                                </div>
                                @endif
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
    <section class="movie-section">
        <div class="container">
            <h2 class="section-title">New Releases</h2>
            <div class="movie-grid">
                @foreach($newReleases as $movie)
                <div class="movie-card">
                    <a href="{{ route('movie.show', $movie->id) }}">
                        <img src="{{ $movie->thumbnail }}" alt="{{ $movie->title }}">
                        <div class="movie-overlay">
                            <div class="movie-info">
                                <h3>{{ $movie->title }}</h3>
                                <div class="movie-meta">
                                    <span>⭐ {{ number_format($movie->rating, 1) }}</span>
                                    <span>{{ $movie->release_year }}</span>
                                </div>
                                @if($movie->genres->count() > 0)
                                <div class="movie-genres">
                                    @foreach($movie->genres->take(2) as $genre)
                                    <span class="genre-badge">{{ $genre->name }}</span>
                                    @endforeach
                                </div>
                                @endif
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
    <section class="movie-section">
        <div class="container">
            <h2 class="section-title">Popular Movies</h2>
            <div class="movie-grid">
                @foreach($popular as $movie)
                <div class="movie-card">
                    <a href="{{ route('movie.show', $movie->id) }}">
                        <img src="{{ $movie->thumbnail }}" alt="{{ $movie->title }}">
                        <div class="movie-overlay">
                            <div class="movie-info">
                                <h3>{{ $movie->title }}</h3>
                                <div class="movie-meta">
                                    <span>⭐ {{ number_format($movie->rating, 1) }}</span>
                                    <span>{{ $movie->release_year }}</span>
                                </div>
                                @if($movie->genres->count() > 0)
                                <div class="movie-genres">
                                    @foreach($movie->genres->take(2) as $genre)
                                    <span class="genre-badge">{{ $genre->name }}</span>
                                    @endforeach
                                </div>
                                @endif
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
            <h2 class="section-title">Browse by Genre</h2>
            <div class="genres-grid">
                @foreach($genres as $genre)
                <a href="{{ route('movies.genre', $genre->slug) }}" class="genre-card">
                    @if($genre->icon)
                    <span class="genre-icon">{{ $genre->icon }}</span>
                    @endif
                    <h3>{{ $genre->name }}</h3>
                    <p>{{ $genre->movies_count }} movies</p>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Call to Action for Guests --}}
    @guest
    <section class="cta-section">
        <div class="container text-center">
            <h2>Ready to start watching?</h2>
            <p>Join thousands of movie lovers on Kun. Sign up now and start watching!</p>
            <div class="cta-actions">
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Get Started Free</a>
                <a href="{{ route('movies.index') }}" class="btn btn-outline btn-lg">Browse Movies</a>
            </div>
        </div>
    </section>
    @endguest
</div>

<style>
.home-page {
    min-height: 100vh;
    background: #0a0a0a;
    color: #fff;
}

.hero-section {
    height: 80vh;
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: center;
    padding: 0 5%;
    margin-bottom: 2rem;
}

.hero-content {
    max-width: 600px;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: bold;
    margin-bottom: 1rem;
}

.hero-meta {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
    font-size: 1.1rem;
}

.hero-meta .rating {
    color: #ffc107;
    font-weight: bold;
}

.hero-meta .badge {
    background: rgba(255,255,255,0.2);
    padding: 0.25rem 0.75rem;
    border-radius: 4px;
    font-size: 0.9rem;
}

.hero-description {
    font-size: 1.2rem;
    line-height: 1.6;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.hero-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.btn {
    padding: 0.75rem 2rem;
    border-radius: 4px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
    display: inline-block;
}

.btn-lg {
    padding: 1rem 2.5rem;
    font-size: 1.1rem;
}

.btn-primary {
    background: #e50914;
    color: #fff;
}

.btn-primary:hover {
    background: #f40612;
}

.btn-secondary {
    background: rgba(255,255,255,0.2);
    color: #fff;
}

.btn-secondary:hover {
    background: rgba(255,255,255,0.3);
}

.btn-outline {
    background: transparent;
    color: #fff;
    border: 2px solid #fff;
}

.btn-outline:hover {
    background: #fff;
    color: #000;
}

.movie-section, .genres-section, .cta-section {
    padding: 3rem 0;
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2rem;
}

.section-title {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 1.5rem;
}

.movie-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1.5rem;
}

.movie-card {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.3s;
}

.movie-card:hover {
    transform: scale(1.05);
}

.movie-card img {
    width: 100%;
    height: 300px;
    object-fit: cover;
    display: block;
}

.movie-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.9));
    padding: 1rem;
    opacity: 0;
    transition: opacity 0.3s;
}

.movie-card:hover .movie-overlay {
    opacity: 1;
}

.movie-info h3 {
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
}

.movie-meta {
    display: flex;
    gap: 0.75rem;
    font-size: 0.9rem;
    opacity: 0.8;
    margin-bottom: 0.5rem;
}

.movie-genres {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.genre-badge {
    background: rgba(255,255,255,0.2);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.8rem;
}

.genres-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 1rem;
}

.genre-card {
    background: rgba(255,255,255,0.1);
    padding: 2rem 1rem;
    border-radius: 8px;
    text-align: center;
    text-decoration: none;
    color: #fff;
    transition: all 0.3s;
}

.genre-card:hover {
    background: rgba(255,255,255,0.2);
    transform: translateY(-5px);
}

.genre-icon {
    font-size: 2rem;
    display: block;
    margin-bottom: 0.5rem;
}

.genre-card h3 {
    font-size: 1.2rem;
    margin-bottom: 0.25rem;
}

.genre-card p {
    font-size: 0.9rem;
    opacity: 0.7;
}

.cta-section {
    background: linear-gradient(135deg, #e50914 0%, #831010 100%);
    padding: 4rem 0;
    text-align: center;
}

.cta-section h2 {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.cta-section p {
    font-size: 1.2rem;
    opacity: 0.9;
    margin-bottom: 2rem;
}

.cta-actions {
    display: flex;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.text-center {
    text-align: center;
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-description {
        font-size: 1rem;
    }
    
    .movie-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    }
    
    .movie-card img {
        height: 220px;
    }
}
</style>
@endsection
