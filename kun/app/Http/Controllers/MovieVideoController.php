<?php

namespace App\Http\Controllers;

use App\Models\MovieVideo;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieVideoController extends Controller
{
    /**
     * Get video stream URL
     */
    public function stream($id)
    {
        $video = MovieVideo::findOrFail($id);
        $movie = $video->movie;

        // Check if user has access
        if (!auth()->check()) {
            abort(401, 'Authentication required');
        }

        if ($movie->is_premium && !auth()->user()->isPremium()) {
            abort(403, 'Premium subscription required');
        }

        // Return video URL or stream
        return response()->json([
            'url' => $video->url,
            'type' => $video->type,
            'quality' => $video->quality,
        ]);
    }

    /**
     * Get available qualities for a movie
     */
    public function qualities($movieId)
    {
        $qualities = MovieVideo::where('movie_id', $movieId)
            ->where('type', 'full')
            ->get(['id', 'quality', 'size'])
            ->map(function($video) {
                return [
                    'id' => $video->id,
                    'quality' => $video->quality,
                    'label' => $video->quality . ' - ' . $this->formatSize($video->size),
                ];
            });

        return response()->json($qualities);
    }

    /**
     * Format file size
     */
    protected function formatSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
