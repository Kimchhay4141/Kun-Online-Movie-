@extends('layouts.app')

@section('title', 'Search Results for "' . $query . '" - Kun')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/movies.css') }}">
@endsection

@section('content')
<div class="search-page">
    <div class="container">
        <!-- Search Header -->
        <div class="search-header">
            <h1>Search Results for "{{ $query }}"</h1>
            <p class="search-count">{{ $movies->total() }} {{ Str::plural('result', $movies->total()) }} found</p>
            
            <!-- Search Form -->
            <form action="{{ route('movies.search') }}" method="GET" class="search-form-inline">
                <div class="search-input-group">
                    <i class="fas fa-search"></i>
                    <input type="text" 
                           name="q" 
                           value="{{ $query }}" 
                           placeholder="Search for movies..."
                           autofocus>
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>

        <!-- Results -->
        @if($movies->count() > 0)
        <div class="search-results">
            <div class="movies-grid">
                @foreach($movies as $movie)
                <x-movie-card :movie="[
                    'id' => $movie->id,
                    'title' => $movie->title,
                    'poster' => $movie->poster_url ?? 'https://via.placeholder.com/300x450/141414/e50914?text=No+Poster',
                    'rating' => $movie->rating,
                    'year' => $movie->release_date ? $movie->release_date->format('Y') : null,
                    'duration' => $movie->duration ? $movie->duration . ' min' : null,
                    'age_rating' => $movie->age_rating,
                    'quality' => $movie->quality,
                    'genres' => $movie->genres->pluck('name')->toArray()
                ]" />
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                {{ $movies->appends(['q' => $query])->links() }}
            </div>
        </div>
        @else
        <div class="no-results">
            <i class="fas fa-search"></i>
            <h2>No results found for "{{ $query }}"</h2>
            <p>Try different keywords or browse our collection</p>
            <div class="no-results-actions">
                <a href="{{ route('movies.index') }}" class="btn btn-primary">
                    <i class="fas fa-film"></i>
                    Browse All Movies
                </a>
                <a href="{{ route('home') }}" class="btn btn-secondary">
                    <i class="fas fa-home"></i>
                    Go Home
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
