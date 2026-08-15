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
        $popularMovies = Movie::withCount('movieViews as view_count')
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

        return view('admin.dashboard', compact(
            'stats',
            'popularMovies',
            'recentUsers',
            'recentPayments'
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
