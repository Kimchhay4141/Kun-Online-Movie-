@extends('layouts.admin')

@section('title', 'Genres Management - Admin')

@section('content')
<div class="admin-genres">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-tags"></i> Genres Management
            </h1>
            <p class="page-subtitle">Manage movie genres and categories</p>
        </div>
        <div class="header-actions">
            <button class="btn-primary" onclick="openCreateModal()">
                <i class="fas fa-plus"></i> Add New Genre
            </button>
        </div>
    </div>

    <!-- Genres Grid -->
    <div class="genres-management-grid">
        @forelse($genres ?? [] as $genre)
        <div class="genre-card-manage">
            <div class="genre-card-header">
                <div class="genre-icon-large">
                    @if($genre->icon)
                    {{ $genre->icon }}
                    @else
                    <i class="fas fa-film"></i>
                    @endif
                </div>
                <div class="genre-card-actions">
                    <button class="btn-icon-small" onclick="editGenre({{ $genre->id }})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-icon-small btn-danger" onclick="deleteGenre({{ $genre->id }})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="genre-card-body">
                <h3>{{ $genre->name }}</h3>
                <p>{{ $genre->description ?? 'No description' }}</p>
                <div class="genre-meta">
                    <span class="meta-badge">
                        <i class="fas fa-film"></i> {{ $genre->movies_count ?? 0 }} movies
                    </span>
                    <span class="meta-badge {{ $genre->is_active ? 'active' : 'inactive' }}">
                        <i class="fas fa-circle"></i> {{ $genre->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state-full">
            <i class="fas fa-tags"></i>
            <h3>No genres yet</h3>
            <p>Create your first genre to organize movies</p>
            <button class="btn-primary" onclick="openCreateModal()">
                <i class="fas fa-plus"></i> Create First Genre
            </button>
        </div>
        @endforelse
    </div>
</div>

<!-- Create/Edit Modal -->
<div id="genreModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Add New Genre</h2>
            <button class="modal-close" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="genreForm" onsubmit="saveGenre(event)">
                <input type="hidden" id="genreId">
                
                <div class="form-group">
                    <label>Genre Name *</label>
                    <input type="text" id="genreName" required placeholder="e.g., Action, Drama">
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea id="genreDescription" rows="3" placeholder="Brief description of the genre"></textarea>
                </div>

                <div class="form-group">
                    <label>Icon (Emoji or Font Awesome)</label>
                    <input type="text" id="genreIcon" placeholder="🎬 or <i class='fas fa-film'></i>">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Sort Order</label>
                        <input type="number" id="genreSortOrder" value="0" min="0">
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select id="genreStatus">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-secondary" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn-primary">Save Genre</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.genres-management-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
}

.genre-card-manage {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s;
}

.genre-card-manage:hover {
    transform: translateY(-5px);
    border-color: var(--primary-color);
}

.genre-card-header {
    padding: 1.5rem;
    background: var(--light-bg);
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid var(--border-color);
}

.genre-icon-large {
    font-size: 2.5rem;
}

.genre-card-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-icon-small {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 6px;
    color: var(--text-secondary);
    cursor: pointer;
    transition: all 0.3s;
}

.btn-icon-small:hover {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: white;
}

.btn-icon-small.btn-danger:hover {
    background: var(--danger-color);
    border-color: var(--danger-color);
}

.genre-card-body {
    padding: 1.5rem;
}

.genre-card-body h3 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
}

.genre-card-body p {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.genre-meta {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.meta-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.35rem 0.75rem;
    background: var(--light-bg);
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 600;
}

.meta-badge.active {
    background: rgba(70, 211, 105, 0.15);
    color: var(--success-color);
}

.meta-badge.inactive {
    background: rgba(255, 68, 68, 0.15);
    color: var(--danger-color);
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.85);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}

.modal.active {
    display: flex;
}

.modal-content {
    background: var(--card-bg);
    border-radius: 12px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    border: 1px solid var(--border-color);
}

.modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h2 {
    font-size: 1.5rem;
    font-weight: 700;
}

.modal-close {
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--light-bg);
    border: none;
    border-radius: 8px;
    color: var(--text-secondary);
    cursor: pointer;
    transition: all 0.3s;
}

.modal-close:hover {
    background: var(--danger-color);
    color: white;
}

.modal-body {
    padding: 1.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--text-secondary);
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    background: var(--light-bg);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 0.75rem;
    color: var(--text-primary);
    font-size: 0.95rem;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    outline: none;
    border-color: var(--primary-color);
}

.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
}

.empty-state-full {
    grid-column: 1 / -1;
    text-align: center;
    padding: 4rem 2rem;
}

.empty-state-full i {
    font-size: 4rem;
    color: var(--text-muted);
    margin-bottom: 1.5rem;
}

.empty-state-full h3 {
    font-size: 1.75rem;
    margin-bottom: 0.75rem;
}

.empty-state-full p {
    color: var(--text-secondary);
    margin-bottom: 2rem;
}
</style>
@endsection

@section('scripts')
<script>
function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Add New Genre';
    document.getElementById('genreForm').reset();
    document.getElementById('genreId').value = '';
    document.getElementById('genreModal').classList.add('active');
}

function closeModal() {
    document.getElementById('genreModal').classList.remove('active');
}

function editGenre(id) {
    // Fetch genre data and populate form
    alert('Edit genre functionality - ID: ' + id);
    // In real implementation, fetch genre data via AJAX and populate form
}

function saveGenre(event) {
    event.preventDefault();
    
    const formData = {
        name: document.getElementById('genreName').value,
        description: document.getElementById('genreDescription').value,
        icon: document.getElementById('genreIcon').value,
        sort_order: document.getElementById('genreSortOrder').value,
        is_active: document.getElementById('genreStatus').value === '1'
    };
    
    const genreId = document.getElementById('genreId').value;
    const url = genreId ? `/admin/genres/${genreId}` : '{{ route('admin.genres.store') }}';
    const method = genreId ? 'PUT' : 'POST';
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Genre saved successfully!');
            window.location.reload();
        } else {
            alert('Error saving genre');
        }
    })
    .catch(error => {
        alert('Error saving genre');
        console.error(error);
    });
}

function deleteGenre(id) {
    if (!confirm('Are you sure you want to delete this genre?')) {
        return;
    }
    
    fetch(`/admin/genres/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Genre deleted successfully');
            window.location.reload();
        } else {
            alert('Error deleting genre');
        }
    });
}

// Close modal on outside click
document.getElementById('genreModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection
