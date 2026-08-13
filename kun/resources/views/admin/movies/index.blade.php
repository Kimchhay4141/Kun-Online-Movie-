@extends('layouts.app')

@section('title', 'Manage Movies - Admin')

@section('content')
<div class="admin-page">
    <div class="container">
        <div class="admin-header">
            <h1>Manage Movies</h1>
            <p>Edit sample movies or update your own content.</p>
        </div>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Poster</th>
                        <th>Title</th>
                        <th>Year</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th>Featured</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movies as $movie)
                    <tr>
                        <td>
                            <img src="{{ $movie->thumbnail }}" alt="{{ $movie->title }}" class="admin-thumb">
                        </td>
                        <td>{{ $movie->title }}</td>
                        <td>{{ $movie->release_year }}</td>
                        <td>{{ number_format($movie->rating, 1) }}</td>
                        <td><span class="status-badge status-{{ $movie->status }}">{{ $movie->status }}</span></td>
                        <td>{{ $movie->is_featured ? 'Yes' : 'No' }}</td>
                        <td>
                            <a href="{{ route('admin.movies.edit', $movie) }}" class="btn btn-sm">Edit</a>
                            <a href="{{ route('movie.show', $movie->id) }}" class="btn btn-sm btn-outline" target="_blank">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">No movies yet. Run: php artisan db:seed --class=MovieSeeder</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $movies->links() }}
    </div>
</div>

<style>
.admin-page { padding: 2rem 0 4rem; background: #0a0a0a; min-height: 80vh; color: #fff; }
.admin-header { margin-bottom: 2rem; }
.admin-header h1 { font-size: 2rem; margin-bottom: 0.5rem; }
.admin-table-wrap { overflow-x: auto; background: #141414; border-radius: 8px; }
.admin-table { width: 100%; border-collapse: collapse; }
.admin-table th, .admin-table td { padding: 1rem; text-align: left; border-bottom: 1px solid #2a2a2a; }
.admin-thumb { width: 50px; height: 75px; object-fit: cover; border-radius: 4px; }
.status-badge { padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.85rem; text-transform: capitalize; }
.status-published { background: rgba(70,211,105,0.2); color: #46d369; }
.status-draft { background: rgba(255,165,0,0.2); color: #ffa500; }
.status-archived { background: rgba(128,128,128,0.2); color: #aaa; }
.btn { display: inline-block; padding: 0.5rem 1rem; background: #e50914; color: #fff; border-radius: 4px; text-decoration: none; }
.btn-sm { padding: 0.35rem 0.75rem; font-size: 0.9rem; }
.btn-outline { background: transparent; border: 1px solid #fff; margin-left: 0.5rem; }
.alert-success { background: rgba(70,211,105,0.15); border: 1px solid #46d369; color: #46d369; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; }
</style>
@endsection
