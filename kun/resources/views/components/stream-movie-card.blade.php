@props(['movie', 'favoriteMovieIds' => collect()])

<article class="kun-stream-card">
    <div class="kun-stream-poster">
        <a href="{{ route('movie.show', $movie->id) }}">
            <img src="{{ $movie->thumbnail ?? 'https://via.placeholder.com/300x450/141414/e50914?text=KUN' }}" alt="{{ $movie->title }}">
        </a>
        <span class="rating-pill">
            <i class="fas fa-star"></i>
            {{ number_format($movie->rating, 1) }}
        </span>
        <button
            type="button"
            class="like-btn {{ $favoriteMovieIds->contains($movie->id) ? 'liked' : '' }}"
            data-movie-id="{{ $movie->id }}"
            title="Add to favorites"
            aria-label="Add to favorites"
        >
            <i class="fas fa-heart"></i>
        </button>
    </div>

    <div class="kun-stream-body">
        <h3 class="kun-stream-title">
            <a href="{{ route('movie.show', $movie->id) }}">{{ $movie->title }}</a>
        </h3>

        <div class="kun-stream-meta">
            <span class="genre-text">
                {{ $movie->genres->pluck('name')->take(2)->join(', ') ?: 'Movie' }}
            </span>
            <span class="meta-dot">•</span>
            <span class="meta-item">
                <i class="far fa-clock"></i>
                {{ $movie->duration }} min
            </span>
        </div>

        <div class="kun-stream-language">
            <i class="fas fa-closed-captioning"></i>
            {{ ucfirst($movie->language ?? 'English') }}
        </div>

        @auth
        <a href="{{ route('movie.watch', $movie->id) }}" class="watch-now-btn">
            <i class="fas fa-play"></i>
            Watch Now
        </a>
        @else
        <button type="button" class="watch-now-btn guest-action-btn" data-action="watch">
            <i class="fas fa-play"></i>
            Watch Now
        </button>
        @endauth
    </div>
</article>
