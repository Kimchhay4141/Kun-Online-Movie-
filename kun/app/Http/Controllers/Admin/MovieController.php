<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\MovieVideo;
use App\Services\VideoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MovieController extends Controller
{
    protected $videoService;

    public function __construct(VideoService $videoService)
    {
        $this->videoService = $videoService;
    }

    public function index(Request $request)
    {
        $query = Movie::with('genres');

        // Search filter
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Genre filter
        if ($request->filled('genre')) {
            $query->whereHas('genres', function($q) use ($request) {
                $q->where('genres.id', $request->genre);
            });
        }

        $movies = $query->orderBy('created_at', 'desc')->paginate(15);

        // Stats
        $totalMovies = Movie::count();
        $publishedMovies = Movie::where('status', 'published')->count();
        $draftMovies = Movie::where('status', 'draft')->count();
        $comingSoonMovies = Movie::where('status', 'coming_soon')->count();

        // Get genres for filter
        $genres = Genre::where('is_active', true)->orderBy('name')->get();

        return view('admin.movies.index', compact(
            'movies',
            'totalMovies',
            'publishedMovies',
            'draftMovies',
            'comingSoonMovies',
            'genres'
        ));
    }

    public function create()
    {
        $genres = Genre::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.movies.create', compact('genres'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatedMovie($request);

        // Handle thumbnail upload
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('movies/thumbnails', 'public');
        }

        // Handle banner upload
        $bannerPath = null;
        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('movies/banners', 'public');
        }

        $movie = Movie::create([
            'title' => $validated['title'],
            'slug' => $this->uniqueSlug($validated['title']),
            'description' => $validated['description'] ?? null,
            'release_year' => $validated['release_year'] ?? null,
            'release_date' => $validated['release_date'] ?? null,
            'duration' => $validated['duration'] ?? null,
            'director' => $validated['director'] ?? null,
            'cast' => $validated['cast'] ?? null,
            'thumbnail' => $thumbnailPath,
            'banner' => $bannerPath,
            'rating' => $validated['rating'] ?? 0,
            'view_count' => $validated['view_count'] ?? 0,
            'status' => $validated['status'],
            'content_rating' => $validated['content_rating'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'published_at' => $validated['status'] === 'published' ? now() : null,
        ]);

        $movie->genres()->sync($validated['genres'] ?? []);

        // Handle video uploads
        $this->handleVideoUploads($request, $movie);

        return redirect()
            ->route('admin.movies.index')
            ->with('success', 'Movie created successfully!');
    }

    public function edit(Movie $movie)
    {
        $movie->load('genres');
        $genres = Genre::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.movies.edit', compact('movie', 'genres'));
    }

    public function update(Request $request, Movie $movie)
    {
        $validated = $this->validatedMovie($request);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($movie->thumbnail) {
                Storage::disk('public')->delete($movie->thumbnail);
            }
            $thumbnailPath = $request->file('thumbnail')->store('movies/thumbnails', 'public');
        } else {
            $thumbnailPath = $movie->thumbnail;
        }

        // Handle banner upload
        if ($request->hasFile('banner')) {
            // Delete old banner if exists
            if ($movie->banner) {
                Storage::disk('public')->delete($movie->banner);
            }
            $bannerPath = $request->file('banner')->store('movies/banners', 'public');
        } else {
            $bannerPath = $movie->banner;
        }

        $movie->update([
            'title' => $validated['title'],
            'slug' => $this->uniqueSlug($validated['title'], $movie->id),
            'description' => $validated['description'] ?? null,
            'release_year' => $validated['release_year'] ?? null,
            'release_date' => $validated['release_date'] ?? null,
            'duration' => $validated['duration'] ?? null,
            'director' => $validated['director'] ?? null,
            'cast' => $validated['cast'] ?? null,
            'thumbnail' => $thumbnailPath,
            'banner' => $bannerPath,
            'rating' => $validated['rating'] ?? 0,
            'view_count' => $validated['view_count'] ?? 0,
            'status' => $validated['status'],
            'content_rating' => $validated['content_rating'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'published_at' => $validated['status'] === 'published' ? ($movie->published_at ?? now()) : null,
        ]);

        $movie->genres()->sync($validated['genres'] ?? []);

        // Handle video uploads
        $this->handleVideoUploads($request, $movie);

        return redirect()
            ->route('admin.movies.index')
            ->with('success', 'Movie updated successfully!');
    }

    private function validatedMovie(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'release_year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'release_date' => ['nullable', 'date'],
            'duration' => ['nullable', 'integer', 'min:1'],
            'director' => ['nullable', 'string', 'max:255'],
            'cast' => ['nullable', 'string'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240'], // 10MB max
            'banner' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:20480'], // 20MB max
            'rating' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'view_count' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:draft,published,archived,coming_soon'],
            'content_rating' => ['nullable', 'in:G,PG,PG-13,R,NC-17'],
            'is_featured' => ['nullable', 'boolean'],
            'genres' => ['nullable', 'array'],
            'genres.*' => ['exists:genres,id'],
            // Video upload validation
            'main_video' => ['nullable', 'file', 'mimes:mp4,webm,ogg,mov,avi,mkv', 'max:2048000'], // 2GB max
            'main_video_url' => ['nullable', 'url'],
            'main_video_title' => ['nullable', 'string', 'max:255'],
            'main_video_quality' => ['nullable', 'in:480p,720p,1080p,4K'],
            'main_video_is_primary' => ['nullable', 'boolean'],
            'trailer_video' => ['nullable', 'file', 'mimes:mp4,webm,ogg,mov,avi,mkv', 'max:2048000'],
            'trailer_video_url' => ['nullable', 'url'],
            'trailer_video_title' => ['nullable', 'string', 'max:255'],
            'trailer_video_quality' => ['nullable', 'in:480p,720p,1080p,4K'],
            'trailer_is_primary' => ['nullable', 'boolean'],
        ]);
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'movie';
        $slug = $base;
        $i = 1;

        while (
            Movie::withTrashed()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    /**
     * Handle video uploads (both files and URLs)
     */
    private function handleVideoUploads(Request $request, Movie $movie)
    {
        // Handle main video file upload
        if ($request->hasFile('main_video')) {
            $this->videoService->uploadVideo(
                $request->file('main_video'),
                $movie->id,
                'movie',
                $request->boolean('main_video_is_primary', true)
            );
        }
        // Handle main video URL
        elseif ($request->filled('main_video_url')) {
            $this->videoService->uploadVideoFromUrl(
                $request->input('main_video_url'),
                $movie->id,
                'movie',
                $request->boolean('main_video_url_is_primary', true)
            );
        }

        // Handle trailer video file upload
        if ($request->hasFile('trailer_video')) {
            $this->videoService->uploadVideo(
                $request->file('trailer_video'),
                $movie->id,
                'trailer',
                $request->boolean('trailer_is_primary', false)
            );
        }
        // Handle trailer video URL
        elseif ($request->filled('trailer_video_url')) {
            $this->videoService->uploadVideoFromUrl(
                $request->input('trailer_video_url'),
                $movie->id,
                'trailer',
                $request->boolean('trailer_video_url_is_primary', false)
            );
        }
    }

    /**
     * Delete a video
     */
    public function destroyVideo(MovieVideo $video)
    {
        $this->videoService->deleteVideo($video);
        
        return redirect()
            ->back()
            ->with('success', 'Video deleted successfully!');
    }
}
