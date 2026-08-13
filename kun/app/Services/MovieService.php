<?php

namespace App\Services;

use App\Models\Movie;
use App\Models\Genre;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MovieService
{
    /**
     * Create a new movie
     */
    public function createMovie(array $data)
    {
        // Handle poster upload
        if (isset($data['poster'])) {
            $data['poster_path'] = $this->uploadPoster($data['poster']);
            unset($data['poster']);
        }

        // Generate slug
        $data['slug'] = Str::slug($data['title']);

        // Create movie
        $movie = Movie::create($data);

        // Attach genres
        if (isset($data['genres'])) {
            $movie->genres()->attach($data['genres']);
        }

        return $movie;
    }

    /**
     * Update an existing movie
     */
    public function updateMovie(Movie $movie, array $data)
    {
        // Handle poster upload
        if (isset($data['poster'])) {
            // Delete old poster
            if ($movie->poster_path) {
                Storage::disk('public')->delete($movie->poster_path);
            }
            $data['poster_path'] = $this->uploadPoster($data['poster']);
            unset($data['poster']);
        }

        // Update slug if title changed
        if (isset($data['title']) && $data['title'] !== $movie->title) {
            $data['slug'] = Str::slug($data['title']);
        }

        // Update movie
        $movie->update($data);

        // Sync genres
        if (isset($data['genres'])) {
            $movie->genres()->sync($data['genres']);
        }

        return $movie;
    }

    /**
     * Delete a movie
     */
    public function deleteMovie(Movie $movie)
    {
        // Delete poster
        if ($movie->poster_path) {
            Storage::disk('public')->delete($movie->poster_path);
        }

        // Delete associated videos
        foreach ($movie->videos as $video) {
            if ($video->file_path) {
                Storage::disk('public')->delete($video->file_path);
            }
        }

        // Delete movie
        $movie->delete();

        return true;
    }

    /**
     * Get recommended movies based on user's watch history
     */
    public function getRecommendations($user, $limit = 10)
    {
        // Get user's watched genres
        $watchedGenres = $user->movieViews()
            ->with('movie.genres')
            ->get()
            ->pluck('movie.genres')
            ->flatten()
            ->pluck('id')
            ->unique();

        if ($watchedGenres->isEmpty()) {
            // Return popular movies if no watch history
            return Movie::where('status', 'published')
                ->orderBy('view_count', 'desc')
                ->take($limit)
                ->get();
        }

        // Get movies with similar genres
        return Movie::with('genres')
            ->where('status', 'published')
            ->whereHas('genres', function($query) use ($watchedGenres) {
                $query->whereIn('genres.id', $watchedGenres);
            })
            ->whereNotIn('id', $user->movieViews()->pluck('movie_id'))
            ->orderBy('rating', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Get similar movies
     */
    public function getSimilarMovies(Movie $movie, $limit = 8)
    {
        $genreIds = $movie->genres->pluck('id');

        return Movie::with('genres')
            ->where('status', 'published')
            ->where('id', '!=', $movie->id)
            ->whereHas('genres', function($query) use ($genreIds) {
                $query->whereIn('genres.id', $genreIds);
            })
            ->orderBy('rating', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Search movies
     */
    public function searchMovies($query, $filters = [])
    {
        $movies = Movie::with('genres')->where('status', 'published');

        // Search in title and description
        if ($query) {
            $movies->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            });
        }

        // Filter by genre
        if (isset($filters['genre'])) {
            $movies->whereHas('genres', function($q) use ($filters) {
                $q->where('slug', $filters['genre']);
            });
        }

        // Filter by year
        if (isset($filters['year'])) {
            $movies->whereYear('release_date', $filters['year']);
        }

        // Filter by rating
        if (isset($filters['min_rating'])) {
            $movies->where('rating', '>=', $filters['min_rating']);
        }

        // Sort
        $sortBy = $filters['sort'] ?? 'latest';
        switch ($sortBy) {
            case 'popular':
                $movies->orderBy('view_count', 'desc');
                break;
            case 'rating':
                $movies->orderBy('rating', 'desc');
                break;
            case 'title':
                $movies->orderBy('title', 'asc');
                break;
            default:
                $movies->orderBy('release_date', 'desc');
        }

        return $movies->paginate(20);
    }

    /**
     * Upload poster image
     */
    protected function uploadPoster($file)
    {
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('movies/posters', $filename, 'public');
        return $path;
    }

    /**
     * Get trending movies
     */
    public function getTrending($period = 30, $limit = 10)
    {
        return Movie::with('genres')
            ->where('status', 'published')
            ->where('created_at', '>=', now()->subDays($period))
            ->orderBy('view_count', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Get movies by genre
     */
    public function getByGenre($genreSlug, $limit = null)
    {
        $query = Movie::with('genres')
            ->where('status', 'published')
            ->whereHas('genres', function($q) use ($genreSlug) {
                $q->where('slug', $genreSlug);
            })
            ->orderBy('release_date', 'desc');

        return $limit ? $query->take($limit)->get() : $query->paginate(20);
    }

    /**
     * Increment movie view count
     */
    public function incrementViewCount(Movie $movie)
    {
        $movie->increment('view_count');
    }

    /**
     * Get movie statistics
     */
    public function getStatistics()
    {
        return [
            'total_movies' => Movie::count(),
            'published_movies' => Movie::where('status', 'published')->count(),
            'draft_movies' => Movie::where('status', 'draft')->count(),
            'total_views' => Movie::sum('view_count'),
            'total_genres' => Genre::count(),
            'avg_rating' => Movie::avg('rating'),
            'most_viewed' => Movie::orderBy('view_count', 'desc')->take(5)->get(),
            'highest_rated' => Movie::orderBy('rating', 'desc')->take(5)->get(),
        ];
    }
}
