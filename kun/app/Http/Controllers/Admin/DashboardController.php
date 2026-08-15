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
        // Calculate statistics (with safe fallbacks)
        $stats = [
            'total_movies' => Movie::count(),
            'total_users' => User::count(),
            'total_views' => $this->safeCount(MovieView::class),
            'total_revenue' => $this->safeSumPayments(),
            'active_subscriptions' => User::where('subscription_status', 'active')->count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
        ];

        // Get popular movies (top 10 by views)
        $popularMovies = $this->getPopularMovies();

        // Get recent users (last 5)
        $recentUsers = User::with('roles')
            ->latest()
            ->take(5)
            ->get();

        // Get recent payments (last 5) - optional
        $recentPayments = $this->getRecentPayments();

        return view('admin.dashboard', compact(
            'stats',
            'popularMovies',
            'recentUsers',
            'recentPayments'
        ));
    }

    /**
     * Safely count records from a model (returns 0 if table doesn't exist)
     */
    private function safeCount($modelClass)
    {
        try {
            return $modelClass::count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Safely sum payments (returns 0 if Payment table doesn't exist)
     */
    private function safeSumPayments()
    {
        try {
            if (class_exists(Payment::class)) {
                return Payment::where('status', 'completed')->sum('amount') ?? 0;
            }
        } catch (\Exception $e) {
            // Payment table doesn't exist yet
        }
        return 0;
    }

    /**
     * Get popular movies (handles missing MovieView relationship)
     */
    private function getPopularMovies()
    {
        try {
            return Movie::withCount('movieViews as view_count')
                ->orderBy('view_count', 'desc')
                ->take(10)
                ->get();
        } catch (\Exception $e) {
            // MovieView relationship doesn't exist, just return recent movies
            return Movie::latest()->take(10)->get();
        }
    }

    /**
     * Get recent payments (returns empty collection if Payment doesn't exist)
     */
    private function getRecentPayments()
    {
        try {
            if (class_exists(Payment::class)) {
                return Payment::with('user')
                    ->latest()
                    ->take(5)
                    ->get();
            }
        } catch (\Exception $e) {
            // Payment table doesn't exist yet
        }
        return collect(); // Return empty collection
    }

    /**
     * Refresh stats via AJAX
     */
    public function refreshStats()
    {
        $stats = [
            'total_movies' => Movie::count(),
            'total_users' => User::count(),
            'total_views' => $this->safeCount(MovieView::class),
            'total_revenue' => $this->safeSumPayments(),
        ];

        return response()->json($stats);
    }
}
