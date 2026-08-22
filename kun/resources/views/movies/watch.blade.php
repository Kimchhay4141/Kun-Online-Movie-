@extends('layouts.app')

@section('title', 'Watch ' . $movie->title . ' - Kun')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/movies.css') }}">
<style>
body { background: #000; }
.navbar { background: rgba(0,0,0,0.9); }
.footer { display: none; }
main { padding-top: 70px; min-height: 100vh; }
</style>
@endsection

@section('content')
<div class="watch-page">
    <!-- Video Player -->
    <div class="video-player-container">
        <div class="video-player" id="videoPlayer">
            <video id="mainVideo" controls autoplay>
                <source src="{{ $video->video_url }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>

            <!-- Custom Controls Overlay -->
            <div class="player-controls" id="playerControls">
                <div class="progress-bar-container">
                    <div class="progress-bar" id="progressBar">
                        <div class="progress-fill" id="progressFill"></div>
                        <div class="progress-handle" id="progressHandle"></div>
                    </div>
                    <span class="time-display">
                        <span id="currentTime">00:00</span> / <span id="duration">00:00</span>
                    </span>
                </div>

                <div class="control-buttons">
                    <div class="left-controls">
                        <button class="control-btn" id="playPauseBtn">
                            <i class="fas fa-pause"></i>
                        </button>
                        <button class="control-btn" id="rewindBtn">
                            <i class="fas fa-backward"></i>
                            <span>10s</span>
                        </button>
                        <button class="control-btn" id="forwardBtn">
                            <i class="fas fa-forward"></i>
                            <span>10s</span>
                        </button>
                        <div class="volume-control">
                            <button class="control-btn" id="volumeBtn">
                                <i class="fas fa-volume-up"></i>
                            </button>
                            <input type="range" class="volume-slider" id="volumeSlider" min="0" max="100" value="100">
                        </div>
                    </div>

                    <div class="center-info">
                        <h3 class="video-title">{{ $movie->title }}</h3>
                    </div>

                    <div class="right-controls">
                        <div class="quality-selector">
                            <button class="control-btn" id="qualityBtn">
                                <i class="fas fa-cog"></i>
                                <span>{{ $video->quality }}</span>
                            </button>
                            <div class="quality-menu" id="qualityMenu">
                                @foreach($qualities as $id => $quality)
                                <button class="quality-option" data-video-id="{{ $id }}">
                                    {{ $quality }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                        <button class="control-btn" id="fullscreenBtn">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Loading Spinner -->
            <div class="loading-spinner" id="loadingSpinner">
                <i class="fas fa-circle-notch fa-spin"></i>
            </div>

            <!-- Click to Play Overlay -->
            <div class="play-overlay" id="playOverlay">
                <button class="big-play-btn">
                    <i class="fas fa-play"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Movie Info Below Player -->
    <div class="watch-info">
        <div class="container">
            <div class="info-grid">
                <div class="info-main">
                    <h1>{{ $movie->title }}</h1>
                    <div class="movie-meta">
                        @if($movie->rating)
                        <span><i class="fas fa-star"></i> {{ number_format($movie->rating, 1) }}</span>
                        @endif
                        <span>{{ $movie->release_date ? $movie->release_date->format('Y') : '' }}</span>
                        <span>{{ $movie->duration }} min</span>
                    </div>
                    <p>{{ $movie->description }}</p>
                    
                    @if($movie->genres->count() > 0)
                    <div class="genres">
                        @foreach($movie->genres as $genre)
                        <span class="genre-tag">{{ $genre->name }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>

                <div class="info-sidebar">
                    @if($nextMovie)
                    <div class="next-movie">
                        <h3>Up Next</h3>
                        <a href="{{ route('movie.watch', $nextMovie->id) }}" class="next-movie-card">
                            <img src="{{ $nextMovie->poster_url }}" alt="{{ $nextMovie->title }}">
                            <div class="next-movie-info">
                                <h4>{{ $nextMovie->title }}</h4>
                                <span>{{ $nextMovie->duration }} min</span>
                            </div>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const video = document.getElementById('mainVideo');
const progressFill = document.getElementById('progressFill');
const currentTimeDisplay = document.getElementById('currentTime');
const durationDisplay = document.getElementById('duration');
const playPauseBtn = document.getElementById('playPauseBtn');

// Format time
function formatTime(seconds) {
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
}

// Update progress
video.addEventListener('timeupdate', () => {
    const progress = (video.currentTime / video.duration) * 100;
    progressFill.style.width = progress + '%';
    currentTimeDisplay.textContent = formatTime(video.currentTime);
    
    // Save progress every 10 seconds
    if (Math.floor(video.currentTime) % 10 === 0) {
        saveProgress();
    }
});

video.addEventListener('loadedmetadata', () => {
    durationDisplay.textContent = formatTime(video.duration);
});

// Play/Pause
playPauseBtn.addEventListener('click', () => {
    if (video.paused) {
        video.play();
        playPauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
    } else {
        video.pause();
        playPauseBtn.innerHTML = '<i class="fas fa-play"></i>';
    }
});

// Rewind/Forward
document.getElementById('rewindBtn').addEventListener('click', () => {
    video.currentTime = Math.max(0, video.currentTime - 10);
});

document.getElementById('forwardBtn').addEventListener('click', () => {
    video.currentTime = Math.min(video.duration, video.currentTime + 10);
});

// Volume
const volumeSlider = document.getElementById('volumeSlider');
volumeSlider.addEventListener('input', (e) => {
    video.volume = e.target.value / 100;
});

// Fullscreen
document.getElementById('fullscreenBtn').addEventListener('click', () => {
    if (document.fullscreenElement) {
        document.exitFullscreen();
    } else {
        document.getElementById('videoPlayer').requestFullscreen();
    }
});

// Save progress to server
function saveProgress() {
    const progress = (video.currentTime / video.duration) * 100;
    fetch('/api/watch/{{ $movie->id }}/progress', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            progress: progress,
            current_time: video.currentTime
        })
    });
}

// Resume from saved progress
@if($movieView && $movieView->current_time)
video.currentTime = {{ $movieView->current_time }};
@endif

// Keyboard shortcuts
document.addEventListener('keydown', (e) => {
    switch(e.code) {
        case 'Space':
            e.preventDefault();
            playPauseBtn.click();
            break;
        case 'ArrowLeft':
            video.currentTime -= 5;
            break;
        case 'ArrowRight':
            video.currentTime += 5;
            break;
        case 'KeyF':
            document.getElementById('fullscreenBtn').click();
            break;
    }
});
</script>
@endsection
