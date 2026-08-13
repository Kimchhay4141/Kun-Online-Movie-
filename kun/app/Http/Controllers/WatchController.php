<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\MovieView;
use App\Models\MovieVideo;
use Illuminate\Http\Request;

class WatchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the video player page
     */
    public function show($id)
    {
        $movie = Movie::with(['genres', 'videos'])
            ->where('status', 'published')
            ->findOrFail($id);

        // Check if user has access (premium content check)
        if ($movie->is_premium && !auth()->user()->isPremium()) {
            return redirect()->route('movie.show', $movie->id)
                ->with('error', 'This movie requires a premium subscription.');
        }

        // Get or create movie view record
        $movieView = MovieView::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'movie_id' => $movie->id,
            ],
            [
                'progress' => 0,
                'watch_count' => 0,
            ]
        );

        // Increment watch count
        $movieView->increment('watch_count');

        // Get the primary video
        $video = $movie->videos()
            ->where('type', 'full')
            ->where('quality', 'HD')
            ->first() ?? $movie->videos()->first();

        if (!$video) {
            return redirect()->route('movie.show', $movie->id)
                ->with('error', 'Video not available for this movie.');
        }

        // Get available video qualities
        $qualities = $movie->videos()
            ->where('type', 'full')
            ->get()
            ->pluck('quality', 'id');

        // Get next episode/movie (for series or recommendations)
        $nextMovie = $this->getNextMovie($movie);

        return view('movies.watch', compact(
            'movie',
            'video',
            'qualities',
            'movieView',
            'nextMovie'
        ));
    }

    /**
     * Update watch progress
     */
    public function updateProgress(Request $request, $id)
    {
        $request->validate([
            'progress' => 'required|numeric|min:0|max:100',
            'current_time' => 'required|numeric|min:0',
        ]);

        $movieView = MovieView::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'movie_id' => $id,
            ],
            [
                'progress' => $request->progress,
                'current_time' => $request->current_time,
                'last_watched_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Progress saved',
            'progress' => $movieView->progress,
        ]);
    }

    /**
     * Get watch history
     */
    public function history()
    {
        $history = auth()->user()
            ->movieViews()
            ->with('movie.genres')
            ->orderBy('last_watched_at', 'desc')
            ->paginate(20);

        return view('user.history', compact('history'));
    }

    /**
     * Clear watch history
     */
    public function clearHistory()
    {
        auth()->user()->movieViews()->delete();

        return redirect()->back()->with('success', 'Watch history cleared!');
    }

    /**
     * Get continue watching movies
     */
    public function continueWatching()
    {
        $continueWatching = auth()->user()
            ->movieViews()
            ->with('movie.genres')
            ->where('progress', '>', 0)
            ->where('progress', '<', 100)
            ->orderBy('last_watched_at', 'desc')
            ->paginate(20);

        return view('user.continue-watching', compact('continueWatching'));
    }

    /**
     * Get next recommended movie
     */
    protected function getNextMovie($currentMovie)
    {
        return Movie::with('genres')
            ->where('status', 'published')
            ->where('id', '!=', $currentMovie->id)
            ->whereHas('genres', function($query) use ($currentMovie) {
                $query->whereIn('genres.id', $currentMovie->genres->pluck('id'));
            })
            ->inRandomOrder()
            ->first();
    }
}
