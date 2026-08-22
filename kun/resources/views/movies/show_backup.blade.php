@extends('layouts.app')

@section('title', $movie->title . ' - Kun')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/movies.css') }}">
@endsection

@section('content')
<div class="movie-detail-page">
    <!-- Movie Hero -->
    <div class="movie-hero" style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(20,20,20,1)), url('{{ $movie->backdrop_url ?? $movie->poster_url }}');">
        <div class="container">
            <div class="movie-hero-content">
                <div class="movie-poster">
                    <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}">
                    @if($movie->quality)
                    <div class="quality-badge">{{ $movie->quality }}</div>
                    @endif
                </div>

                <div class="movie-info">
                    <h1 class="movie-title">{{ $movie->title }}</h1>
                    
                    <div class="movie-meta">
                        @if($movie->rating)
                        <span class="rating">
                            <i class="fas fa-star"></i>
                            {{ number_format($movie->rating, 1) }}
                        </span>
                        @endif
                        @if($movie->release_date)
                        <span>{{ $movie->release_date->format('Y') }}</span>
                        @endif
                        @if($movie->duration)
                        <span>{{ $movie->duration }} min</span>
                        @endif
                        @if($movie->age_rating)
                        <span class="age-rating">{{ $movie->age_rating }}</span>
                        @endif
                    </div>

                    @if($movie->genres->count() > 0)
                    <div class="movie-genres">
                        @foreach($movie->genres as $genre)
                        <a href="{{ route('movies.genre', $genre->slug) }}" class="genre-tag">
                            {{ $genre->name }}
                        </a>
                        @endforeach
                    </div>
                    @endif

                    <p class="movie-description">{{ $movie->description }}</p>

                    <div class="movie-actions">
                        <a href="{{ route('movie.watch', $movie->id) }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-play"></i>
                            <span>Watch Now</span>
                        </a>

                        @auth
                        <button class="btn btn-secondary" onclick="toggleWatchlist({{ $movie->id }})" id="watchlistBtn">
                            <i class="fas {{ $isInWatchlist ? 'fa-check' : 'fa-plus' }}"></i>
                            <span>{{ $isInWatchlist ? 'In Watchlist' : 'Add to Watchlist' }}</span>
                        </button>

                        <button class="btn btn-icon" onclick="toggleFavorite({{ $movie->id }})" id="favoriteBtn">
                            <i class="fas {{ $isFavorited ? 'fa-heart' : 'fa-heart' }}" style="color: {{ $isFavorited ? '#e50914' : 'inherit' }}"></i>
                        </button>
                        @endauth

                        <button class="btn btn-icon" onclick="shareMovie()">
                            <i class="fas fa-share-alt"></i>
                        </button>
                    </div>

                    @if($userProgress && $userProgress > 0)
                    <div class="watch-progress">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $userProgress }}%"></div>
                        </div>
                        <span class="progress-text">{{ number_format($userProgress) }}% completed</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Movie Details -->
    <div class="movie-details-section">
        <div class="container">
            <div class="details-grid">
                <!-- Main Content -->
                <div class="details-main">
                    @if($movie->trailer_url)
                    <div class="trailer-section">
                        <h2>
                            <i class="fas fa-video"></i>
                            Trailer
                        </h2>
                        <div class="video-container">
                            <iframe src="{{ $movie->trailer_url }}" allowfullscreen></iframe>
                        </div>
                    </div>
                    @endif

                    <div class="cast-section">
                        <h2>
                            <i class="fas fa-users"></i>
                            Cast & Crew
                        </h2>
                        @if($movie->director)
                        <p><strong>Director:</strong> {{ $movie->director }}</p>
                        @endif
                        @if($movie->cast)
                        <p><strong>Cast:</strong> {{ $movie->cast }}</p>
                        @endif
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="details-sidebar">
                    <div class="info-box">
                        <h3>Movie Info</h3>
                        <ul class="info-list">
                            @if($movie->release_date)
                            <li>
                                <span class="label">Release Date</span>
                                <span class="value">{{ $movie->release_date->format('F j, Y') }}</span>
                            </li>
                            @endif
                            <li>
                                <span class="label">Views</span>
                                <span class="value">{{ number_format($movie->view_count) }}</span>
                            </li>
                            @if($movie->language)
                            <li>
                                <span class="label">Language</span>
                                <span class="value">{{ $movie->language }}</span>
                            </li>
                            @endif
                            @if($movie->country)
                            <li>
                                <span class="label">Country</span>
                                <span class="value">{{ $movie->country }}</span>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Movies -->
    @if($relatedMovies->count() > 0)
    <div class="related-movies-section">
        <div class="container">
            <h2 class="section-title">
                <i class="fas fa-film"></i>
                More Like This
            </h2>
            <div class="movie-row">
                @foreach($relatedMovies as $related)
                <x-movie-card :movie="[
                    'id' => $related->id,
                    'title' => $related->title,
                    'poster' => $related->poster_url,
                    'rating' => $related->rating,
                    'year' => $related->release_date ? $related->release_date->format('Y') : null,
                    'genres' => $related->genres->pluck('name')->toArray()
                ]" />
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<script>
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
        const text = btn.querySelector('span');
        
        if (data.in_watchlist) {
            icon.className = 'fas fa-check';
            text.textContent = 'In Watchlist';
        } else {
            icon.className = 'fas fa-plus';
            text.textContent = 'Add to Watchlist';
        }
        
        showToast(data.message, 'success');
    });
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
        
        icon.style.color = data.favorited ? '#e50914' : 'inherit';
        showToast(data.message, 'success');
    });
}

function shareMovie() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $movie->title }}',
            text: 'Check out this movie on Kun!',
            url: window.location.href
        });
    } else {
        const url = window.location.href;
        navigator.clipboard.writeText(url);
        showToast('Link copied to clipboard!', 'success');
    }
}

function showToast(message, type) {
    // Simple toast implementation
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    toast.style.cssText = 'position:fixed;top:100px;right:20px;background:#46d369;color:#fff;padding:15px 25px;border-radius:8px;z-index:10000;animation:slideIn 0.3s ease';
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}
</script>
@endsection
