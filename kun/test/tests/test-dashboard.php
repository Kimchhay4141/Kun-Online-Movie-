#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Movie;
use App\Models\User;
use App\Models\MovieView;
use App\Models\Payment;

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║   TESTING DASHBOARD DATA AVAILABILITY                         ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

echo "Checking required tables and data...\n\n";

// Test Movies
echo "1. Movies Table:\n";
try {
    $movieCount = Movie::count();
    echo "   ✅ Movies table exists\n";
    echo "   Total movies: {$movieCount}\n";
} catch (\Exception $e) {
    echo "   ❌ Movies table error: " . $e->getMessage() . "\n";
}
echo "\n";

// Test Users
echo "2. Users Table:\n";
try {
    $userCount = User::count();
    echo "   ✅ Users table exists\n";
    echo "   Total users: {$userCount}\n";
} catch (\Exception $e) {
    echo "   ❌ Users table error: " . $e->getMessage() . "\n";
}
echo "\n";

// Test MovieViews (optional)
echo "3. MovieViews Table (optional):\n";
try {
    if (class_exists(MovieView::class)) {
        $viewCount = MovieView::count();
        echo "   ✅ MovieViews table exists\n";
        echo "   Total views: {$viewCount}\n";
    } else {
        echo "   ⚠️  MovieView class not found (optional)\n";
    }
} catch (\Exception $e) {
    echo "   ⚠️  MovieViews table not available (optional)\n";
    echo "   This is OK - dashboard will show 0 for views\n";
}
echo "\n";

// Test Payments (optional)
echo "4. Payments Table (optional):\n";
try {
    if (class_exists(Payment::class)) {
        $paymentCount = Payment::count();
        $totalRevenue = Payment::where('status', 'completed')->sum('amount') ?? 0;
        echo "   ✅ Payments table exists\n";
        echo "   Total payments: {$paymentCount}\n";
        echo "   Total revenue: \${$totalRevenue}\n";
    } else {
        echo "   ⚠️  Payment class not found (optional)\n";
    }
} catch (\Exception $e) {
    echo "   ⚠️  Payments table not available (optional)\n";
    echo "   This is OK - dashboard will show \$0 for revenue\n";
}
echo "\n";

// Test Movie relationship
echo "5. Movie-MovieViews Relationship:\n";
try {
    $movie = Movie::first();
    if ($movie) {
        $viewCount = $movie->movieViews()->count();
        echo "   ✅ Movie has movieViews relationship\n";
        echo "   Sample movie views: {$viewCount}\n";
    } else {
        echo "   ⚠️  No movies found to test relationship\n";
    }
} catch (\Exception $e) {
    echo "   ⚠️  Relationship test failed (optional)\n";
    echo "   Dashboard will still work - will show recent movies instead\n";
}
echo "\n";

// Summary
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║   DASHBOARD READINESS CHECK                                    ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

$hasMovies = false;
$hasUsers = false;

try {
    $hasMovies = Movie::count() > 0;
    $hasUsers = User::count() > 0;
} catch (\Exception $e) {
    // Ignore
}

if ($hasMovies && $hasUsers) {
    echo "✅ Dashboard is ready to display!\n";
    echo "   • Movies: Available\n";
    echo "   • Users: Available\n";
    echo "   • Views: " . (class_exists(MovieView::class) ? 'Available' : 'Will show 0') . "\n";
    echo "   • Revenue: " . (class_exists(Payment::class) ? 'Available' : 'Will show $0') . "\n";
    echo "\n";
    echo "You can now access: http://localhost:8000/admin/dashboard\n";
} else {
    echo "⚠️  Dashboard needs some data:\n";
    if (!$hasMovies) echo "   • Add some movies first\n";
    if (!$hasUsers) echo "   • Should have users (at least admin)\n";
    echo "\n";
    echo "Dashboard will still load, but with empty data.\n";
}

echo "\n";
