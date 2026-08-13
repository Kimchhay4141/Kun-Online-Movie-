<?php

namespace App\Services;

use App\Models\Movie;
use App\Models\MovieView;
use App\Models\User;

class WatchService
{
    /**
     * Record movie watch
     */
    public function recordWatch(User $user, Movie $movie)
    {
        $movieView = MovieView::firstOrCreate(
            [
                'user_id' => $user->id,
                'movie_id' => $movie->id,
            ],
            [
                'progress' => 0,
                'watch_count' => 0,
                'first_watched_at' => now(),
            ]
        );

        // Increment watch count
        $movieView->increment('watch_count');
        $movieView->update(['last_watched_at' => now()]);

        // Increment movie view count
        $movie->increment('view_count');

        return $movieView;
    }

    /**
     * Update watch progress
     */
    public function updateProgress(User $user, Movie $movie, float $progress, int $currentTime)
    {
        $movieView = MovieView::updateOrCreate(
            [
                'user_id' => $user->id,
                'movie_id' => $movie->id,
            ],
            [
                'progress' => min($progress, 100),
                'current_time' => $currentTime,
                'last_watched_at' => now(),
            ]
        );

        return $movieView;
    }

    /**
     * Get watch progress for a movie
     */
    public function getProgress(User $user, Movie $movie)
    {
        $movieView = MovieView::where('user_id', $user->id)
            ->where('movie_id', $movie->id)
            ->first();

        return $movieView ? [
            'progress' => $movieView->progress,
            'current_time' => $movieView->current_time,
            'last_watched_at' => $movieView->last_watched_at,
        ] : null;
    }

    /**
     * Get continue watching list
     */
    public function getContinueWatching(User $user, $limit = 20)
    {
        return MovieView::with('movie.genres')
            ->where('user_id', $user->id)
            ->where('progress', '>', 0)
            ->where('progress', '<', 95) // Not completed
            ->orderBy('last_watched_at', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Get watch history
     */
    public function getWatchHistory(User $user, $limit = null)
    {
        $query = MovieView::with('movie.genres')
            ->where('user_id', $user->id)
            ->orderBy('last_watched_at', 'desc');

        return $limit ? $query->take($limit)->get() : $query->paginate(20);
    }

    /**
     * Clear watch history
     */
    public function clearHistory(User $user)
    {
        return MovieView::where('user_id', $user->id)->delete();
    }

    /**
     * Remove single watch record
     */
    public function removeFromHistory(User $user, Movie $movie)
    {
        return MovieView::where('user_id', $user->id)
            ->where('movie_id', $movie->id)
            ->delete();
    }

    /**
     * Mark movie as completed
     */
    public function markAsCompleted(User $user, Movie $movie)
    {
        return MovieView::updateOrCreate(
            [
                'user_id' => $user->id,
                'movie_id' => $movie->id,
            ],
            [
                'progress' => 100,
                'completed_at' => now(),
                'last_watched_at' => now(),
            ]
        );
    }

    /**
     * Get completed movies
     */
    public function getCompletedMovies(User $user, $limit = null)
    {
        $query = MovieView::with('movie.genres')
            ->where('user_id', $user->id)
            ->where('progress', '>=', 95)
            ->orderBy('completed_at', 'desc');

        return $limit ? $query->take($limit)->get() : $query->paginate(20);
    }

    /**
     * Get watch statistics for user
     */
    public function getUserWatchStats(User $user)
    {
        $movieViews = MovieView::where('user_id', $user->id)->get();

        return [
            'total_watched' => $movieViews->count(),
            'completed' => $movieViews->where('progress', '>=', 95)->count(),
            'in_progress' => $movieViews->where('progress', '>', 0)->where('progress', '<', 95)->count(),
            'total_watch_time' => $movieViews->sum('current_time'),
            'avg_progress' => $movieViews->avg('progress'),
            'favorite_genres' => $this->getFavoriteGenres($user),
            'most_watched' => $movieViews->sortByDesc('watch_count')->take(5),
        ];
    }

    /**
     * Get user's favorite genres based on watch history
     */
    public function getFavoriteGenres(User $user)
    {
        $watchedMovies = MovieView::with('movie.genres')
            ->where('user_id', $user->id)
            ->get();

        $genres = [];
        foreach ($watchedMovies as $view) {
            foreach ($view->movie->genres as $genre) {
                $genreName = $genre->name;
                if (!isset($genres[$genreName])) {
                    $genres[$genreName] = 0;
                }
                $genres[$genreName]++;
            }
        }

        arsort($genres);
        return array_slice($genres, 0, 5, true);
    }

    /**
     * Check if user has access to watch movie
     */
    public function hasAccess(User $user, Movie $movie)
    {
        // Check if movie requires premium subscription
        if ($movie->is_premium) {
            return $user->isPremium();
        }

        // Check if movie has any other access restrictions
        // Add more logic here as needed

        return true;
    }

    /**
     * Get recommended next movie
     */
    public function getNextRecommendation(User $user, Movie $currentMovie)
    {
        // Get similar movies based on genres
        $genreIds = $currentMovie->genres->pluck('id');

        return Movie::with('genres')
            ->where('status', 'published')
            ->where('id', '!=', $currentMovie->id)
            ->whereHas('genres', function($query) use ($genreIds) {
                $query->whereIn('genres.id', $genreIds);
            })
            ->whereNotIn('id', function($query) use ($user) {
                $query->select('movie_id')
                    ->from('movie_views')
                    ->where('user_id', $user->id)
                    ->where('progress', '>=', 95);
            })
            ->orderBy('rating', 'desc')
            ->first();
    }

    /**
     * Get watch statistics for admin
     */
    public function getGlobalWatchStats()
    {
        return [
            'total_views' => MovieView::count(),
            'unique_viewers' => MovieView::distinct('user_id')->count(),
            'total_watch_time' => MovieView::sum('current_time'),
            'avg_completion_rate' => MovieView::avg('progress'),
            'most_watched_movies' => Movie::orderBy('view_count', 'desc')->take(10)->get(),
            'most_completed_movies' => MovieView::selectRaw('movie_id, COUNT(*) as completions')
                ->where('progress', '>=', 95)
                ->groupBy('movie_id')
                ->orderBy('completions', 'desc')
                ->take(10)
                ->with('movie')
                ->get(),
        ];
    }
}
