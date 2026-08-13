@extends('layouts.app')

@section('title', 'My Favorites - Kun')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/user.css') }}">
@endsection

@section('content')
<div class="user-library-page">
    <div class="container">
        <div class="page-header">
            <h1>
                <i class="fas fa-heart"></i>
                My Favorites
            </h1>
            <p>{{ $favorites->total() }} {{ Str::plural('movie', $favorites->total()) }}</p>
        </div>

        @if($favorites->count() > 0)
        <div class="movies-grid">
            @foreach($favorites as $favorite)
            <x-movie-card :movie="[
                'id' => $favorite->movie->id,
                'title' => $favorite->movie->title,
                'poster' => $favorite->movie->poster_url,
                'rating' => $favorite->movie->rating,
                'year' => $favorite->movie->release_date ? $favorite->movie->release_date->format('Y') : null,
                'duration' => $favorite->movie->duration ? $favorite->movie->duration . ' min' : null,
                'genres' => $favorite->movie->genres->pluck('name')->toArray()
            ]" />
            @endforeach
        </div>

        <div class="pagination-wrapper">
            {{ $favorites->links() }}
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-heart"></i>
            <h2>No favorites yet</h2>
            <p>Start adding movies to your favorites</p>
            <a href="{{ route('movies.index') }}" class="btn btn-primary">
                <i class="fas fa-film"></i>
                Browse Movies
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
