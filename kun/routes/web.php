<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\WatchController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\MovieController as AdminMovieController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ==========================================
// Public Routes (Visitors can access)
// ==========================================

// Homepage - Public (Visitors can browse)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Browse Movies - Public
Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');

// Movie Detail - Public
Route::get('/movie/{id}', [MovieController::class, 'show'])->name('movie.show');

// Search Movies - Public
Route::get('/search', [MovieController::class, 'search'])->name('movies.search');

// Browse by Genre - Public
Route::get('/genre/{slug}', [MovieController::class, 'byGenre'])->name('movies.genre');

// Genres Page - Public
Route::get('/genres', [GenreController::class, 'index'])->name('genres.index');
Route::get('/genres/{slug}', [GenreController::class, 'show'])->name('genres.show');

// ==========================================
// Authentication Routes
// ==========================================

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Register
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Logout
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Password Reset
Route::get('/password/reset', function() {
    return view('auth.passwords.email');
})->name('password.request');

Route::post('/password/email', function() {
    // Password reset email logic
})->name('password.email');

// ==========================================
// Protected Routes (Require Authentication)
// ==========================================

Route::middleware(['auth'])->group(function () {
    
    // Watch Movie
    Route::get('/movie/{id}/watch', [WatchController::class, 'show'])->name('movie.watch');
    Route::post('/movie/{id}/progress', [WatchController::class, 'updateProgress'])->name('movie.progress');
    
    // Watch History
    Route::get('/history', [WatchController::class, 'history'])->name('watch.history');
    Route::delete('/history/clear', [WatchController::class, 'clearHistory'])->name('watch.history.clear');
    Route::get('/continue-watching', [WatchController::class, 'continueWatching'])->name('continue.watching');
    
    // Favorites
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::delete('/favorites/{movieId}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    
    // Watchlist (My List)
    Route::get('/my-list', [WatchlistController::class, 'index'])->name('watchlist.index');
    Route::post('/watchlist', [WatchlistController::class, 'store'])->name('watchlist.store');
    Route::delete('/watchlist/{movieId}', [WatchlistController::class, 'destroy'])->name('watchlist.destroy');
    
    // User Profile
    Route::get('/profile', function() {
        return view('user.profile');
    })->name('profile.show');
    
    Route::put('/profile', function() {
        // Update profile logic
    })->name('profile.update');
    
    Route::put('/profile/password', function() {
        // Update password logic
    })->name('profile.password');
    
    Route::put('/profile/preferences', function() {
        // Update preferences logic
    })->name('profile.preferences');
    
    // Subscription & Payment
    Route::get('/subscription/plans', [PaymentController::class, 'plans'])->name('subscription.plans');
    Route::get('/subscription/checkout', [PaymentController::class, 'checkout'])->name('subscription.checkout');
    Route::post('/subscription/process', [PaymentController::class, 'process'])->name('subscription.process');
    Route::get('/subscription/success', [PaymentController::class, 'success'])->name('subscription.success');
    Route::post('/subscription/cancel', [PaymentController::class, 'cancel'])->name('subscription.cancel');
    Route::get('/payments/history', [PaymentController::class, 'history'])->name('payments.history');
});

// ==========================================
// Admin Routes (Require Admin Role)
// ==========================================

Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Test route (remove after testing)
    Route::get('/test', function() {
        return 'Admin access working! User: ' . auth()->user()->name . ' | Roles: ' . auth()->user()->roles->pluck('name')->implode(', ');
    })->name('test');
    
    // Movies Management
    Route::get('/movies', [AdminMovieController::class, 'index'])->name('movies.index');
    Route::get('/movies/create', [AdminMovieController::class, 'create'])->name('movies.create');
    Route::post('/movies', [AdminMovieController::class, 'store'])->name('movies.store');
    Route::get('/movies/{movie}/edit', [AdminMovieController::class, 'edit'])->name('movies.edit');
    Route::put('/movies/{movie}', [AdminMovieController::class, 'update'])->name('movies.update');
    
    // Genres Management
    Route::get('/genres', [App\Http\Controllers\Admin\GenreController::class, 'index'])->name('genres.index');
    Route::post('/genres', [App\Http\Controllers\Admin\GenreController::class, 'store'])->name('genres.store');
    Route::put('/genres/{id}', [App\Http\Controllers\Admin\GenreController::class, 'update'])->name('genres.update');
    Route::delete('/genres/{id}', [App\Http\Controllers\Admin\GenreController::class, 'destroy'])->name('genres.destroy');
    
    // Users Management
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
    Route::post('/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
    Route::get('/users/{id}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{id}/suspend', [App\Http\Controllers\Admin\UserController::class, 'suspend'])->name('users.suspend');
    Route::post('/users/{id}/assign-roles', [App\Http\Controllers\Admin\UserController::class, 'assignRoles'])->name('users.assign-roles');
    
    // Roles Management
    Route::resource('roles', App\Http\Controllers\Admin\RoleController::class);
    Route::post('/roles/{role}/assign-permissions', [App\Http\Controllers\Admin\RoleController::class, 'assignPermissions'])->name('roles.assign-permissions');
    
    // Permissions Management
    Route::resource('permissions', App\Http\Controllers\Admin\PermissionController::class);
    
    // Payments Management
    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{id}', [AdminPaymentController::class, 'show'])->name('payments.show');
    
    // Statistics & Reports
    Route::get('/stats/refresh', function() {
        return response()->json([
            'total_movies' => 150,
            'total_users' => 1234,
            'total_views' => 45678,
            'total_revenue' => 12345
        ]);
    })->name('stats.refresh');
    
    // Bulk Actions
    Route::post('/bulk-action', function() {
        return response()->json(['success' => true]);
    })->name('bulk.action');
    
    // Export Data
    Route::get('/export', function() {
        // Export logic
    })->name('export');
});

// ==========================================
// Static Pages
// ==========================================

Route::get('/about', function() {
    return view('pages.about');
})->name('about');

Route::get('/contact', function() {
    return view('pages.contact');
})->name('contact');

Route::get('/terms', function() {
    return view('pages.terms');
})->name('terms');

Route::get('/privacy', function() {
    return view('pages.privacy');
})->name('privacy');

Route::get('/faq', function() {
    return view('pages.faq');
})->name('faq');

Route::get('/help', function() {
    return view('pages.help');
})->name('help');
