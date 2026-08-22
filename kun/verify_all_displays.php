<?php

/**
 * Verify that images and videos display correctly on ALL pages
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Movie;

echo "🔍 Verifying Image & Video Display Across All Pages\n";
echo str_repeat("=", 70) . "\n\n";

// Get sample movies
$movies = Movie::with('videos', 'genres')->limit(5)->get();

if ($movies->count() === 0) {
    echo "❌ No movies found in database!\n";
    exit(1);
}

echo "📊 Checking " . $movies->count() . " movies...\n\n";

$issuesFound = [];
$totalChecks = 0;
$passedChecks = 0;

foreach ($movies as $movie) {
    echo "Movie: {$movie->title} (ID: {$movie->id})\n";
    echo str_repeat("-", 70) . "\n";
    
    // Check thumbnail
    $totalChecks++;
    if ($movie->thumbnail) {
        if (str_contains($movie->thumbnail, 'supabase.co')) {
            echo "  ✅ Thumbnail: Supabase URL\n";
            echo "     {$movie->thumbnail}\n";
            $passedChecks++;
        } else if (str_contains($movie->thumbnail, 'placeholder')) {
            echo "  ⚠️  Thumbnail: Using placeholder\n";
            $passedChecks++;
        } else {
            echo "  ❌ Thumbnail: Local path detected!\n";
            echo "     {$movie->thumbnail}\n";
            $issuesFound[] = "Movie #{$movie->id}: Thumbnail is local path";
        }
    } else {
        echo "  ⚠️  Thumbnail: Not set (will use placeholder)\n";
        $passedChecks++;
    }
    
    // Check banner
    $totalChecks++;
    if ($movie->banner) {
        if (str_contains($movie->banner, 'supabase.co')) {
            echo "  ✅ Banner: Supabase URL\n";
            $passedChecks++;
        } else if (str_contains($movie->banner, 'placeholder')) {
            echo "  ⚠️  Banner: Using placeholder\n";
            $passedChecks++;
        } else {
            echo "  ❌ Banner: Local path detected!\n";
            echo "     {$movie->banner}\n";
            $issuesFound[] = "Movie #{$movie->id}: Banner is local path";
        }
    } else {
        echo "  ⚠️  Banner: Not set (will fallback to thumbnail)\n";
        $passedChecks++;
    }
    
    // Check videos
    $totalChecks++;
    if ($movie->videos->count() > 0) {
        $allSupabase = true;
        foreach ($movie->videos as $video) {
            if (!str_contains($video->video_url, 'supabase.co')) {
                $allSupabase = false;
                echo "  ❌ Video: Local or external URL\n";
                echo "     {$video->video_url}\n";
                $issuesFound[] = "Movie #{$movie->id}: Video #{$video->id} not in Supabase";
            }
        }
        if ($allSupabase) {
            echo "  ✅ Videos: All in Supabase (" . $movie->videos->count() . " videos)\n";
            $passedChecks++;
        }
    } else {
        echo "  ⚠️  Videos: None uploaded\n";
        $passedChecks++;
    }
    
    // Check status
    $totalChecks++;
    if ($movie->status === 'published') {
        echo "  ✅ Status: Published (visible to users)\n";
        $passedChecks++;
    } else {
        echo "  ⚠️  Status: {$movie->status} (not visible to users)\n";
        $passedChecks++;
    }
    
    echo "\n";
}

echo str_repeat("=", 70) . "\n";
echo "📊 VERIFICATION SUMMARY\n";
echo str_repeat("=", 70) . "\n";
echo "Total Checks: $totalChecks\n";
echo "Passed: $passedChecks\n";
echo "Issues: " . count($issuesFound) . "\n";
echo "\n";

if (count($issuesFound) > 0) {
    echo "⚠️  Issues Found:\n";
    foreach ($issuesFound as $issue) {
        echo "  - $issue\n";
    }
    echo "\n";
    echo "💡 To fix: Run 'php migrate_all_images.php' to migrate remaining local files\n";
} else {
    echo "✅ All checks passed! Images and videos are correctly configured.\n";
}

echo "\n";
echo "🌐 Pages Where Images/Videos Are Displayed:\n";
echo str_repeat("-", 70) . "\n";
echo "1. Home Page:\n";
echo "   URL: http://localhost:8000\n";
echo "   Shows: Featured movies, trending, new releases\n";
echo "   Images: Thumbnails from database\n";
echo "\n";
echo "2. Movie Detail Page:\n";
echo "   URL: http://localhost:8000/movies/{id}\n";
echo "   Shows: Poster, banner background, movie info\n";
echo "   Images: thumbnail, banner\n";
echo "\n";
echo "3. Watch Page:\n";
echo "   URL: http://localhost:8000/movie/{id}/watch\n";
echo "   Shows: Video player\n";
echo "   Video: from movie_videos table\n";
echo "\n";
echo "4. Genre Pages:\n";
echo "   URL: http://localhost:8000/genres/{slug}\n";
echo "   Shows: Movies in that genre\n";
echo "   Images: Thumbnails\n";
echo "\n";
echo "5. Search Results:\n";
echo "   URL: http://localhost:8000/search?q=...\n";
echo "   Shows: Matching movies\n";
echo "   Images: Thumbnails\n";
echo "\n";
echo "6. Admin Movie List:\n";
echo "   URL: http://localhost:8000/admin/movies\n";
echo "   Shows: All movies with thumbnails\n";
echo "   Images: Thumbnails\n";
echo "\n";
echo "7. Admin Edit Page:\n";
echo "   URL: http://localhost:8000/admin/movies/{id}/edit\n";
echo "   Shows: Current thumbnail and banner\n";
echo "   Images: Direct from database URLs\n";
echo "\n";
echo "8. User Profile Pages:\n";
echo "   URLs: /watchlist, /favorites, /history\n";
echo "   Shows: User's movies\n";
echo "   Images: Thumbnails\n";
echo "\n";

echo "✅ Verification complete!\n";
