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
        // Get trending movies (most viewed)
        $trending = Movie::with('genres')
            ->where('status', 'published')
            ->orderBy('view_count', 'desc')
            ->take(10)
            ->get();

        // Get new releases (by year and created date)
        $newReleases = Movie::with('genres')
            ->where('status', 'published')
            ->orderBy('release_year', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Get popular movies (highest rated)
        $popular = Movie::with('genres')
            ->where('status', 'published')
            ->orderBy('rating', 'desc')
            ->take(10)
            ->get();

        // Get featured movie for hero section
        $featured = Movie::with('genres')
            ->where('status', 'published')
            ->where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->first();

        // If no featured movie, get highest rated
        if (!$featured) {
            $featured = Movie::with('genres')
                ->where('status', 'published')
                ->orderBy('rating', 'desc')
                ->first();
        }

        // Get all active genres with movie counts
        $genres = Genre::where('is_active', true)
            ->withCount('movies')
            ->orderBy('sort_order')
            ->get();

        // Get movies by genre (if genres exist)
        $actionMovies = Movie::with('genres')
            ->whereHas('genres', function($query) {
                $query->where('slug', 'action');
            })
            ->where('status', 'published')
            ->take(10)
            ->get();

        $comedyMovies = Movie::with('genres')
            ->whereHas('genres', function($query) {
                $query->where('slug', 'comedy');
            })
            ->where('status', 'published')
            ->take(10)
            ->get();

        $horrorMovies = Movie::with('genres')
            ->whereHas('genres', function($query) {
                $query->where('slug', 'horror');
            })
            ->where('status', 'published')
            ->take(10)
            ->get();

        // Get continue watching for authenticated users only
        $continueWatching = collect();
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
                ->filter(); // Remove nulls
        }

        return view('home', compact(
            'trending',
            'newReleases',
            'popular',
            'featured',
            'genres',
            'actionMovies',
            'comedyMovies',
            'horrorMovies',
            'continueWatching'
        ));
    }
}
