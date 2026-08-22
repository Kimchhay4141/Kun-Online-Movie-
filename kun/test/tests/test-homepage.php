#!/usr/bin/env php
<?php

/**
 * Test Homepage Loading
 */

define('LARAVEL_START', microtime(true));

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Movie;
use App\Models\Genre;

echo "\n";
echo "╔═══════════════════════════════════════════════════╗\n";
echo "║           TESTING HOMEPAGE DATA                   ║\n";
echo "╚═══════════════════════════════════════════════════╝\n";
echo "\n";

try {
    echo "Testing data for homepage...\n\n";

    // Test featured movie
    $featured = Movie::with('genres')
        ->where('status', 'published')
        ->where('is_featured', true)
        ->first();
    
    if (!$featured) {
        $featured = Movie::with('genres')
            ->where('status', 'published')
            ->orderBy('rating', 'desc')
            ->first();
    }
    
    echo "✓ Featured Movie: " . ($featured ? $featured->title : "None") . "\n";

    // Test trending
    $trending = Movie::with('genres')
        ->where('status', 'published')
        ->withCount(['movieViews as recent_views' => function($query) {
            $query->where('created_at', '>=', now()->subDays(7));
        }])
        ->orderBy('recent_views', 'desc')
        ->take(10)
        ->get();
    
    echo "✓ Trending Movies: " . $trending->count() . " found\n";

    // Test new releases
    $newReleases = Movie::with('genres')
        ->where('status', 'published')
        ->latest()
        ->take(10)
        ->get();
    
    echo "✓ New Releases: " . $newReleases->count() . " found\n";

    // Test popular
    $popular = Movie::with('genres')
        ->where('status', 'published')
        ->orderBy('view_count', 'desc')
        ->take(10)
        ->get();
    
    echo "✓ Popular Movies: " . $popular->count() . " found\n";

    // Test genres
    $genres = Genre::where('is_active', true)
        ->withCount('movies')
        ->orderBy('sort_order')
        ->get();
    
    echo "✓ Genres: " . $genres->count() . " found\n";

    echo "\n";
    echo "╔═══════════════════════════════════════════════════╗\n";
    echo "║        ✓ ALL TESTS PASSED SUCCESSFULLY!          ║\n";
    echo "╚═══════════════════════════════════════════════════╝\n";
    echo "\n";
    echo "Your homepage should now work!\n";
    echo "\n";
    echo "🌐 Visit: http://127.0.0.1:8000\n";
    echo "🔐 Admin: http://127.0.0.1:8000/admin/dashboard\n";
    echo "\n";

} catch (Exception $e) {
    echo "\n";
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\n";
    exit(1);
}
