<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ComingSoonSeeder extends Seeder
{
    public function run(): void
    {
        $movies = [
            [
                'title' => 'Dune: Part Three',
                'description' => 'Paul Atreides unites with the Fremen while seeking revenge against those who destroyed his family.',
                'release_year' => 2026,
                'release_date' => '2026-10-11',
                'duration' => 155,
                'director' => 'Denis Villeneuve',
                'rating' => 0,
                'thumbnail' => 'https://picsum.photos/seed/dune3/400/600',
                'banner' => 'https://picsum.photos/seed/dune3-banner/1920/1080',
                'genres' => ['sci-fi', 'drama'],
            ],
            [
                'title' => 'Deadpool 3',
                'description' => 'Wade Wilson teams up with Wolverine in a multiverse-spanning adventure.',
                'release_year' => 2026,
                'release_date' => '2026-07-24',
                'duration' => 120,
                'director' => 'Shawn Levy',
                'rating' => 0,
                'thumbnail' => 'https://picsum.photos/seed/deadpool3/400/600',
                'banner' => 'https://picsum.photos/seed/deadpool3-banner/1920/1080',
                'genres' => ['action', 'comedy'],
            ],
            [
                'title' => 'Avatar 3',
                'description' => 'Jake and Neytiri face new threats as they explore other regions of Pandora.',
                'release_year' => 2026,
                'release_date' => '2026-12-19',
                'duration' => 190,
                'director' => 'James Cameron',
                'rating' => 0,
                'thumbnail' => 'https://picsum.photos/seed/avatar3/400/600',
                'banner' => 'https://picsum.photos/seed/avatar3-banner/1920/1080',
                'genres' => ['sci-fi', 'action'],
            ],
            [
                'title' => 'The Batman Part II',
                'description' => 'Batman continues his war on crime as a new villain emerges in Gotham City.',
                'release_year' => 2026,
                'release_date' => '2026-10-02',
                'duration' => 165,
                'director' => 'Matt Reeves',
                'rating' => 0,
                'thumbnail' => 'https://picsum.photos/seed/batman2/400/600',
                'banner' => 'https://picsum.photos/seed/batman2-banner/1920/1080',
                'genres' => ['action', 'drama'],
            ],
        ];

        foreach ($movies as $data) {
            $genreSlugs = $data['genres'];
            unset($data['genres']);

            $movie = Movie::updateOrCreate(
                ['slug' => Str::slug($data['title'])],
                array_merge($data, [
                    'slug' => Str::slug($data['title']),
                    'status' => 'coming_soon',
                    'language' => 'en',
                    'country' => 'USA',
                    'is_featured' => false,
                    'is_premium' => false,
                ])
            );

            $genreIds = Genre::whereIn('slug', $genreSlugs)->pluck('id');
            $movie->genres()->sync($genreIds);
        }

        $this->command->info('✓ Coming soon movies seeded: ' . count($movies));
    }
}
