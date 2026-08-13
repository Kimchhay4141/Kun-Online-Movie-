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
        $nowShowing = Movie::with('genres')
            ->where('status', 'published')
            ->orderBy('view_count', 'desc')
            ->take(8)
            ->get();

        $comingSoon = Movie::with('genres')
            ->where('status', 'coming_soon')
            ->orderBy('release_date')
            ->take(8)
            ->get();

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

        $genres = Genre::where('is_active', true)
            ->withCount('movies')
            ->orderBy('sort_order')
            ->get();

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
            'nowShowing',
            'comingSoon',
            'featured',
            'genres',
            'continueWatching',
            'favoriteMovieIds'
        ));
    }
}
