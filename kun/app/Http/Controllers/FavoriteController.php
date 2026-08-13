<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display user's favorite movies
     */
    public function index()
    {
        $favorites = auth()->user()
            ->favorites()
            ->with('movie.genres')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('user.favorites', compact('favorites'));
    }

    /**
     * Add movie to favorites
     */
    public function store(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
        ]);

        $favorite = Favorite::firstOrCreate([
            'user_id' => auth()->id(),
            'movie_id' => $request->movie_id,
        ]);

        if ($favorite->wasRecentlyCreated) {
            return response()->json([
                'success' => true,
                'message' => 'Movie added to favorites!',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Movie already in favorites.',
        ], 409);
    }

    /**
     * Remove movie from favorites
     */
    public function destroy($movieId)
    {
        $deleted = Favorite::where('user_id', auth()->id())
            ->where('movie_id', $movieId)
            ->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Movie removed from favorites!',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Movie not found in favorites.',
        ], 404);
    }

    /**
     * Toggle favorite status
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
        ]);

        $favorite = Favorite::where('user_id', auth()->id())
            ->where('movie_id', $request->movie_id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json([
                'success' => true,
                'favorited' => false,
                'message' => 'Removed from favorites',
            ]);
        } else {
            Favorite::create([
                'user_id' => auth()->id(),
                'movie_id' => $request->movie_id,
            ]);
            return response()->json([
                'success' => true,
                'favorited' => true,
                'message' => 'Added to favorites',
            ]);
        }
    }
}
