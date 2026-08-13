<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genre;
use Illuminate\Support\Str;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        $genres = [
            ['name' => 'Action', 'icon' => '💥', 'sort_order' => 1],
            ['name' => 'Comedy', 'icon' => '😂', 'sort_order' => 2],
            ['name' => 'Drama', 'icon' => '🎭', 'sort_order' => 3],
            ['name' => 'Horror', 'icon' => '👻', 'sort_order' => 4],
            ['name' => 'Thriller', 'icon' => '🔪', 'sort_order' => 5],
            ['name' => 'Romance', 'icon' => '💕', 'sort_order' => 6],
            ['name' => 'Sci-Fi', 'icon' => '🚀', 'sort_order' => 7],
            ['name' => 'Fantasy', 'icon' => '🧙', 'sort_order' => 8],
            ['name' => 'Adventure', 'icon' => '⚔️', 'sort_order' => 9],
            ['name' => 'Animation', 'icon' => '🎨', 'sort_order' => 10],
            ['name' => 'Crime', 'icon' => '🔫', 'sort_order' => 11],
            ['name' => 'Documentary', 'icon' => '📽️', 'sort_order' => 12],
            ['name' => 'Family', 'icon' => '👨‍👩‍👧‍👦', 'sort_order' => 13],
            ['name' => 'Mystery', 'icon' => '🕵️', 'sort_order' => 14],
            ['name' => 'War', 'icon' => '⚔️', 'sort_order' => 15],
        ];

        foreach ($genres as $genre) {
            Genre::create([
                'name' => $genre['name'],
                'slug' => Str::slug($genre['name']),
                'icon' => $genre['icon'],
                'sort_order' => $genre['sort_order'],
                'is_active' => true,
                'description' => "Watch the best {$genre['name']} movies online.",
            ]);
        }

        $this->command->info('✓ Genres seeded successfully!');
    }
}
