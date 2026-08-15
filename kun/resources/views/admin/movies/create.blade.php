@extends('layouts.app')

@section('title', 'Add Movie - Admin')

@section('content')
<div class="admin-page">
    <div class="container">
        <div class="admin-header">
            <h1>Add Movie</h1>
            <p>Create a new movie for the homepage and movie pages.</p>
        </div>

        <form action="{{ route('admin.movies.store') }}" method="POST" class="admin-form">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')<span class="error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="release_year">Release Year</label>
                    <input type="number" id="release_year" name="release_year" value="{{ old('release_year') }}">
                </div>

                <div class="form-group">
                    <label for="release_date">Release Date</label>
                    <input type="date" id="release_date" name="release_date" value="{{ old('release_date') }}">
                </div>

                <div class="form-group">
                    <label for="duration">Duration (minutes)</label>
                    <input type="number" id="duration" name="duration" value="{{ old('duration') }}">
                </div>

                <div class="form-group">
                    <label for="rating">Rating (0-10)</label>
                    <input type="number" step="0.1" id="rating" name="rating" value="{{ old('rating', 0) }}">
                </div>

                <div class="form-group">
                    <label for="view_count">View Count</label>
                    <input type="number" id="view_count" name="view_count" value="{{ old('view_count', 0) }}">
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        @foreach(['draft', 'published', 'archived', 'coming_soon'] as $status)
                        <option value="{{ $status }}" {{ old('status', 'draft') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="content_rating">Content Rating</label>
                    <select id="content_rating" name="content_rating">
                        <option value="">None</option>
                        @foreach(['G', 'PG', 'PG-13', 'R', 'NC-17'] as $rating)
                        <option value="{{ $rating }}" {{ old('content_rating') === $rating ? 'selected' : '' }}>{{ $rating }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group full-width">
                    <label for="thumbnail">Thumbnail URL</label>
                    <input type="url" id="thumbnail" name="thumbnail" value="{{ old('thumbnail') }}">
                </div>

                <div class="form-group full-width">
                    <label for="banner">Banner URL</label>
                    <input type="url" id="banner" name="banner" value="{{ old('banner') }}">
                </div>

                <div class="form-group full-width">
                    <label for="director">Director</label>
                    <input type="text" id="director" name="director" value="{{ old('director') }}">
                </div>

                <div class="form-group full-width">
                    <label for="cast">Cast</label>
                    <input type="text" id="cast" name="cast" value="{{ old('cast') }}">
                </div>

                <div class="form-group full-width">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="5">{{ old('description') }}</textarea>
                </div>

                <div class="form-group full-width">
                    <label>Genres</label>
                    <div class="genre-checkboxes">
                        @foreach($genres as $genre)
                        <label class="checkbox-label">
                            <input type="checkbox" name="genres[]" value="{{ $genre->id }}"
                                {{ in_array($genre->id, old('genres', [])) ? 'checked' : '' }}>
                            {{ $genre->name }}
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                        Featured on homepage hero
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">Create Movie</button>
                <a href="{{ route('admin.movies.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<style>
.admin-page { padding: 2rem 0 4rem; background: #0a0a0a; min-height: 80vh; color: #fff; }
.admin-header { margin-bottom: 2rem; }
.admin-form { background: #141414; padding: 2rem; border-radius: 8px; }
.form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }
.form-group { display: flex; flex-direction: column; gap: 0.5rem; }
.form-group.full-width { grid-column: 1 / -1; }
.form-group label { font-weight: 600; }
.form-group input, .form-group select, .form-group textarea {
    padding: 0.75rem; border-radius: 4px; border: 1px solid #2a2a2a;
    background: #1f1f1f; color: #fff;
}
.genre-checkboxes { display: flex; flex-wrap: wrap; gap: 1rem; }
.checkbox-label { display: flex; align-items: center; gap: 0.5rem; }
.form-actions { margin-top: 2rem; display: flex; gap: 1rem; }
.btn { padding: 0.75rem 1.5rem; background: #e50914; color: #fff; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; }
.btn-outline { background: transparent; border: 1px solid #fff; }
.error { color: #ff4444; font-size: 0.9rem; }
@media (max-width: 768px) { .form-grid { grid-template-columns: 1fr; } }
</style>
@endsection
