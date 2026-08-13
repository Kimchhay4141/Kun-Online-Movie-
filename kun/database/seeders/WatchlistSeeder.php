<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Movie;
use App\Models\Watchlist;

class WatchlistSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        $this->command->info('Creating watchlists...');

        $users = User::where('email', '!=', 'admin@kun.com')->take(8)->get();
        $movies = Movie::all();

        foreach ($users as $user) {
            // Each user adds 3-7 random movies to watchlist
            $randomMovies = $movies->random(rand(3, 7));
            
            foreach ($randomMovies as $movie) {
                try {
                    Watchlist::create([
                        'user_id' => $user->id,
                        'movie_id' => $movie->id,
                    ]);
                } catch (\Exception $e) {
                    // Skip if duplicate
                }
            }
            
            $this->command->info("  ✓ {$user->name}: {$randomMovies->count()} in watchlist");
        }

        $this->command->info('🔖 Watchlists seeded successfully!');
    }
}
