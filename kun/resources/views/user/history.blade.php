@extends('layouts.app')

@section('title', 'Watch History - Kun')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/user.css') }}">
@endsection

@section('content')
<div class="user-library-page">
    <div class="container">
        <div class="page-header">
            <div>
                <h1>
                    <i class="fas fa-history"></i>
                    Watch History
                </h1>
                <p>{{ $history->total() }} {{ Str::plural('movie', $history->total()) }}</p>
            </div>
            @if($history->count() > 0)
            <button class="btn btn-danger" onclick="clearHistory()">
                <i class="fas fa-trash"></i>
                Clear History
            </button>
            @endif
        </div>

        @if($history->count() > 0)
        <div class="history-list">
            @foreach($history as $view)
            <div class="history-item">
                <a href="{{ route('movie.show', $view->movie->id) }}" class="history-poster">
                    <img src="{{ $view->movie->poster_url }}" alt="{{ $view->movie->title }}">
                    @if($view->progress > 0)
                    <div class="progress-overlay">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $view->progress }}%"></div>
                        </div>
                    </div>
                    @endif
                </a>
                
                <div class="history-info">
                    <h3>
                        <a href="{{ route('movie.show', $view->movie->id) }}">{{ $view->movie->title }}</a>
                    </h3>
                    <div class="history-meta">
                        <span><i class="fas fa-clock"></i> Last watched {{ $view->last_watched_at->diffForHumans() }}</span>
                        <span><i class="fas fa-eye"></i> Watched {{ $view->watch_count }} {{ Str::plural('time', $view->watch_count) }}</span>
                        @if($view->progress > 0)
                        <span><i class="fas fa-chart-line"></i> {{ number_format($view->progress) }}% complete</span>
                        @endif
                    </div>
                    <div class="history-genres">
                        @foreach($view->movie->genres as $genre)
                        <span class="genre-tag">{{ $genre->name }}</span>
                        @endforeach
                    </div>
                </div>

                <div class="history-actions">
                    @if($view->progress > 0 && $view->progress < 95)
                    <a href="{{ route('movie.watch', $view->movie->id) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-play"></i>
                        Continue
                    </a>
                    @else
                    <a href="{{ route('movie.watch', $view->movie->id) }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-redo"></i>
                        Watch Again
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <div class="pagination-wrapper">
            {{ $history->links() }}
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-history"></i>
            <h2>No watch history</h2>
            <p>Start watching movies to see your history here</p>
            <a href="{{ route('movies.index') }}" class="btn btn-primary">
                <i class="fas fa-film"></i>
                Browse Movies
            </a>
        </div>
        @endif
    </div>
</div>

<script>
function clearHistory() {
    if (confirm('Are you sure you want to clear your watch history? This action cannot be undone.')) {
        fetch('/watch/history/clear', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            alert('History cleared successfully!');
            location.reload();
        });
    }
}
</script>
@endsection
