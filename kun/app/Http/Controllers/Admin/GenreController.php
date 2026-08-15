<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GenreController extends Controller
{
    public function index(Request $request)
    {
        $query = Genre::withCount('movies');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('slug', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $genres = $query->orderBy('sort_order')->orderBy('name')->get();

        $totalGenres = Genre::count();
        $activeGenres = Genre::where('is_active', true)->count();
        $inactiveGenres = Genre::where('is_active', false)->count();
        $genresWithMovies = Genre::has('movies')->count();

        return view('admin.genres.index', compact(
            'genres',
            'totalGenres',
            'activeGenres',
            'inactiveGenres',
            'genresWithMovies'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $genre = Genre::create([
            'name' => $validated['name'],
            'slug' => $this->uniqueSlug($validated['name']),
            'description' => $validated['description'] ?? null,
            'icon' => $validated['icon'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return response()->json(['success' => true, 'genre' => $genre]);
    }

    public function update(Request $request, $id)
    {
        $genre = Genre::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $genre->update([
            'name' => $validated['name'],
            'slug' => $this->uniqueSlug($validated['name'], $genre->id),
            'description' => $validated['description'] ?? null,
            'icon' => $validated['icon'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active', $genre->is_active),
        ]);

        return response()->json(['success' => true, 'genre' => $genre]);
    }

    public function destroy($id)
    {
        $genre = Genre::findOrFail($id);
        
        if ($genre->movies()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete genre with associated movies'
            ], 400);
        }

        $genre->delete();

        return response()->json(['success' => true]);
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'genre';
        $slug = $base;
        $i = 1;

        while (
            Genre::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
