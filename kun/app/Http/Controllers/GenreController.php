<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * Display all genres
     */
    public function index()
    {
        $genres = Genre::withCount('movies')
            ->orderBy('name', 'asc')
            ->get();

        return view('genres.index', compact('genres'));
    }

    /**
     * Display movies in a specific genre
     */
    public function show($slug)
    {
        $genre = Genre::where('slug', $slug)->firstOrFail();
        
        $movies = $genre->movies()
            ->with('genres')
            ->where('status', 'published')
            ->orderBy('release_date', 'desc')
            ->paginate(20);

        return view('genres.show', compact('genre', 'movies'));
    }

    /**
     * Get all genres (API endpoint)
     */
    public function all()
    {
        $genres = Genre::withCount('movies')->get();
        return response()->json($genres);
    }
}
