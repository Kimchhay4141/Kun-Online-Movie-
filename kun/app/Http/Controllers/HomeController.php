<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Genre;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the homepage with featured movies
     * No authentication required - public homepage
     */
    public function index()
    {
        try {
            $displayableStatuses = ['published', 'coming_soon'];

            // Get featured movie
            $featured = Movie::with('genres')
                ->where('status', 'published')
                ->where('is_featured', true)
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$featured) {
                $featured = Movie::with('genres')
                    ->where('status', 'published')
                    ->orderBy('rating', 'desc')
                    ->first();
            }

            // Get trending movies (high views in last 7 days)
            $trending = Movie::with('genres')
                ->where('status', 'published')
                ->withCount(['movieViews as recent_views' => function($query) {
                    $query->where('created_at', '>=', now()->subDays(7));
                }])
                ->orderBy('recent_views', 'desc')
                ->take(10)
                ->get();

            // Get new releases
            $newReleases = Movie::with('genres')
                ->where('status', 'published')
                ->latest()
                ->take(10)
                ->get();

            // Get popular movies
            $popular = Movie::with('genres')
                ->where('status', 'published')
                ->orderBy('view_count', 'desc')
                ->take(10)
                ->get();

            // Get every movie that should be visible on the public homepage
            $allMovies = Movie::with('genres')
                ->whereIn('status', $displayableStatuses)
                ->orderByRaw("CASE WHEN status = 'published' THEN 0 ELSE 1 END")
                ->orderByDesc('is_featured')
                ->orderByDesc('published_at')
                ->orderByDesc('created_at')
                ->get();

            // Get genres
            $genres = Genre::where('is_active', true)
                ->withCount('movies')
                ->orderBy('sort_order')
                ->get();

            // Get user-specific data if authenticated
            $continueWatching = collect();
            $favoriteMovieIds = collect();
            
            if (auth()->check()) {
                $continueWatching = auth()->user()
                    ->movieViews()
                    ->with('movie.genres')
                    ->whereHas('movie', function($query) {
                        $query->where('status', 'published');
                    })
                    ->orderBy('updated_at', 'desc')
                    ->take(8)
                    ->get()
                    ->pluck('movie')
                    ->filter();

                $favoriteMovieIds = auth()->user()
                    ->favorites()
                    ->pluck('movie_id');
            }

            return view('home', compact(
                'featured',
                'trending',
                'newReleases',
                'popular',
                'allMovies',
                'genres',
                'continueWatching',
                'favoriteMovieIds'
            ));
        } catch (\Exception $e) {
            \Log::error('HomeController@index error: ' . $e->getMessage());
            
            // Return with empty collections if there's an error
            return view('home', [
                'featured' => null,
                'trending' => collect(),
                'newReleases' => collect(),
                'popular' => collect(),
                'allMovies' => collect(),
                'genres' => collect(),
                'continueWatching' => collect(),
                'favoriteMovieIds' => collect(),
                'loadError' => 'The catalog could not be loaded right now.'
            ]);
        }
    }
}
