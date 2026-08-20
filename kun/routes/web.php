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

// Social Login
Route::get('/login/{provider}', [LoginController::class, 'redirectToProvider'])->name('social.login');
Route::get('/login/{provider}/callback', [LoginController::class, 'handleProviderCallback']);

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
    Route::get('/movies', [AdminMovieController::class, 'index'])->name('movies.index')->middleware('permission:View Movies');
    Route::get('/movies/create', [AdminMovieController::class, 'create'])->name('movies.create')->middleware('permission:Create Movie');
    Route::post('/movies', [AdminMovieController::class, 'store'])->name('movies.store')->middleware('permission:Create Movie');
    Route::get('/movies/{movie}/edit', [AdminMovieController::class, 'edit'])->name('movies.edit')->middleware('permission:Edit Movie');
    Route::put('/movies/{movie}', [AdminMovieController::class, 'update'])->name('movies.update')->middleware('permission:Edit Movie');
    Route::delete('/movies/{movie}', [AdminMovieController::class, 'destroy'])->name('movies.destroy')->middleware('permission:Delete Movie');
    Route::delete('/movies/{movie}/force', [AdminMovieController::class, 'forceDestroy'])->name('movies.forceDestroy')->middleware('permission:Delete Movie');
    Route::post('/movies/{id}/restore', [AdminMovieController::class, 'restore'])->name('movies.restore')->middleware('permission:Delete Movie');

    // Video Management
    Route::delete('/videos/{video}', [AdminMovieController::class, 'destroyVideo'])->name('videos.destroy')->middleware('permission:Manage Movie Videos');

    // Genres Management
    Route::get('/genres', [App\Http\Controllers\Admin\GenreController::class, 'index'])->name('genres.index')->middleware('permission:View Genres');
    Route::post('/genres', [App\Http\Controllers\Admin\GenreController::class, 'store'])->name('genres.store')->middleware('permission:Create Genre');
    Route::put('/genres/{id}', [App\Http\Controllers\Admin\GenreController::class, 'update'])->name('genres.update')->middleware('permission:Edit Genre');
    Route::delete('/genres/{id}', [App\Http\Controllers\Admin\GenreController::class, 'destroy'])->name('genres.destroy')->middleware('permission:Delete Genre');

    // Users Management
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index')->middleware('permission:View Users');
    Route::get('/users/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create')->middleware('permission:Create User');
    Route::post('/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store')->middleware('permission:Create User');
    Route::get('/users/{id}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show')->middleware('permission:View Users');
    Route::get('/users/{id}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit')->middleware('permission:Edit User');
    Route::put('/users/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update')->middleware('permission:Edit User');
    Route::delete('/users/{id}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy')->middleware('permission:Delete User');
    Route::post('/users/{id}/suspend', [App\Http\Controllers\Admin\UserController::class, 'suspend'])->name('users.suspend')->middleware('permission:Suspend User');
    Route::post('/users/{id}/assign-roles', [App\Http\Controllers\Admin\UserController::class, 'assignRoles'])->name('users.assign-roles')->middleware('permission:Assign Roles');
    
    // Roles Management
    Route::get('/roles', [App\Http\Controllers\Admin\RoleController::class, 'index'])->name('roles.index')->middleware('permission:View Roles');
    Route::get('/roles/create', [App\Http\Controllers\Admin\RoleController::class, 'create'])->name('roles.create')->middleware('permission:Create Role');
    Route::post('/roles', [App\Http\Controllers\Admin\RoleController::class, 'store'])->name('roles.store')->middleware('permission:Create Role');
    Route::get('/roles/{role}', [App\Http\Controllers\Admin\RoleController::class, 'show'])->name('roles.show')->middleware('permission:View Roles');
    Route::get('/roles/{role}/edit', [App\Http\Controllers\Admin\RoleController::class, 'edit'])->name('roles.edit')->middleware('permission:Edit Role');
    Route::put('/roles/{role}', [App\Http\Controllers\Admin\RoleController::class, 'update'])->name('roles.update')->middleware('permission:Edit Role');
    Route::delete('/roles/{role}', [App\Http\Controllers\Admin\RoleController::class, 'destroy'])->name('roles.destroy')->middleware('permission:Delete Role');
    Route::post('/roles/{role}/assign-permissions', [App\Http\Controllers\Admin\RoleController::class, 'assignPermissions'])->name('roles.assign-permissions')->middleware('permission:Assign Roles');

    // Permissions Management
    Route::get('/permissions', [App\Http\Controllers\Admin\PermissionController::class, 'index'])->name('permissions.index')->middleware('permission:View Permissions');
    Route::get('/permissions/create', [App\Http\Controllers\Admin\PermissionController::class, 'create'])->name('permissions.create')->middleware('permission:Create Permission');
    Route::post('/permissions', [App\Http\Controllers\Admin\PermissionController::class, 'store'])->name('permissions.store')->middleware('permission:Create Permission');
    Route::get('/permissions/{permission}', [App\Http\Controllers\Admin\PermissionController::class, 'show'])->name('permissions.show')->middleware('permission:View Permissions');
    Route::get('/permissions/{permission}/edit', [App\Http\Controllers\Admin\PermissionController::class, 'edit'])->name('permissions.edit')->middleware('permission:Edit Permission');
    Route::put('/permissions/{permission}', [App\Http\Controllers\Admin\PermissionController::class, 'update'])->name('permissions.update')->middleware('permission:Edit Permission');
    Route::delete('/permissions/{permission}', [App\Http\Controllers\Admin\PermissionController::class, 'destroy'])->name('permissions.destroy')->middleware('permission:Delete Permission');
    
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
