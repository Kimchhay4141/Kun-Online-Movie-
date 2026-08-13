<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Movie;
use App\Models\MovieView;

class MovieViewSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        $this->command->info('Creating movie views/watch history...');

        $users = User::where('email', '!=', 'admin@kun.com')->take(8)->get();
        $movies = Movie::all();

        foreach ($users as $user) {
            // Each user watches 4-8 random movies
            $watchedMovies = $movies->random(rand(4, 8));
            
            foreach ($watchedMovies as $movie) {
                $totalDuration = $movie->duration * 60; // Convert minutes to seconds
                $watchDuration = rand(300, $totalDuration); // Watch between 5 minutes and full movie
                $progressPercentage = min(100, round(($watchDuration / $totalDuration) * 100));
                $completed = $progressPercentage >= 90;
                
                try {
                    MovieView::create([
                        'user_id' => $user->id,
                        'movie_id' => $movie->id,
                        'watch_duration' => $watchDuration,
                        'total_duration' => $totalDuration,
                        'progress_percentage' => $progressPercentage,
                        'last_watched_at' => now()->subDays(rand(0, 30)),
                        'completed' => $completed,
                    ]);
                    
                    // Update movie view count
                    $movie->increment('view_count');
                } catch (\Exception $e) {
                    // Skip if duplicate
                }
            }
            
            $this->command->info("  ✓ {$user->name}: {$watchedMovies->count()} movies watched");
        }

        $this->command->info('👁️  Movie views seeded successfully!');
    }
}
