<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\User;
use App\Models\Payment;
use App\Models\MovieView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard
     */
    public function index()
    {
        // Calculate statistics
        $stats = [
            'total_movies' => Movie::count(),
            'total_users' => User::count(),
            'total_views' => MovieView::count(),
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'active_subscriptions' => User::where('subscription_status', 'active')->count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
        ];

        // Get popular movies (top 10 by views)
        $popularMovies = Movie::with('genres')
            ->withCount('movieViews as view_count')
            ->orderBy('view_count', 'desc')
            ->take(10)
            ->get();

        // Get recent users (last 5)
        $recentUsers = User::with('roles')
            ->latest()
            ->take(5)
            ->get();

        // Get recent payments (last 5)
        $recentPayments = Payment::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Get trending movies (high views in last 7 days)
        $trending = Movie::with('genres')
            ->withCount(['movieViews as recent_views' => function($query) {
                $query->where('created_at', '>=', now()->subDays(7));
            }])
            ->orderBy('recent_views', 'desc')
            ->take(10)
            ->get();

        // Get new releases (last 10 movies)
        $newReleases = Movie::with('genres')
            ->where('status', 'published')
            ->latest()
            ->take(10)
            ->get();

        // Get popular movies for homepage (same as popularMovies for consistency)
        $popular = $popularMovies->take(10);

        // Get active genres with movie count
        $genres = DB::table('genres')
            ->leftJoin('genre_movie', 'genres.id', '=', 'genre_movie.genre_id')
            ->select('genres.*', DB::raw('COUNT(genre_movie.movie_id) as movies_count'))
            ->where('genres.is_active', true)
            ->groupBy('genres.id', 'genres.name', 'genres.slug', 'genres.description', 'genres.icon', 'genres.is_active', 'genres.sort_order', 'genres.created_at', 'genres.updated_at')
            ->orderBy('genres.sort_order')
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'popularMovies',
            'recentUsers',
            'recentPayments',
            'trending',
            'newReleases',
            'popular',
            'genres'
        ));
    }

    /**
     * Refresh stats via AJAX
     */
    public function refreshStats()
    {
        $stats = [
            'total_movies' => Movie::count(),
            'total_users' => User::count(),
            'total_views' => MovieView::count(),
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
        ];

        return response()->json($stats);
    }
}
