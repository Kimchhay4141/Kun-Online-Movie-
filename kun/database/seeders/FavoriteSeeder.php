<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Movie;
use App\Models\Favorite;

class FavoriteSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        $this->command->info('Creating favorites...');

        $users = User::where('email', '!=', 'admin@kun.com')->take(8)->get();
        $movies = Movie::all();

        foreach ($users as $user) {
            // Each user favorites 2-5 random movies
            $randomMovies = $movies->random(rand(2, 5));
            
            foreach ($randomMovies as $movie) {
                try {
                    Favorite::create([
                        'user_id' => $user->id,
                        'movie_id' => $movie->id,
                    ]);
                } catch (\Exception $e) {
                    // Skip if duplicate
                }
            }
            
            $this->command->info("  ✓ {$user->name}: {$randomMovies->count()} favorites");
        }

        $this->command->info('❤️  Favorites seeded successfully!');
    }
}
