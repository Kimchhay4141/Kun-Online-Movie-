<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::with('genres')->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.movies.index', compact('movies'));
    }

    public function edit(Movie $movie)
    {
        $movie->load('genres');
        $genres = Genre::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.movies.edit', compact('movie', 'genres'));
    }

    public function update(Request $request, Movie $movie)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'release_year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'release_date' => ['nullable', 'date'],
            'duration' => ['nullable', 'integer', 'min:1'],
            'director' => ['nullable', 'string', 'max:255'],
            'cast' => ['nullable', 'string'],
            'thumbnail' => ['nullable', 'url'],
            'banner' => ['nullable', 'url'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'view_count' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:draft,published,archived,coming_soon'],
            'content_rating' => ['nullable', 'in:G,PG,PG-13,R,NC-17'],
            'is_featured' => ['nullable', 'boolean'],
            'genres' => ['nullable', 'array'],
            'genres.*' => ['exists:genres,id'],
        ]);

        $movie->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'description' => $validated['description'] ?? null,
            'release_year' => $validated['release_year'] ?? null,
            'release_date' => $validated['release_date'] ?? null,
            'duration' => $validated['duration'] ?? null,
            'director' => $validated['director'] ?? null,
            'cast' => $validated['cast'] ?? null,
            'thumbnail' => $validated['thumbnail'] ?? null,
            'banner' => $validated['banner'] ?? null,
            'rating' => $validated['rating'] ?? 0,
            'view_count' => $validated['view_count'] ?? 0,
            'status' => $validated['status'],
            'content_rating' => $validated['content_rating'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'published_at' => $validated['status'] === 'published' ? ($movie->published_at ?? now()) : null,
        ]);

        $movie->genres()->sync($validated['genres'] ?? []);

        return redirect()
            ->route('admin.movies.index')
            ->with('success', 'Movie updated successfully!');
    }
}
