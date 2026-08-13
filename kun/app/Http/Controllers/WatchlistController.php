<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Watchlist;
use Illuminate\Http\Request;

class WatchlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display user's watchlist
     */
    public function index()
    {
        $watchlist = auth()->user()
            ->watchlist()
            ->with('movie.genres')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('user.watchlist', compact('watchlist'));
    }

    /**
     * Add movie to watchlist
     */
    public function store(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
        ]);

        $watchlist = Watchlist::firstOrCreate([
            'user_id' => auth()->id(),
            'movie_id' => $request->movie_id,
        ]);

        if ($watchlist->wasRecentlyCreated) {
            return response()->json([
                'success' => true,
                'message' => 'Movie added to watchlist!',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Movie already in watchlist.',
        ], 409);
    }

    /**
     * Remove movie from watchlist
     */
    public function destroy($movieId)
    {
        $deleted = Watchlist::where('user_id', auth()->id())
            ->where('movie_id', $movieId)
            ->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Movie removed from watchlist!',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Movie not found in watchlist.',
        ], 404);
    }

    /**
     * Toggle watchlist status
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
        ]);

        $watchlist = Watchlist::where('user_id', auth()->id())
            ->where('movie_id', $request->movie_id)
            ->first();

        if ($watchlist) {
            $watchlist->delete();
            return response()->json([
                'success' => true,
                'in_watchlist' => false,
                'message' => 'Removed from watchlist',
            ]);
        } else {
            Watchlist::create([
                'user_id' => auth()->id(),
                'movie_id' => $request->movie_id,
            ]);
            return response()->json([
                'success' => true,
                'in_watchlist' => true,
                'message' => 'Added to watchlist',
            ]);
        }
    }
}
