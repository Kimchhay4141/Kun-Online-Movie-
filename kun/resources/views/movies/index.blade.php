@extends('layouts.app')

@section('title', 'Browse Movies - Kun')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/movies.css') }}">
@endsection

@section('content')
<div class="movies-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title">
                <i class="fas fa-film"></i>
                {{ isset($genre) ? $genre->name . ' Movies' : 'Browse Movies' }}
            </h1>
            <p class="page-subtitle">{{ $movies->total() }} movies available</p>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section">
        <div class="container">
            <form action="{{ route('movies.index') }}" method="GET" class="filters-form">
                <!-- Genre Filter -->
                <div class="filter-group">
                    <label>
                        <i class="fas fa-theater-masks"></i>
                        Genre
                    </label>
                    <select name="genre" onchange="this.form.submit()">
                        <option value="">All Genres</option>
                        @foreach($genres as $g)
                        <option value="{{ $g->slug }}" {{ request('genre') == $g->slug ? 'selected' : '' }}>
                            {{ $g->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Year Filter -->
                <div class="filter-group">
                    <label>
                        <i class="fas fa-calendar"></i>
                        Year
                    </label>
                    <select name="year" onchange="this.form.submit()">
                        <option value="">All Years</option>
                        @for($y = date('Y'); $y >= 1990; $y--)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                        @endfor
                    </select>
                </div>

                <!-- Rating Filter -->
                <div class="filter-group">
                    <label>
                        <i class="fas fa-star"></i>
                        Min Rating
                    </label>
                    <select name="rating" onchange="this.form.submit()">
                        <option value="">Any Rating</option>
                        <option value="7" {{ request('rating') == 7 ? 'selected' : '' }}>7.0+</option>
                        <option value="8" {{ request('rating') == 8 ? 'selected' : '' }}>8.0+</option>
                        <option value="9" {{ request('rating') == 9 ? 'selected' : '' }}>9.0+</option>
                    </select>
                </div>

                <!-- Sort Filter -->
                <div class="filter-group">
                    <label>
                        <i class="fas fa-sort"></i>
                        Sort By
                    </label>
                    <select name="sort" onchange="this.form.submit()">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                        <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title (A-Z)</option>
                    </select>
                </div>

                <!-- Clear Filters -->
                @if(request()->hasAny(['genre', 'year', 'rating', 'sort']))
                <a href="{{ route('movies.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-times"></i>
                    Clear
                </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Movies Grid -->
    <div class="movies-grid-section">
        <div class="container">
            @if($movies->count() > 0)
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
                {{ $movies->links() }}
            </div>
            @else
            <div class="no-results">
                <i class="fas fa-film"></i>
                <h3>No movies found</h3>
                <p>Try adjusting your filters</p>
                <a href="{{ route('movies.index') }}" class="btn btn-primary">
                    <i class="fas fa-refresh"></i>
                    Reset Filters
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/movies.js') }}"></script>
@endsection
