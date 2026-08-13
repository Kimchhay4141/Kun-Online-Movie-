@props(['movie'])

<article class="coming-soon-card">
    <div class="coming-soon-poster">
        <a href="{{ route('movie.show', $movie->id) }}">
            <img src="{{ $movie->thumbnail ?? 'https://via.placeholder.com/300x450/141414/e50914?text=No+Poster' }}" alt="{{ $movie->title }}">
        </a>
        @if($movie->release_date)
        <span class="release-date-pill">
            <i class="far fa-calendar-alt"></i>
            {{ $movie->release_date->format('M d, Y') }}
        </span>
        @endif
    </div>

    <div class="coming-soon-body">
        <h3 class="coming-soon-title">
            <a href="{{ route('movie.show', $movie->id) }}">{{ $movie->title }}</a>
        </h3>

        <div class="coming-soon-meta">
            <span class="genre-text">
                {{ $movie->genres->pluck('name')->take(2)->join(', ') ?: 'Movie' }}
            </span>
            <span class="meta-dot">•</span>
            <span class="meta-item">
                <i class="far fa-clock"></i>
                {{ $movie->duration }} min
            </span>
        </div>

        <p class="coming-soon-description">{{ Str::limit($movie->description, 90) }}</p>

        @auth
        <button type="button" class="notify-btn notify-toggle-btn" data-movie-id="{{ $movie->id }}">
            <i class="far fa-bell"></i>
            Notify Me
        </button>
        @else
        <button type="button" class="notify-btn guest-action-btn" data-action="notify">
            <i class="far fa-bell"></i>
            Notify Me
        </button>
        @endauth
    </div>
</article>
