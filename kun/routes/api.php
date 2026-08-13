<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\WatchController;
use App\Http\Controllers\MovieVideoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// ==========================================
// Public API Routes
// ==========================================

// Movies
Route::prefix('movies')->group(function () {
    // Get trending movies
    Route::get('/trending', [MovieController::class, 'trending']);
    
    // Get new releases
    Route::get('/new-releases', [MovieController::class, 'newReleases']);
    
    // Search movies
    Route::get('/search', [MovieController::class, 'search']);
    
    // Get movie by ID
    Route::get('/{id}', [MovieController::class, 'show']);
});

// Genres
Route::prefix('genres')->group(function () {
    // Get all genres
    Route::get('/', [GenreController::class, 'all']);
    
    // Get genre by slug
    Route::get('/{slug}', [GenreController::class, 'show']);
});

// ==========================================
// Protected API Routes (Require Authentication)
// ==========================================

Route::middleware(['auth:sanctum'])->group(function () {
    
    // User Information
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Favorites API
    Route::prefix('favorites')->group(function () {
        // Get user favorites
        Route::get('/', [FavoriteController::class, 'index']);
        
        // Toggle favorite
        Route::post('/toggle', [FavoriteController::class, 'toggle']);
        
        // Add to favorites
        Route::post('/', [FavoriteController::class, 'store']);
        
        // Remove from favorites
        Route::delete('/{movieId}', [FavoriteController::class, 'destroy']);
    });
    
    // Watchlist API
    Route::prefix('watchlist')->group(function () {
        // Get user watchlist
        Route::get('/', [WatchlistController::class, 'index']);
        
        // Toggle watchlist
        Route::post('/toggle', [WatchlistController::class, 'toggle']);
        
        // Add to watchlist
        Route::post('/', [WatchlistController::class, 'store']);
        
        // Remove from watchlist
        Route::delete('/{movieId}', [WatchlistController::class, 'destroy']);
    });
    
    // Watch Progress API
    Route::prefix('watch')->group(function () {
        // Update watch progress
        Route::post('/{id}/progress', [WatchController::class, 'updateProgress']);
        
        // Get watch history
        Route::get('/history', [WatchController::class, 'history']);
        
        // Get continue watching
        Route::get('/continue', [WatchController::class, 'continueWatching']);
        
        // Clear watch history
        Route::delete('/history/clear', [WatchController::class, 'clearHistory']);
    });
    
    // Video Streaming API
    Route::prefix('videos')->group(function () {
        // Get video stream
        Route::get('/{id}/stream', [MovieVideoController::class, 'stream']);
        
        // Get available qualities
        Route::get('/movie/{movieId}/qualities', [MovieVideoController::class, 'qualities']);
    });
    
    // User Statistics
    Route::get('/stats', function(Request $request) {
        $user = $request->user();
        return response()->json([
            'total_watched' => $user->movieViews()->count(),
            'favorites_count' => $user->favorites()->count(),
            'watchlist_count' => $user->watchlist()->count(),
            'total_watch_time' => $user->movieViews()->sum('current_time'),
        ]);
    });
});

// ==========================================
// Admin API Routes
// ==========================================

Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
    
    // Dashboard Statistics
    Route::get('/stats', function() {
        return response()->json([
            'total_movies' => \App\Models\Movie::count(),
            'total_users' => \App\Models\User::count(),
            'total_views' => \App\Models\MovieView::count(),
            'total_revenue' => \App\Models\Payment::where('status', 'completed')->sum('amount'),
            'active_subscriptions' => \App\Models\User::where('subscription_status', 'active')->count(),
        ]);
    });
    
    // Movies Management
    Route::prefix('movies')->group(function () {
        Route::get('/', function() {
            return \App\Models\Movie::with('genres')->paginate(20);
        });
        
        Route::post('/', function(Request $request) {
            // Create movie logic
        });
        
        Route::put('/{id}', function(Request $request, $id) {
            // Update movie logic
        });
        
        Route::delete('/{id}', function($id) {
            // Delete movie logic
        });
        
        Route::patch('/{id}/status', function(Request $request, $id) {
            // Update movie status
        });
    });
    
    // Users Management
    Route::prefix('users')->group(function () {
        Route::get('/', function() {
            return \App\Models\User::with('roles')->paginate(20);
        });
        
        Route::get('/{id}', function($id) {
            return \App\Models\User::with(['roles', 'payments', 'movieViews'])->findOrFail($id);
        });
        
        Route::patch('/{id}/status', function(Request $request, $id) {
            // Update user status
        });
    });
    
    // Analytics
    Route::prefix('analytics')->group(function () {
        Route::get('/views', function() {
            // Get views analytics
            return response()->json([
                'daily_views' => [120, 190, 300, 500, 420, 600],
                'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
            ]);
        });
        
        Route::get('/revenue', function() {
            // Get revenue analytics
            return response()->json([
                'monthly_revenue' => [3500, 7500, 12000, 9500, 11000, 15000],
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']
            ]);
        });
        
        Route::get('/popular-movies', function() {
            return \App\Models\Movie::orderBy('view_count', 'desc')->take(10)->get();
        });
        
        Route::get('/popular-genres', function() {
            return \App\Models\Genre::withCount('movies')->orderBy('movies_count', 'desc')->get();
        });
    });
});

// ==========================================
// Webhook Routes (No Authentication)
// ==========================================

Route::prefix('webhooks')->group(function () {
    // Payment webhook (Stripe, PayPal, etc.)
    Route::post('/payment', function(Request $request) {
        // Handle payment webhook
        return response()->json(['received' => true]);
    });
    
    // Video processing webhook
    Route::post('/video-processed', function(Request $request) {
        // Handle video processing completion
        return response()->json(['received' => true]);
    });
});

// ==========================================
// Health Check
// ==========================================

Route::get('/health', function() {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now()->toISOString(),
        'service' => 'Kun Movie API',
        'version' => '1.0.0'
    ]);
});
