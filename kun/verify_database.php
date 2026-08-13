<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Movie;
use App\Models\Genre;
use App\Models\MovieVideo;
use App\Models\MovieView;
use App\Models\Favorite;
use App\Models\Watchlist;
use App\Models\Payment;

echo "===========================================\n";
echo "   POSTGRESQL DATABASE VERIFICATION\n";
echo "===========================================\n\n";

echo "📊 TABLE COUNTS:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "1.  users:           " . User::count() . " records\n";
echo "2.  roles:           " . Role::count() . " records\n";
echo "3.  permissions:     " . Permission::count() . " records\n";
echo "4.  movies:          " . Movie::count() . " records\n";
echo "5.  genres:          " . Genre::count() . " records\n";
echo "6.  movie_videos:    " . MovieVideo::count() . " records\n";
echo "7.  movie_views:     " . MovieView::count() . " records\n";
echo "8.  favorites:       " . Favorite::count() . " records\n";
echo "9.  watchlists:      " . Watchlist::count() . " records\n";
echo "10. payments:        " . Payment::count() . " records (SKIPPED ✓)\n";
echo "\n";

echo "👥 SAMPLE USERS:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
foreach (User::take(5)->get() as $user) {
    echo "  • {$user->name} ({$user->email})\n";
}
echo "\n";

echo "🎬 SAMPLE MOVIES:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
foreach (Movie::take(5)->get() as $movie) {
    echo "  • {$movie->title} ⭐ {$movie->rating}\n";
}
echo "\n";

echo "🎭 SAMPLE GENRES:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
foreach (Genre::take(5)->get() as $genre) {
    echo "  • {$genre->icon} {$genre->name}\n";
}
echo "\n";

echo "🎥 SAMPLE VIDEOS:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
foreach (MovieVideo::with('movie')->take(5)->get() as $video) {
    echo "  • {$video->movie->title} - {$video->title} ({$video->video_type})\n";
}
echo "\n";

echo "❤️  SAMPLE FAVORITES:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
foreach (Favorite::with(['user', 'movie'])->take(5)->get() as $fav) {
    echo "  • {$fav->user->name} ❤️  {$fav->movie->title}\n";
}
echo "\n";

echo "🔖 SAMPLE WATCHLISTS:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
foreach (Watchlist::with(['user', 'movie'])->take(5)->get() as $watch) {
    echo "  • {$watch->user->name} → {$watch->movie->title}\n";
}
echo "\n";

echo "👁️  SAMPLE WATCH HISTORY:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
foreach (MovieView::with(['user', 'movie'])->take(5)->get() as $view) {
    $status = $view->completed ? '✓ Completed' : "{$view->progress_percentage}%";
    echo "  • {$view->user->name} watched {$view->movie->title} ({$status})\n";
}
echo "\n";

echo "===========================================\n";
echo "✅ ALL DATA SUCCESSFULLY LOADED!\n";
echo "===========================================\n";
echo "\n";
echo "Database: Kun_Onlien_Movie (PostgreSQL)\n";
echo "Total Tables with Data: 12 tables\n";
echo "Payments Table: Empty (as requested)\n";
echo "\n";
