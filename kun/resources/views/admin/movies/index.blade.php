@extends('layouts.admin')

@section('title', 'Movies Management - Admin')

@section('content')
<div class="admin-movies">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-film"></i> Movies Management
            </h1>
            <p class="page-subtitle">Manage all movies in your platform</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.movies.create') }}" class="btn-primary">
                <i class="fas fa-plus"></i> Add New Movie
            </a>
            <button class="btn-secondary" onclick="toggleFilters()">
                <i class="fas fa-filter"></i> Filters
            </button>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section" id="filtersSection" style="display: none;">
        <form method="GET" action="{{ route('admin.movies.index') }}" class="filters-form">
            <div class="filter-group">
                <label>Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title...">
            </div>
            <div class="filter-group">
                <label>Status</label>
                <select name="status">
                    <option value="">All Status</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="coming_soon" {{ request('status') == 'coming_soon' ? 'selected' : '' }}>Coming Soon</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Genre</label>
                <select name="genre">
                    <option value="">All Genres</option>
                    @foreach($genres ?? [] as $genre)
                    <option value="{{ $genre->id }}" {{ request('genre') == $genre->id ? 'selected' : '' }}>{{ $genre->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-apply">Apply</button>
                <a href="{{ route('admin.movies.index') }}" class="btn-reset">Reset</a>
            </div>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="stats-row">
        <div class="stat-card-small">
            <div class="stat-icon bg-primary">
                <i class="fas fa-film"></i>
            </div>
            <div class="stat-info">
                <h4>{{ $totalMovies ?? 0 }}</h4>
                <p>Total Movies</p>
            </div>
        </div>
        <div class="stat-card-small">
            <div class="stat-icon bg-success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <h4>{{ $publishedMovies ?? 0 }}</h4>
                <p>Published</p>
            </div>
        </div>
        <div class="stat-card-small">
            <div class="stat-icon bg-warning">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <h4>{{ $draftMovies ?? 0 }}</h4>
                <p>Drafts</p>
            </div>
        </div>
        <div class="stat-card-small">
            <div class="stat-icon bg-info">
                <i class="fas fa-calendar"></i>
            </div>
            <div class="stat-info">
                <h4>{{ $comingSoonMovies ?? 0 }}</h4>
                <p>Coming Soon</p>
            </div>
        </div>
    </div>

    <!-- Movies Table -->
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-list"></i> All Movies ({{ $movies->total() }})
            </h3>
            <div class="card-actions">
                <select class="bulk-action-select" id="bulkAction">
                    <option value="">Bulk Actions</option>
                    <option value="publish">Publish Selected</option>
                    <option value="draft">Move to Draft</option>
                    @if(auth()->user()->hasPermission('Delete Movie'))
                    <option value="delete">Delete Selected</option>
                    @endif
                </select>
                <button class="btn-sm btn-secondary" onclick="applyBulkAction()">Apply</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th width="40">
                                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                            </th>
                            <th>Movie</th>
                            <th>Genres</th>
                            <th>Rating</th>
                            <th>Views</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movies as $movie)
                        <tr>
                            <td>
                                <input type="checkbox" class="movie-checkbox" value="{{ $movie->id }}">
                            </td>
                            <td>
                                <div class="movie-info-cell">
                                    <img src="{{ $movie->thumbnail ?? 'https://via.placeholder.com/50x75' }}" alt="{{ $movie->title }}" class="movie-thumb">
                                    <div>
                                        <strong>{{ $movie->title }}</strong>
                                        <small>{{ $movie->release_year }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="genres-tags">
                                    @foreach($movie->genres->take(3) as $genre)
                                    <span class="genre-tag">{{ $genre->name }}</span>
                                    @endforeach
                                    @if($movie->genres->count() > 3)
                                    <span class="genre-tag">+{{ $movie->genres->count() - 3 }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="rating-badge">
                                    <i class="fas fa-star"></i> {{ number_format($movie->rating ?? 0, 1) }}
                                </span>
                            </td>
                            <td>
                                <span class="views-badge">
                                    <i class="fas fa-eye"></i> {{ number_format($movie->view_count ?? 0) }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-{{ strtolower($movie->status) }}">
                                    {{ ucfirst($movie->status) }}
                                </span>
                            </td>
                            <td>
                                <small>{{ $movie->created_at->format('M d, Y') }}</small>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('movie.show', $movie->id) }}" class="btn-icon" title="View" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.movies.edit', $movie->id) }}" class="btn-icon" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if(auth()->user()->hasPermission('Delete Movie'))
                                    <button class="btn-icon btn-danger" onclick="deleteMovie({{ $movie->id }})" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                <div class="empty-state-small">
                                    <i class="fas fa-film"></i>
                                    <p>No movies found</p>
                                    <a href="{{ route('admin.movies.create') }}" class="btn-primary btn-sm">Add First Movie</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($movies->hasPages())
            <div class="pagination-wrapper">
                {{ $movies->links('pagination.admin') }}
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.5rem;
}

.page-title i {
    color: var(--primary-color);
}

.page-subtitle {
    color: var(--text-secondary);
    font-size: 1rem;
}

.header-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.btn-primary, .btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 0.95rem;
}

.btn-primary {
    background: var(--primary-color);
    color: white;
}

.btn-primary:hover {
    background: var(--secondary-color);
    transform: translateY(-2px);
}

.btn-secondary {
    background: var(--light-bg);
    color: var(--text-primary);
    border: 1px solid var(--border-color);
}

.btn-secondary:hover {
    background: var(--hover-bg);
}

.filters-section {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.filters-form {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    align-items: end;
}

.filter-group label {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--text-secondary);
}

.filter-group input,
.filter-group select {
    width: 100%;
    background: var(--light-bg);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 0.65rem;
    color: var(--text-primary);
    font-size: 0.9rem;
}

.filter-group input:focus,
.filter-group select:focus {
    outline: none;
    border-color: var(--primary-color);
}

.filter-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-apply, .btn-reset {
    padding: 0.65rem 1.25rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-apply {
    background: var(--primary-color);
    color: white;
    border: none;
}

.btn-reset {
    background: var(--light-bg);
    color: var(--text-primary);
    border: 1px solid var(--border-color);
    text-decoration: none;
    display: inline-block;
    text-align: center;
}

.stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card-small {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    flex-shrink: 0;
}

.bg-primary { background: var(--primary-color); }
.bg-success { background: var(--success-color); }
.bg-warning { background: var(--warning-color); }
.bg-info { background: var(--info-color); }

.stat-info h4 {
    font-size: 1.75rem;
    font-weight: 800;
    margin-bottom: 0.25rem;
}

.stat-info p {
    font-size: 0.85rem;
    color: var(--text-secondary);
}

.bulk-action-select {
    background: var(--light-bg);
    border: 1px solid var(--border-color);
    border-radius: 6px;
    padding: 0.4rem 0.75rem;
    color: var(--text-primary);
    font-size: 0.85rem;
}

.btn-sm {
    padding: 0.4rem 1rem;
    font-size: 0.85rem;
}

.genres-tags {
    display: flex;
    gap: 0.25rem;
    flex-wrap: wrap;
}

.genre-tag {
    background: rgba(229, 9, 20, 0.15);
    color: var(--primary-color);
    padding: 0.25rem 0.65rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.views-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    color: var(--info-color);
    font-weight: 600;
    font-size: 0.9rem;
}

.status-published {
    background: rgba(70, 211, 105, 0.15);
    color: var(--success-color);
}

.status-draft {
    background: rgba(255, 165, 0, 0.15);
    color: var(--warning-color);
}

.status-coming_soon {
    background: rgba(33, 150, 243, 0.15);
    color: var(--info-color);
}

.btn-danger {
    background: rgba(255, 68, 68, 0.15) !important;
    color: var(--danger-color) !important;
}

.btn-danger:hover {
    background: var(--danger-color) !important;
    color: white !important;
}

.empty-state-small {
    padding: 3rem;
    text-align: center;
}

.empty-state-small i {
    font-size: 3rem;
    color: var(--text-muted);
    margin-bottom: 1rem;
}

.empty-state-small p {
    color: var(--text-secondary);
    margin-bottom: 1rem;
}

.pagination-wrapper {
    padding: 1.5rem;
    border-top: 1px solid var(--border-color);
}
</style>
@endsection

@section('scripts')
<script>
function toggleFilters() {
    const filters = document.getElementById('filtersSection');
    filters.style.display = filters.style.display === 'none' ? 'block' : 'none';
}

function toggleSelectAll() {
    const checkboxes = document.querySelectorAll('.movie-checkbox');
    const selectAll = document.getElementById('selectAll');
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
}

function applyBulkAction() {
    const action = document.getElementById('bulkAction').value;
    const selected = Array.from(document.querySelectorAll('.movie-checkbox:checked')).map(cb => cb.value);
    
    if (!action) {
        alert('Please select an action');
        return;
    }
    
    if (selected.length === 0) {
        alert('Please select at least one movie');
        return;
    }
    
    if (action === 'delete') {
        if (!confirm(`Are you sure you want to delete ${selected.length} movie(s)?`)) {
            return;
        }
    }
    
    // Send bulk action request
    fetch('{{ route('admin.bulk.action') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            action: action,
            ids: selected
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error processing bulk action');
        console.error(error);
    });
}

function deleteMovie(id) {
    if (!confirm('Are you sure you want to delete this movie?')) {
        return;
    }
    
    fetch(`/admin/movies/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert('Error deleting movie: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        alert('Error deleting movie');
        console.error(error);
    });
}
</script>
@endsection
