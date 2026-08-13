<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Genre;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * Display a listing of movies
     */
    public function index(Request $request)
    {
        $query = Movie::with('genres')->where('status', 'published');

        // Filter by genre
        if ($request->has('genre')) {
            $query->whereHas('genres', function($q) use ($request) {
                $q->where('slug', $request->genre);
            });
        }

        // Filter by year
        if ($request->filled('year')) {
            $query->where('release_year', $request->year);
        }

        // Filter by rating
        if ($request->has('rating')) {
            $query->where('rating', '>=', $request->rating);
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'popular':
                $query->orderBy('view_count', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            default:
                $query->orderBy('release_year', 'desc')->orderBy('created_at', 'desc');
        }

        $movies = $query->paginate(20);
        $genres = Genre::all();
        $favoriteMovieIds = auth()->check()
            ? auth()->user()->favorites()->pluck('movie_id')
            : collect();

        return view('movies.index', compact('movies', 'genres', 'favoriteMovieIds'));
    }

    /**
     * Display a single movie
     */
    public function show($id)
    {
        $movie = Movie::with(['genres', 'videos', 'views'])
            ->whereIn('status', ['published', 'coming_soon'])
            ->findOrFail($id);

        // Increment view count
        $movie->increment('view_count');

        // Get related movies
        $relatedMovies = Movie::with('genres')
            ->where('status', 'published')
            ->where('id', '!=', $movie->id)
            ->whereHas('genres', function($query) use ($movie) {
                $query->whereIn('genres.id', $movie->genres->pluck('id'));
            })
            ->take(8)
            ->get();

        // Check if user has favorited or added to watchlist
        $isFavorited = false;
        $isInWatchlist = false;
        $userProgress = null;

        if (auth()->check()) {
            $isFavorited = auth()->user()->favorites()->where('movie_id', $movie->id)->exists();
            $isInWatchlist = auth()->user()->watchlist()->where('movie_id', $movie->id)->exists();
            
            $movieView = auth()->user()->movieViews()->where('movie_id', $movie->id)->first();
            $userProgress = $movieView ? $movieView->progress : 0;
        }

        return view('movies.show', compact(
            'movie',
            'relatedMovies',
            'isFavorited',
            'isInWatchlist',
            'userProgress'
        ));
    }

    /**
     * Search movies
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $movies = Movie::with('genres')
            ->where('status', 'published')
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhereHas('genres', function($subQ) use ($query) {
                      $subQ->where('name', 'like', "%{$query}%");
                  });
            })
            ->paginate(20);

        return view('movies.search', compact('movies', 'query'));
    }

    /**
     * Display movies by genre
     */
    public function byGenre($slug)
    {
        $genre = Genre::where('slug', $slug)->firstOrFail();
        
        $movies = $genre->movies()
            ->with('genres')
            ->where('status', 'published')
            ->orderBy('release_year', 'desc')
            ->paginate(20);

        $genres = Genre::all();
        $favoriteMovieIds = auth()->check()
            ? auth()->user()->favorites()->pluck('movie_id')
            : collect();

        return view('movies.index', compact('movies', 'genres', 'genre', 'favoriteMovieIds'));
    }

    /**
     * Get trending movies (API endpoint)
     */
    public function trending()
    {
        $movies = Movie::with('genres')
            ->where('status', 'published')
            ->orderBy('view_count', 'desc')
            ->take(10)
            ->get();

        return response()->json($movies);
    }

    /**
     * Get new releases (API endpoint)
     */
    public function newReleases()
    {
        $movies = Movie::with('genres')
            ->where('status', 'published')
            ->orderBy('release_year', 'desc')
            ->take(10)
            ->get();

        return response()->json($movies);
    }
}
