@props(['movie', 'favoriteMovieIds' => collect()])

<div class="movie-card" data-movie-id="{{ $movie->id }}">
    <a href="{{ route('movie.show', $movie->id) }}" class="movie-card-link">
        <img src="{{ $movie->thumbnail ?? 'https://via.placeholder.com/300x450/141414/e50914?text=No+Poster' }}" alt="{{ $movie->title }}">
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
    <button
        type="button"
        class="like-btn {{ $favoriteMovieIds->contains($movie->id) ? 'liked' : '' }}"
        data-movie-id="{{ $movie->id }}"
        title="{{ $favoriteMovieIds->contains($movie->id) ? 'Unlike' : 'Like' }}"
        aria-label="Like movie"
    >
        <i class="fas fa-heart"></i>
    </button>
</div>
