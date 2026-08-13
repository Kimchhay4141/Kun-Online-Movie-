@extends('layouts.app')

@section('title', 'My Watchlist - Kun')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/user.css') }}">
@endsection

@section('content')
<div class="user-library-page">
    <div class="container">
        <div class="page-header">
            <h1>
                <i class="fas fa-list"></i>
                My Watchlist
            </h1>
            <p>{{ $watchlist->total() }} {{ Str::plural('movie', $watchlist->total()) }}</p>
        </div>

        @if($watchlist->count() > 0)
        <div class="movies-grid">
            @foreach($watchlist as $item)
            <x-movie-card :movie="[
                'id' => $item->movie->id,
                'title' => $item->movie->title,
                'poster' => $item->movie->poster_url,
                'rating' => $item->movie->rating,
                'year' => $item->movie->release_date ? $item->movie->release_date->format('Y') : null,
                'duration' => $item->movie->duration ? $item->movie->duration . ' min' : null,
                'genres' => $item->movie->genres->pluck('name')->toArray()
            ]" />
            @endforeach
        </div>

        <div class="pagination-wrapper">
            {{ $watchlist->links() }}
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-list"></i>
            <h2>Your watchlist is empty</h2>
            <p>Add movies you want to watch later</p>
            <a href="{{ route('movies.index') }}" class="btn btn-primary">
                <i class="fas fa-film"></i>
                Browse Movies
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
