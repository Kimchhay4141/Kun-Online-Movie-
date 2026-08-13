@extends('layouts.app')

@section('title', 'Genres - Kun')

@section('content')
<div class="browse-page">
    <div class="stream-page-header">
        <div class="container">
            <h1><i class="fas fa-film"></i> Browse Genres</h1>
            <p>Find movies to stream on Kun by genre</p>
        </div>
    </div>

    <div class="container browse-grid-section">
        <div class="genres-grid-page">
            @foreach($genres as $genre)
            <a href="{{ route('movies.genre', $genre->slug) }}" class="genre-card-page">
                @if($genre->icon)<span class="genre-icon">{{ $genre->icon }}</span>@endif
                <h3>{{ $genre->name }}</h3>
                <p>{{ $genre->movies_count }} movies</p>
            </a>
            @endforeach
        </div>
    </div>
</div>

<style>
.browse-page { background: #000; min-height: 80vh; padding-bottom: 3rem; }
.genres-grid-page {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 1.25rem;
}
.genre-card-page {
    background: #141414;
    border: 1px solid #222;
    border-radius: 14px;
    padding: 2rem 1rem;
    text-align: center;
    color: #fff;
    text-decoration: none;
    transition: all 0.2s;
}
.genre-card-page:hover { border-color: #e50914; transform: translateY(-4px); }
.genre-card-page .genre-icon { font-size: 2rem; display: block; margin-bottom: 0.5rem; }
.genre-card-page h3 { font-size: 1.1rem; margin-bottom: 0.25rem; }
.genre-card-page p { color: #888; font-size: 0.85rem; }
</style>
@endsection
