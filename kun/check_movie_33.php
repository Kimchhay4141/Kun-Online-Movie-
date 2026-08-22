<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Movie;

$movieId = 33;
$movie = Movie::with('videos', 'genres')->find($movieId);

if (!$movie) {
    echo "Movie {$movieId} not found\n";
    exit;
}

echo "Movie ID: {$movie->id}\n";
echo "Title: {$movie->title}\n";
echo "Slug: {$movie->slug}\n";
echo "Status: {$movie->status}\n";
echo "Thumbnail: {$movie->thumbnail}\n";
echo "Banner: {$movie->banner}\n";
echo "Videos: {$movie->videos->count()}\n";

foreach ($movie->videos as $video) {
    echo "  - {$video->video_type}: {$video->video_url}\n";
}
