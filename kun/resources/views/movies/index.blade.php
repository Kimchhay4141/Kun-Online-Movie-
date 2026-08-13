@extends('layouts.app')

@section('title', 'Browse Movies - Kun')

@section('content')
<div class="browse-page">
    <div class="stream-page-header">
        <div class="container">
            <h1><i class="fas fa-play-circle"></i> {{ isset($genre) ? $genre->name : 'Browse Movies' }}</h1>
            <p>{{ $movies->total() }} movies ready to stream on Kun</p>
        </div>
    </div>

    <div class="filters-bar">
        <div class="container">
            <form action="{{ route('movies.index') }}" method="GET" class="filters-form">
                <select name="genre" onchange="this.form.submit()">
                    <option value="">All Genres</option>
                    @foreach($genres as $g)
                    <option value="{{ $g->slug }}" {{ request('genre') == $g->slug ? 'selected' : '' }}>{{ $g->name }}</option>
                    @endforeach
                </select>
                <select name="year" onchange="this.form.submit()">
                    <option value="">All Years</option>
                    @for($y = date('Y'); $y >= 1990; $y--)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                <select name="sort" onchange="this.form.submit()">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                    <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                    <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title A-Z</option>
                </select>
            </form>
        </div>
    </div>

    <div class="container browse-grid-section">
        @if($movies->count() > 0)
        <div class="kun-stream-grid">
            @foreach($movies as $movie)
                <x-stream-movie-card :movie="$movie" :favorite-movie-ids="$favoriteMovieIds ?? collect()" />
            @endforeach
        </div>
        <div class="pagination-wrap">{{ $movies->links() }}</div>
        @else
        <div class="empty-state">
            <h2>No movies found</h2>
            <p>Try a different filter or browse all movies on Kun.</p>
            <a href="{{ route('movies.index') }}" class="btn-stream-primary">Browse All Movies</a>
        </div>
        @endif
    </div>
</div>

@guest
<div class="auth-modal" id="authModal" hidden>
    <div class="auth-modal-backdrop" data-close-modal></div>
    <div class="auth-modal-content">
        <button type="button" class="auth-modal-close" data-close-modal>&times;</button>
        <h2>Start watching on Kun</h2>
        <p>Login or sign up to stream this movie online.</p>
        <div class="auth-modal-actions">
            <a href="{{ route('login') }}" class="btn-stream-primary" id="modalLoginBtn">Login</a>
            <a href="{{ route('register') }}" class="btn-stream-outline">Sign Up</a>
        </div>
    </div>
</div>
@endguest

<style>
.browse-page { background: #000; min-height: 80vh; padding-bottom: 3rem; }
.filters-bar { background: #141414; border-bottom: 1px solid #222; padding: 1rem 0; margin-bottom: 2rem; }
.filters-form { display: flex; gap: 1rem; flex-wrap: wrap; }
.filters-form select {
    padding: 0.6rem 1rem; background: #1f1f1f; border: 1px solid #333;
    border-radius: 8px; color: #fff; font-size: 0.9rem;
}
.browse-grid-section { padding-bottom: 2rem; }
.kun-stream-grid {
    display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem;
}
.kun-stream-card {
    background: #141414; border-radius: 14px; overflow: hidden;
    border: 1px solid #222; transition: transform 0.25s;
}
.kun-stream-card:hover { transform: translateY(-4px); }
.kun-stream-poster { position: relative; aspect-ratio: 2/3; overflow: hidden; }
.kun-stream-poster img { width: 100%; height: 100%; object-fit: cover; }
.rating-pill {
    position: absolute; top: 12px; left: 12px;
    background: rgba(0,0,0,0.75); color: #fff;
    padding: 0.35rem 0.65rem; border-radius: 8px; font-size: 0.8rem;
    display: flex; align-items: center; gap: 0.35rem;
}
.rating-pill i { color: #ffc107; }
.kun-stream-poster .like-btn {
    position: absolute; top: 12px; right: 12px;
    width: 34px; height: 34px; border-radius: 50%; border: none;
    background: rgba(0,0,0,0.7); color: #fff; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
}
.kun-stream-poster .like-btn.liked { background: #fff; color: #e50914; }
.kun-stream-body { padding: 1rem; }
.kun-stream-title { font-size: 1rem; font-weight: 700; margin: 0 0 0.5rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.kun-stream-title a { color: #fff; text-decoration: none; }
.kun-stream-meta { display: flex; gap: 0.5rem; font-size: 0.82rem; margin-bottom: 0.4rem; flex-wrap: wrap; }
.genre-text { color: #e50914; }
.meta-item { color: #999; }
.kun-stream-language { font-size: 0.82rem; color: #888; margin-bottom: 1rem; }
.watch-now-btn {
    display: flex; align-items: center; justify-content: center; gap: 0.5rem;
    width: 100%; padding: 0.75rem; background: #e50914; color: #fff;
    border: none; border-radius: 8px; font-weight: 700; text-decoration: none; cursor: pointer;
}
.watch-now-btn:hover { background: #f40612; color: #fff; }
.pagination-wrap { margin-top: 2rem; }
.empty-state { text-align: center; padding: 4rem 0; color: #888; }
.empty-state h2 { color: #fff; margin-bottom: 0.5rem; }
@media (max-width: 1200px) { .kun-stream-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 768px) { .kun-stream-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 480px) { .kun-stream-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};
    const loginUrl = @json(route('login'));
    const authModal = document.getElementById('authModal');
    const modalLoginBtn = document.getElementById('modalLoginBtn');

    function openAuthModal() {
        if (!authModal) return;
        authModal.hidden = false;
        if (modalLoginBtn) modalLoginBtn.href = loginUrl + '?redirect=' + encodeURIComponent(window.location.pathname);
    }
    document.querySelectorAll('[data-close-modal]').forEach(el => el.addEventListener('click', () => { if(authModal) authModal.hidden = true; }));
    document.querySelectorAll('.guest-action-btn').forEach(btn => btn.addEventListener('click', e => { e.preventDefault(); openAuthModal(); }));
    document.querySelectorAll('.like-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault(); e.stopPropagation();
            if (!isLoggedIn) { openAuthModal(); return; }
            fetch(@json(route('favorites.toggle')), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                body: JSON.stringify({ movie_id: this.dataset.movieId }),
            }).then(r => r.json()).then(data => { if (data) this.classList.toggle('liked', data.favorited); });
        });
    });
});
</script>
@endsection
