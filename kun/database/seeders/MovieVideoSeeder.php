<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movie;
use App\Models\MovieVideo;

class MovieVideoSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        $this->command->info('Creating movie videos...');

        $movies = Movie::all();

        foreach ($movies as $movie) {
            // Main movie video
            MovieVideo::create([
                'movie_id' => $movie->id,
                'title' => 'Full Movie',
                'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
                'video_type' => 'movie',
                'quality' => '1080p',
                'duration' => $movie->duration,
                'file_size' => rand(1500, 3000), // MB
                'is_primary' => true,
            ]);

            // Trailer
            MovieVideo::create([
                'movie_id' => $movie->id,
                'title' => 'Official Trailer',
                'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4',
                'video_type' => 'trailer',
                'quality' => '720p',
                'duration' => rand(120, 180), // 2-3 minutes
                'file_size' => rand(50, 150), // MB
                'is_primary' => false,
            ]);

            // Teaser (for some movies)
            if (rand(0, 1)) {
                MovieVideo::create([
                    'movie_id' => $movie->id,
                    'title' => 'Teaser',
                    'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4',
                    'video_type' => 'teaser',
                    'quality' => '720p',
                    'duration' => rand(30, 90), // 30s-1.5min
                    'file_size' => rand(20, 60), // MB
                    'is_primary' => false,
                ]);
            }

            // Behind the Scenes (for featured movies)
            if ($movie->is_featured) {
                MovieVideo::create([
                    'movie_id' => $movie->id,
                    'title' => 'Behind the Scenes',
                    'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4',
                    'video_type' => 'behind_scenes',
                    'quality' => '720p',
                    'duration' => rand(300, 600), // 5-10 minutes
                    'file_size' => rand(200, 400), // MB
                    'is_primary' => false,
                ]);
            }

            $this->command->info("  ✓ Added videos for: {$movie->title}");
        }

        $this->command->info('🎥 Movie videos seeded successfully!');
    }
}
