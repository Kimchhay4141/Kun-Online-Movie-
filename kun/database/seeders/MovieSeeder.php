<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movie;
use App\Models\Genre;
use Illuminate\Support\Str;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        $movies = [
            [
                'title' => 'The Dark Universe',
                'description' => 'In a world where darkness reigns, one hero must rise to bring back the light. An epic adventure filled with action and mystery.',
                'release_year' => 2024,
                'duration' => 142,
                'language' => 'en',
                'country' => 'USA',
                'director' => 'Christopher Nolan',
                'cast' => 'Tom Hardy, Emma Stone, Michael B. Jordan',
                'rating' => 8.5,
                'view_count' => 12500,
                'content_rating' => 'PG-13',
                'is_featured' => true,
                'is_premium' => false,
                'status' => 'published',
                'genres' => ['Action', 'Sci-Fi', 'Adventure']
            ],
            [
                'title' => 'Laugh Out Loud',
                'description' => 'A hilarious comedy about a group of friends who start a prank war that goes completely out of control.',
                'release_year' => 2024,
                'duration' => 98,
                'language' => 'en',
                'country' => 'USA',
                'director' => 'Judd Apatow',
                'cast' => 'Kevin Hart, Tiffany Haddish, Seth Rogen',
                'rating' => 7.2,
                'view_count' => 8900,
                'content_rating' => 'R',
                'is_featured' => false,
                'is_premium' => false,
                'status' => 'published',
                'genres' => ['Comedy']
            ],
            [
                'title' => 'Silent Shadows',
                'description' => 'A psychological thriller that will keep you on the edge of your seat. Nothing is what it seems in this twisted tale.',
                'release_year' => 2024,
                'duration' => 115,
                'language' => 'en',
                'country' => 'USA',
                'director' => 'Jordan Peele',
                'cast' => 'Lupita Nyongo, Daniel Kaluuya, Winston Duke',
                'rating' => 8.1,
                'view_count' => 9500,
                'content_rating' => 'R',
                'is_featured' => false,
                'is_premium' => false,
                'status' => 'published',
                'genres' => ['Thriller', 'Horror', 'Mystery']
            ],
            [
                'title' => 'Love in Paris',
                'description' => 'A beautiful love story set in the romantic city of Paris. Two souls find each other against all odds.',
                'release_year' => 2024,
                'duration' => 105,
                'language' => 'en',
                'country' => 'France',
                'director' => 'Sofia Coppola',
                'cast' => 'Timothée Chalamet, Zendaya, Florence Pugh',
                'rating' => 7.8,
                'view_count' => 6700,
                'content_rating' => 'PG-13',
                'is_featured' => false,
                'is_premium' => false,
                'status' => 'published',
                'genres' => ['Romance', 'Drama']
            ],
            [
                'title' => 'Dragon Warriors',
                'description' => 'An animated adventure featuring brave warriors and magical dragons in an epic battle for their kingdom.',
                'release_year' => 2024,
                'duration' => 95,
                'language' => 'en',
                'country' => 'USA',
                'director' => 'Pete Docter',
                'cast' => 'Chris Pratt, Anna Kendrick, Jack Black',
                'rating' => 8.3,
                'view_count' => 15200,
                'content_rating' => 'PG',
                'is_featured' => true,
                'is_premium' => false,
                'status' => 'published',
                'genres' => ['Animation', 'Fantasy', 'Family']
            ],
            [
                'title' => 'Crime City',
                'description' => 'A gritty crime drama following detectives as they hunt down a notorious crime syndicate in the city.',
                'release_year' => 2023,
                'duration' => 128,
                'language' => 'en',
                'country' => 'USA',
                'director' => 'Martin Scorsese',
                'cast' => 'Robert De Niro, Al Pacino, Joe Pesci',
                'rating' => 8.7,
                'view_count' => 18900,
                'content_rating' => 'R',
                'is_featured' => false,
                'is_premium' => false,
                'status' => 'published',
                'genres' => ['Crime', 'Drama', 'Thriller']
            ],
            [
                'title' => 'Space Odyssey 2025',
                'description' => 'Humanity\'s first mission to colonize a distant planet faces unexpected challenges in deep space.',
                'release_year' => 2024,
                'duration' => 155,
                'language' => 'en',
                'country' => 'USA',
                'director' => 'Denis Villeneuve',
                'cast' => 'Matthew McConaughey, Anne Hathaway, Jessica Chastain',
                'rating' => 8.9,
                'view_count' => 22400,
                'content_rating' => 'PG-13',
                'is_featured' => true,
                'is_premium' => true,
                'status' => 'published',
                'genres' => ['Sci-Fi', 'Adventure', 'Drama']
            ],
            [
                'title' => 'The Haunting',
                'description' => 'A family moves into an old mansion, only to discover it\'s haunted by vengeful spirits from the past.',
                'release_year' => 2024,
                'duration' => 102,
                'language' => 'en',
                'country' => 'USA',
                'director' => 'James Wan',
                'cast' => 'Patrick Wilson, Vera Farmiga, Madison Wolfe',
                'rating' => 7.5,
                'view_count' => 11200,
                'content_rating' => 'R',
                'is_featured' => false,
                'is_premium' => false,
                'status' => 'published',
                'genres' => ['Horror', 'Mystery']
            ],
            [
                'title' => 'War Heroes',
                'description' => 'Based on true events, this epic war film follows a group of soldiers on their most dangerous mission.',
                'release_year' => 2023,
                'duration' => 140,
                'language' => 'en',
                'country' => 'USA',
                'director' => 'Steven Spielberg',
                'cast' => 'Tom Hanks, Matt Damon, Edward Burns',
                'rating' => 8.6,
                'view_count' => 16500,
                'content_rating' => 'R',
                'is_featured' => false,
                'is_premium' => false,
                'status' => 'published',
                'genres' => ['War', 'Action', 'Drama']
            ],
            [
                'title' => 'Mystery Island',
                'description' => 'A group of tourists find themselves trapped on a mysterious island where nothing makes sense.',
                'release_year' => 2024,
                'duration' => 118,
                'language' => 'en',
                'country' => 'USA',
                'director' => 'M. Night Shyamalan',
                'cast' => 'Bruce Willis, Samuel L. Jackson, James McAvoy',
                'rating' => 7.9,
                'view_count' => 9800,
                'content_rating' => 'PG-13',
                'is_featured' => false,
                'is_premium' => false,
                'status' => 'published',
                'genres' => ['Mystery', 'Thriller', 'Adventure']
            ],
            [
                'title' => 'Family Reunion',
                'description' => 'A heartwarming family comedy about three generations coming together for a chaotic holiday reunion.',
                'release_year' => 2024,
                'duration' => 92,
                'language' => 'en',
                'country' => 'USA',
                'director' => 'Nancy Meyers',
                'cast' => 'Diane Keaton, Steve Martin, Meryl Streep',
                'rating' => 7.3,
                'view_count' => 7200,
                'content_rating' => 'PG',
                'is_featured' => false,
                'is_premium' => false,
                'status' => 'published',
                'genres' => ['Family', 'Comedy']
            ],
            [
                'title' => 'The Last Kingdom',
                'description' => 'An epic fantasy adventure set in a medieval kingdom where magic and betrayal intertwine.',
                'release_year' => 2024,
                'duration' => 165,
                'language' => 'en',
                'country' => 'UK',
                'director' => 'Peter Jackson',
                'cast' => 'Henry Cavill, Anya Taylor-Joy, Benedict Cumberbatch',
                'rating' => 8.8,
                'view_count' => 19700,
                'content_rating' => 'PG-13',
                'is_featured' => true,
                'is_premium' => true,
                'status' => 'published',
                'genres' => ['Fantasy', 'Adventure', 'Action']
            ],
        ];

        foreach ($movies as $movieData) {
            $genreNames = $movieData['genres'];
            unset($movieData['genres']);

            // Create movie
            $movie = Movie::create([
                'title' => $movieData['title'],
                'slug' => Str::slug($movieData['title']),
                'description' => $movieData['description'],
                'release_year' => $movieData['release_year'],
                'duration' => $movieData['duration'],
                'language' => $movieData['language'],
                'country' => $movieData['country'],
                'director' => $movieData['director'],
                'cast' => $movieData['cast'],
                'rating' => $movieData['rating'],
                'view_count' => $movieData['view_count'],
                'content_rating' => $movieData['content_rating'],
                'is_featured' => $movieData['is_featured'],
                'is_premium' => $movieData['is_premium'],
                'status' => $movieData['status'],
                'published_at' => now(),
                'thumbnail' => 'https://via.placeholder.com/300x450/1a1a1a/ffffff?text=' . urlencode($movieData['title']),
                'banner' => 'https://via.placeholder.com/1920x1080/1a1a1a/ffffff?text=' . urlencode($movieData['title']),
            ]);

            // Attach genres
            $genres = Genre::whereIn('name', $genreNames)->pluck('id');
            $movie->genres()->attach($genres);

            $this->command->info("✓ Created: {$movie->title}");
        }

        $this->command->info('🎬 Movies seeded successfully!');
    }
}
