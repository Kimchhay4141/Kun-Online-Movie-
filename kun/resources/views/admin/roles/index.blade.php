@extends('layouts.admin')

@section('title', 'Roles Management - Admin')

@section('content')
<div class="roles-page">
    <div class="dashboard-header">
        <div>
            <h1 class="page-title"><i class="fas fa-user-tag"></i> Roles</h1>
            <p class="page-subtitle">Create roles and assign permissions to them</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.roles.create') }}" class="btn-add">
                <i class="fas fa-plus"></i> Add Role
            </a>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card stat-info">
            <div class="stat-icon"><i class="fas fa-user-tag"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($totalRoles) }}</div>
                <div class="stat-label">Total Roles</div>
            </div>
        </div>
        <div class="stat-card stat-success">
            <div class="stat-icon"><i class="fas fa-user-check"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($assignedRoles) }}</div>
                <div class="stat-label">Assigned to Users</div>
            </div>
        </div>
        <div class="stat-card stat-warning">
            <div class="stat-icon"><i class="fas fa-shield-alt"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($systemRoles) }}</div>
                <div class="stat-label">System Roles</div>
            </div>
        </div>
        <div class="stat-card stat-primary">
            <div class="stat-icon"><i class="fas fa-plus"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($newRolesToday) }}</div>
                <div class="stat-label">Created Today</div>
            </div>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.roles.index') }}" class="toolbar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search role name or description...">
        </div>
        <button type="submit" class="btn-apply">Filter</button>
        @if(request()->filled('search'))
        <a href="{{ route('admin.roles.index') }}" class="btn-reset">Clear</a>
        @endif
    </form>

    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-list"></i> All Roles <span class="count">{{ $roles->total() }}</span></h3>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Role</th>
                        <th>Permissions</th>
                        <th>Users</th>
                        <th>Created</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                    <tr>
                        <td>
                            <div class="name-cell">
                                <strong>{{ $role->name }}</strong>
                                <span>{{ $role->description ?: $role->slug }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="pill pill-info">{{ $role->permissions_count }} permissions</span>
                        </td>
                        <td>
                            <span class="pill pill-user">{{ $role->users_count }} users</span>
                        </td>
                        <td>
                            <div class="joined">
                                <strong>{{ $role->created_at->format('M d, Y') }}</strong>
                                <span>{{ $role->created_at->diffForHumans() }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.roles.show', $role) }}" class="btn-icon" title="View"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.roles.edit', $role) }}" class="btn-icon" title="Edit"><i class="fas fa-pen"></i></a>
                                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Delete this role?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon danger" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty">
                                <i class="fas fa-user-tag"></i>
                                <h3>No roles found</h3>
                                <p>Try a different search or add a new role.</p>
                                <a href="{{ route('admin.roles.create') }}" class="btn-add">Add Role</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($roles->hasPages())
        <div class="pagination-wrap">{{ $roles->links('pagination.admin') }}</div>
        @endif
    </div>
</div>
<style>
.btn-add { display: inline-flex; align-items: center; gap: .5rem; padding: .7rem 1.25rem; background: var(--primary-color); color: #fff; border-radius: 10px; font-weight: 700; text-decoration: none; border: none; cursor: pointer; }
.btn-add:hover { background: #f40612; }
.toolbar { display: flex; gap: .75rem; flex-wrap: wrap; margin-bottom: 1.25rem; background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 14px; padding: 1rem; }
.search-box { flex: 1; min-width: 220px; position: relative; }
.search-box i { position: absolute; left: .9rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); }
.search-box input { width: 100%; background: var(--light-bg); border: 1px solid var(--border-color); color: #fff; border-radius: 10px; padding: .7rem .9rem .7rem 2.4rem; outline: none; }
.search-box input:focus { border-color: var(--primary-color); }
.btn-apply, .btn-reset { padding: .7rem 1.1rem; border-radius: 10px; font-weight: 600; border: none; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; }
.btn-apply { background: var(--primary-color); color: #fff; }
.btn-reset { background: var(--light-bg); color: var(--text-secondary); border: 1px solid var(--border-color); }
.count { margin-left: .4rem; background: var(--light-bg); color: var(--text-secondary); font-size: .75rem; padding: .15rem .55rem; border-radius: 999px; }
.name-cell strong { display: block; }
.name-cell span { color: var(--text-muted); font-size: .8rem; }
.pill { display: inline-flex; padding: .28rem .7rem; border-radius: 999px; font-size: .72rem; font-weight: 700; }
.pill-info { background: rgba(33,150,243,.18); color: #64b5f6; }
.pill-user { background: rgba(156,39,176,.18); color: #ce93d8; }
.joined strong { display: block; font-size: .85rem; }
.joined span { color: var(--text-muted); font-size: .75rem; }
.action-buttons form { margin: 0; }
.btn-icon.danger:hover { background: var(--danger-color); border-color: var(--danger-color); color: #fff; }
.empty { text-align: center; padding: 3.5rem 1rem; color: var(--text-secondary); }
.empty i { font-size: 2.4rem; color: var(--primary-color); margin-bottom: .75rem; }
.empty h3 { margin-bottom: .35rem; color: #fff; }
.empty p { margin-bottom: 1.25rem; }
.pagination-wrap { padding: 1rem 1.5rem; border-top: 1px solid var(--border-color); }
.pagination-wrap nav, .pagination-wrap .pagination { display: flex; gap: .4rem; flex-wrap: wrap; }
.pagination-wrap a, .pagination-wrap span { padding: .4rem .75rem; border-radius: 8px; color: var(--text-secondary); text-decoration: none; background: var(--light-bg); }
.pagination-wrap .active span, .pagination-wrap [aria-current] span { background: var(--primary-color); color: #fff; }
</style>
@endsection
