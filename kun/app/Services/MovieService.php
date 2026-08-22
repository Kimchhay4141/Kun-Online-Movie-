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
            $data['thumbnail'] = $this->uploadPoster($data['poster']);
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
            // Delete old poster from Supabase
            if ($movie->thumbnail) {
                $this->deletePoster($movie->thumbnail);
            }
            $data['thumbnail'] = $this->uploadPoster($data['poster']);
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
        // Delete poster from Supabase
        if ($movie->thumbnail) {
            $this->deletePoster($movie->thumbnail);
        }

        // Delete associated videos using VideoServiceV2
        foreach ($movie->videos as $video) {
            app(VideoServiceV2::class)->deleteVideo($video);
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
     * Upload poster image to Supabase Storage
     */
    protected function uploadPoster($file)
    {
        // Use Supabase REST API for upload
        $supabaseStorage = app(SupabaseStorageService::class);
        
        // Generate unique filename
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = 'posters/' . $filename;
        
        \Log::info('Uploading poster to Supabase:', [
            'path' => $path,
            'size' => $file->getSize(),
        ]);
        
        try {
            // Upload to Supabase posters bucket
            $result = $supabaseStorage->upload($file, $path, 'posters');
            
            \Log::info('Poster uploaded successfully:', [
                'url' => $result['url'],
            ]);
            
            return $result['url'];
        } catch (\Exception $e) {
            \Log::error('Failed to upload poster:', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Delete poster from Supabase Storage
     */
    protected function deletePoster($posterUrl)
    {
        if (!$posterUrl) {
            return;
        }

        // Check if it's a Supabase URL
        if (!str_contains($posterUrl, env('SUPABASE_URL'))) {
            return;
        }

        try {
            $supabaseStorage = app(SupabaseStorageService::class);
            
            // Extract path from URL
            $urlParts = parse_url($posterUrl);
            $path = $urlParts['path'] ?? '';
            // Remove /storage/v1/object/public/posters/ prefix
            $path = preg_replace('#^/storage/v1/object/public/posters/#', '', $path);
            
            if ($path) {
                $supabaseStorage->delete($path, 'posters');
                \Log::info('Poster deleted from Supabase:', ['path' => $path]);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to delete poster:', [
                'error' => $e->getMessage(),
                'url' => $posterUrl,
            ]);
        }
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
