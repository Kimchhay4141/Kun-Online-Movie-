@extends('layouts.admin')

@section('title', 'Genres Management - Admin')

@section('content')
<div class="genres-page">
    <div class="dashboard-header">
        <div>
            <h1 class="page-title"><i class="fas fa-tags"></i> Genres</h1>
            <p class="page-subtitle">Organize movies into categories and keep them easy to browse</p>
        </div>
        <div class="header-actions">
            <button type="button" class="btn-add" onclick="openCreateModal()">
                <i class="fas fa-plus"></i> Add Genre
            </button>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card stat-info">
            <div class="stat-icon"><i class="fas fa-tags"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($totalGenres) }}</div>
                <div class="stat-label">Total Genres</div>
            </div>
        </div>
        <div class="stat-card stat-success">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($activeGenres) }}</div>
                <div class="stat-label">Active</div>
            </div>
        </div>
        <div class="stat-card stat-warning">
            <div class="stat-icon"><i class="fas fa-pause-circle"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($inactiveGenres) }}</div>
                <div class="stat-label">Inactive</div>
            </div>
        </div>
        <div class="stat-card stat-primary">
            <div class="stat-icon"><i class="fas fa-film"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($genresWithMovies) }}</div>
                <div class="stat-label">Used in Movies</div>
            </div>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.genres.index') }}" class="toolbar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search genre name or description...">
        </div>
        <select name="status">
            <option value="">All status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        <button type="submit" class="btn-apply">Filter</button>
        @if(request()->hasAny(['search', 'status']))
        <a href="{{ route('admin.genres.index') }}" class="btn-reset">Clear</a>
        @endif
    </form>

    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-list"></i> All Genres <span class="count">{{ $genres->count() }}</span></h3>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Genre</th>
                        <th>Movies</th>
                        <th>Status</th>
                        <th>Order</th>
                        <th>Created</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($genres as $genre)
                    <tr>
                        <td>
                            <div class="genre-cell">
                                <div class="genre-icon">{{ $genre->icon ?: '🎬' }}</div>
                                <div>
                                    <strong>{{ $genre->name }}</strong>
                                    <span>{{ $genre->slug }}</span>
                                    @if($genre->description)
                                    <small>{{ Str::limit($genre->description, 60) }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="pill pill-info">{{ $genre->movies_count }} movies</span>
                        </td>
                        <td>
                            <span class="dot-status {{ $genre->is_active ? 'status-active' : 'status-inactive' }}">
                                {{ $genre->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td><span class="order-badge">#{{ $genre->sort_order }}</span></td>
                        <td>
                            <div class="joined">
                                <strong>{{ $genre->created_at->format('M d, Y') }}</strong>
                                <span>{{ $genre->created_at->diffForHumans() }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button type="button" class="btn-icon" onclick="editGenre({{ $genre->id }})" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <button type="button" class="btn-icon danger" onclick="deleteGenre({{ $genre->id }})" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty">
                                <i class="fas fa-tags"></i>
                                <h3>No genres found</h3>
                                <p>Create your first genre to organize movies.</p>
                                <button type="button" class="btn-add" onclick="openCreateModal()">Add Genre</button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="genreModal" class="genre-modal">
    <div class="genre-modal-panel">
        <div class="genre-modal-header">
            <h2 id="modalTitle">Add Genre</h2>
            <button type="button" class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
        </div>
        <form id="genreForm" class="genre-modal-body" onsubmit="saveGenre(event)">
            <input type="hidden" id="genreId">
            <div class="field">
                <label for="genreName">Name</label>
                <input type="text" id="genreName" required placeholder="e.g. Action">
            </div>
            <div class="field">
                <label for="genreDescription">Description</label>
                <textarea id="genreDescription" rows="3" placeholder="Short description"></textarea>
            </div>
            <div class="field">
                <label for="genreIcon">Icon (emoji)</label>
                <input type="text" id="genreIcon" placeholder="💥">
            </div>
            <div class="form-row">
                <div class="field">
                    <label for="genreSortOrder">Sort order</label>
                    <input type="number" id="genreSortOrder" value="0" min="0">
                </div>
                <div class="field">
                    <label for="genreStatus">Status</label>
                    <select id="genreStatus">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="form-actions">
                <button type="button" class="btn-reset" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-add">Save Genre</button>
            </div>
        </form>
    </div>
</div>

<style>
.btn-add { display: inline-flex; align-items: center; gap: .5rem; padding: .7rem 1.25rem; background: var(--primary-color); color: #fff; border-radius: 10px; font-weight: 700; text-decoration: none; border: none; cursor: pointer; }
.btn-add:hover { background: #f40612; }
.toolbar { display: flex; gap: .75rem; flex-wrap: wrap; margin-bottom: 1.25rem; background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 14px; padding: 1rem; }
.search-box { flex: 1; min-width: 220px; position: relative; }
.search-box i { position: absolute; left: .9rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); }
.search-box input, .toolbar select { background: var(--light-bg); border: 1px solid var(--border-color); color: #fff; border-radius: 10px; padding: .7rem .9rem; outline: none; }
.search-box input { width: 100%; padding-left: 2.4rem; }
.toolbar select { min-width: 140px; }
.search-box input:focus, .toolbar select:focus { border-color: var(--primary-color); }
.btn-apply, .btn-reset { padding: .7rem 1.1rem; border-radius: 10px; font-weight: 600; border: none; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; }
.btn-apply { background: var(--primary-color); color: #fff; }
.btn-reset { background: var(--light-bg); color: var(--text-secondary); border: 1px solid var(--border-color); }
.count { margin-left: .4rem; background: var(--light-bg); color: var(--text-secondary); font-size: .75rem; padding: .15rem .55rem; border-radius: 999px; }
.genre-cell { display: flex; align-items: flex-start; gap: .85rem; }
.genre-icon { width: 42px; height: 42px; border-radius: 12px; background: var(--light-bg); border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; font-size: 1.35rem; flex-shrink: 0; }
.genre-cell strong { display: block; }
.genre-cell span { display: block; color: var(--text-muted); font-size: .78rem; font-family: ui-monospace, Consolas, monospace; }
.genre-cell small { display: block; color: var(--text-secondary); font-size: .75rem; margin-top: .2rem; }
.pill { display: inline-flex; padding: .28rem .7rem; border-radius: 999px; font-size: .72rem; font-weight: 700; }
.pill-info { background: rgba(33,150,243,.18); color: #64b5f6; }
.dot-status { display: inline-flex; align-items: center; gap: .4rem; font-size: .8rem; font-weight: 600; }
.dot-status::before { content: ''; width: 8px; height: 8px; border-radius: 50%; background: currentColor; }
.status-active { color: var(--success-color); }
.status-inactive { color: var(--text-muted); }
.order-badge { color: var(--text-secondary); font-weight: 600; font-size: .85rem; }
.joined strong { display: block; font-size: .85rem; }
.joined span { color: var(--text-muted); font-size: .75rem; }
.btn-icon.danger:hover { background: var(--danger-color); border-color: var(--danger-color); color: #fff; }
.empty { text-align: center; padding: 3.5rem 1rem; color: var(--text-secondary); }
.empty i { font-size: 2.4rem; color: var(--primary-color); margin-bottom: .75rem; }
.empty h3 { margin-bottom: .35rem; color: #fff; }
.empty p { margin-bottom: 1.25rem; }
.genre-modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.85); z-index: 1000; align-items: center; justify-content: center; padding: 1rem; }
.genre-modal.active { display: flex; }
.genre-modal-panel { width: 100%; max-width: 520px; background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 16px; overflow: hidden; }
.genre-modal-header { display: flex; justify-content: space-between; align-items: center; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-color); }
.genre-modal-header h2 { font-size: 1.15rem; }
.modal-close { width: 36px; height: 36px; border: 1px solid var(--border-color); background: var(--light-bg); color: var(--text-secondary); border-radius: 8px; cursor: pointer; }
.modal-close:hover { background: var(--danger-color); color: #fff; border-color: var(--danger-color); }
.genre-modal-body { padding: 1.5rem; }
.field { display: flex; flex-direction: column; gap: .45rem; margin-bottom: 1rem; }
.field label { font-weight: 600; font-size: .85rem; color: var(--text-secondary); }
.field input, .field textarea, .field select { background: var(--light-bg); border: 1px solid var(--border-color); color: #fff; border-radius: 10px; padding: .75rem .9rem; width: 100%; }
.field input:focus, .field textarea:focus, .field select:focus { outline: none; border-color: var(--primary-color); }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.form-actions { display: flex; justify-content: flex-end; gap: .75rem; margin-top: .5rem; }
@media (max-width: 700px) { .form-row { grid-template-columns: 1fr; } }
</style>
@endsection

@section('scripts')
<script>
const genresData = @json($genres->keyBy('id'));

function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Add Genre';
    document.getElementById('genreForm').reset();
    document.getElementById('genreId').value = '';
    document.getElementById('genreSortOrder').value = '0';
    document.getElementById('genreStatus').value = '1';
    document.getElementById('genreModal').classList.add('active');
}

function closeModal() {
    document.getElementById('genreModal').classList.remove('active');
}

function editGenre(id) {
    const genre = genresData[id];
    if (!genre) return;

    document.getElementById('modalTitle').textContent = 'Edit Genre';
    document.getElementById('genreId').value = genre.id;
    document.getElementById('genreName').value = genre.name;
    document.getElementById('genreDescription').value = genre.description || '';
    document.getElementById('genreIcon').value = genre.icon || '';
    document.getElementById('genreSortOrder').value = genre.sort_order ?? 0;
    document.getElementById('genreStatus').value = genre.is_active ? '1' : '0';
    document.getElementById('genreModal').classList.add('active');
}

function saveGenre(event) {
    event.preventDefault();

    const genreId = document.getElementById('genreId').value;
    const payload = {
        name: document.getElementById('genreName').value,
        description: document.getElementById('genreDescription').value,
        icon: document.getElementById('genreIcon').value,
        sort_order: parseInt(document.getElementById('genreSortOrder').value, 10) || 0,
        is_active: document.getElementById('genreStatus').value === '1'
    };

    fetch(genreId ? `/admin/genres/${genreId}` : '{{ route('admin.genres.store') }}', {
        method: genreId ? 'PUT' : 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(payload)
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) throw new Error(data.message || 'Could not save genre.');
        return data;
    })
    .then(() => window.location.reload())
    .catch(error => alert(error.message || 'Could not save genre.'));
}

function deleteGenre(id) {
    if (!confirm('Delete this genre?')) return;

    fetch(`/admin/genres/${id}`, {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) throw new Error(data.message || 'Could not delete genre.');
        return data;
    })
    .then(() => window.location.reload())
    .catch(error => alert(error.message || 'Could not delete genre.'));
}

document.getElementById('genreModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
@endsection
