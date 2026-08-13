@props(['movie'])

<div class="movie-card" data-movie-id="{{ $movie['id'] ?? 'movie-' . rand(1, 1000) }}">
    <div class="movie-card-inner">
        <!-- Movie Poster -->
        <div class="movie-poster">
            <img src="{{ $movie['poster'] ?? 'https://via.placeholder.com/300x450/141414/e50914?text=No+Poster' }}" 
                 alt="{{ $movie['title'] ?? 'Movie Title' }}" 
                 loading="lazy">
            <div class="movie-overlay">
                <div class="movie-actions">
                    <button class="action-btn play-btn" title="Play">
                        <i class="fas fa-play"></i>
                    </button>
                    <button class="action-btn add-btn" title="Add to My List">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button class="action-btn like-btn" title="Like">
                        <i class="fas fa-thumbs-up"></i>
                    </button>
                    <button class="action-btn info-btn" title="More Info">
                        <i class="fas fa-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <!-- Quality Badge -->
            @if(isset($movie['quality']))
            <div class="quality-badge">{{ $movie['quality'] }}</div>
            @endif
            
            <!-- Rating Badge -->
            @if(isset($movie['rating']))
            <div class="rating-badge">
                <i class="fas fa-star"></i>
                <span>{{ $movie['rating'] }}</span>
            </div>
            @endif
        </div>
        
        <!-- Movie Info -->
        <div class="movie-info">
            <h3 class="movie-title">{{ $movie['title'] ?? 'Movie Title' }}</h3>
            
            <div class="movie-meta">
                @if(isset($movie['year']))
                <span class="meta-item">{{ $movie['year'] }}</span>
                @endif
                
                @if(isset($movie['duration']))
                <span class="meta-item">{{ $movie['duration'] }}</span>
                @endif
                
                @if(isset($movie['age_rating']))
                <span class="meta-item age-rating">{{ $movie['age_rating'] }}</span>
                @endif
            </div>
            
            @if(isset($movie['genres']) && is_array($movie['genres']))
            <div class="movie-genres">
                @foreach(array_slice($movie['genres'], 0, 3) as $genre)
                <span class="genre-tag">{{ $genre }}</span>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.movie-card {
    position: relative;
    cursor: pointer;
    transition: all 0.3s ease;
    height: 100%;
}

.movie-card-inner {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.movie-poster {
    position: relative;
    width: 100%;
    aspect-ratio: 2/3;
    border-radius: 12px;
    overflow: hidden;
    background: var(--light-bg);
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    transition: all 0.3s ease;
}

.movie-poster img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 0.4s ease;
}

.movie-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.9) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding: 20px;
}

.movie-actions {
    display: flex;
    gap: 10px;
    transform: translateY(20px);
    transition: transform 0.3s ease;
}

.action-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    transition: all 0.3s ease;
}

.action-btn:hover {
    background: var(--primary-color);
    border-color: var(--primary-color);
    transform: scale(1.1);
}

.action-btn.play-btn {
    background: var(--primary-color);
    border-color: var(--primary-color);
    width: 50px;
    height: 50px;
    font-size: 18px;
}

.action-btn.play-btn:hover {
    background: var(--secondary-color);
    transform: scale(1.15);
}

.quality-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(10px);
    color: white;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.rating-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    background: rgba(229, 9, 20, 0.9);
    backdrop-filter: blur(10px);
    color: white;
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 4px;
}

.rating-badge i {
    font-size: 11px;
}

.movie-info {
    padding: 15px 5px;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.movie-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-primary);
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    margin: 0;
}

.movie-meta {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.meta-item {
    color: var(--text-secondary);
    font-size: 13px;
    font-weight: 500;
}

.meta-item.age-rating {
    border: 1px solid var(--text-muted);
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
}

.movie-genres {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.genre-tag {
    background: rgba(229, 9, 20, 0.15);
    color: var(--text-secondary);
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 500;
    border: 1px solid rgba(229, 9, 20, 0.3);
}

/* Hover Effects */
.movie-card:hover .movie-poster {
    transform: scale(1.05);
    box-shadow: 0 8px 30px rgba(229, 9, 20, 0.3);
}

.movie-card:hover .movie-poster img {
    transform: scale(1.1);
}

.movie-card:hover .movie-overlay {
    opacity: 1;
}

.movie-card:hover .movie-actions {
    transform: translateY(0);
}

.movie-card:hover .movie-title {
    color: var(--primary-color);
}

/* Responsive */
@media (max-width: 768px) {
    .movie-title {
        font-size: 14px;
    }
    
    .action-btn {
        width: 35px;
        height: 35px;
        font-size: 14px;
    }
    
    .action-btn.play-btn {
        width: 45px;
        height: 45px;
        font-size: 16px;
    }
}

@media (max-width: 480px) {
    .movie-info {
        padding: 12px 2px;
    }
    
    .movie-title {
        font-size: 13px;
    }
    
    .meta-item {
        font-size: 12px;
    }
    
    .genre-tag {
        font-size: 10px;
        padding: 3px 8px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add to list functionality
    document.querySelectorAll('.add-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const icon = this.querySelector('i');
            if (icon.classList.contains('fa-plus')) {
                icon.classList.remove('fa-plus');
                icon.classList.add('fa-check');
                this.style.background = 'var(--success-color)';
            } else {
                icon.classList.remove('fa-check');
                icon.classList.add('fa-plus');
                this.style.background = 'rgba(255, 255, 255, 0.2)';
            }
        });
    });
    
    // Like functionality
    document.querySelectorAll('.like-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const icon = this.querySelector('i');
            if (icon.classList.contains('fa-thumbs-up')) {
                icon.classList.remove('fa-thumbs-up');
                icon.classList.add('fa-thumbs-down');
            } else {
                icon.classList.remove('fa-thumbs-down');
                icon.classList.add('fa-thumbs-up');
            }
        });
    });
    
    // Play button
    document.querySelectorAll('.play-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const movieId = this.closest('.movie-card').dataset.movieId;
            console.log('Playing movie:', movieId);
            // Add your play logic here
            window.location.href = '/movie/' + movieId + '/watch';
        });
    });
    
    // Info button
    document.querySelectorAll('.info-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const movieId = this.closest('.movie-card').dataset.movieId;
            console.log('Show info for movie:', movieId);
            // Add your info modal logic here
            window.location.href = '/movie/' + movieId;
        });
    });
    
    // Card click
    document.querySelectorAll('.movie-card').forEach(card => {
        card.addEventListener('click', function() {
            const movieId = this.dataset.movieId;
            window.location.href = '/movie/' + movieId;
        });
    });
});
</script>
