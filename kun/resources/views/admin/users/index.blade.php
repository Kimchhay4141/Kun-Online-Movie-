@extends('layouts.admin')

@section('title', 'Users Management - Admin')

@section('content')
<div class="users-page">
    <div class="dashboard-header">
        <div>
            <h1 class="page-title"><i class="fas fa-users"></i> Users</h1>
            <p class="page-subtitle">Search, filter, and manage every account on the platform</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.users.create') }}" class="btn-add">
                <i class="fas fa-plus"></i> Add User
            </a>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card stat-info">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($totalUsers) }}</div>
                <div class="stat-label">Total Users</div>
            </div>
        </div>
        <div class="stat-card stat-success">
            <div class="stat-icon"><i class="fas fa-user-check"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($activeUsers) }}</div>
                <div class="stat-label">Active</div>
            </div>
        </div>
        <div class="stat-card stat-warning">
            <div class="stat-icon"><i class="fas fa-crown"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($premiumUsers) }}</div>
                <div class="stat-label">Premium</div>
            </div>
        </div>
        <div class="stat-card stat-primary">
            <div class="stat-icon"><i class="fas fa-user-plus"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($newUsersToday) }}</div>
                <div class="stat-label">Joined Today</div>
            </div>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.users.index') }}" class="toolbar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email...">
        </div>
        <select name="role">
            <option value="">All roles</option>
            @foreach($roles as $role)
            <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
            @endforeach
        </select>
        <select name="subscription">
            <option value="">All plans</option>
            <option value="free" {{ request('subscription') == 'free' ? 'selected' : '' }}>Free</option>
            <option value="basic" {{ request('subscription') == 'basic' ? 'selected' : '' }}>Basic</option>
            <option value="premium" {{ request('subscription') == 'premium' ? 'selected' : '' }}>Premium</option>
        </select>
        <select name="status">
            <option value="">All status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
        </select>
        <button type="submit" class="btn-apply">Filter</button>
        @if(request()->hasAny(['search', 'role', 'subscription', 'status']))
        <a href="{{ route('admin.users.index') }}" class="btn-reset">Clear</a>
        @endif
    </form>

    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-list"></i> All Users <span class="count">{{ $users->total() }}</span></h3>
        </div>
        <div class="table-responsive">
            <table class="data-table users-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Plan</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="user-cell">
                                <div class="avatar">
                                    @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                                    @else
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                    @endif
                                </div>
                                <div>
                                    <strong>{{ $user->name }}</strong>
                                    <span>{{ $user->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            @forelse($user->roles as $role)
                            <span class="pill pill-{{ strtolower($role->name) }}">{{ $role->name }}</span>
                            @empty
                            <span class="pill pill-user">User</span>
                            @endforelse
                        </td>
                        <td>
                            <span class="pill pill-plan-{{ strtolower($user->subscription_plan ?? 'free') }}">
                                {{ ucfirst($user->subscription_plan ?? 'Free') }}
                            </span>
                        </td>
                        <td>
                            <span class="dot-status status-{{ strtolower($user->subscription_status ?? 'inactive') }}">
                                {{ ucfirst($user->subscription_status ?? 'Inactive') }}
                            </span>
                        </td>
                        <td>
                            <div class="joined">
                                <strong>{{ $user->created_at->format('M d, Y') }}</strong>
                                <span>{{ $user->created_at->diffForHumans() }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn-icon" title="View"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-icon" title="Edit"><i class="fas fa-pen"></i></a>
                                @unless($user->isAdmin() || $user->id === auth()->id())
                                <button type="button" class="btn-icon danger" onclick="suspendUser({{ $user->id }})" title="Suspend"><i class="fas fa-ban"></i></button>
                                @endunless
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty">
                                <i class="fas fa-users"></i>
                                <h3>No users found</h3>
                                <p>Try a different search or add a new user.</p>
                                <a href="{{ route('admin.users.create') }}" class="btn-add">Add User</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="pagination-wrap">
            {{ $users->links('pagination.admin') }}
        </div>
        @endif
    </div>
</div>

<style>
.btn-add {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.7rem 1.25rem;
    background: var(--primary-color);
    color: #fff;
    border-radius: 10px;
    font-weight: 700;
    text-decoration: none;
    border: none;
    cursor: pointer;
}
.btn-add:hover { background: #f40612; }

.toolbar {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
    margin-bottom: 1.25rem;
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 14px;
    padding: 1rem;
}
.search-box {
    flex: 1;
    min-width: 220px;
    position: relative;
}
.search-box i {
    position: absolute;
    left: 0.9rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
}
.search-box input,
.toolbar select {
    width: 100%;
    background: var(--light-bg);
    border: 1px solid var(--border-color);
    color: #fff;
    border-radius: 10px;
    padding: 0.7rem 0.9rem;
    outline: none;
}
.search-box input { padding-left: 2.4rem; }
.toolbar select { width: auto; min-width: 140px; }
.search-box input:focus,
.toolbar select:focus { border-color: var(--primary-color); }
.btn-apply, .btn-reset {
    padding: 0.7rem 1.1rem;
    border-radius: 10px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}
.btn-apply { background: var(--primary-color); color: #fff; }
.btn-reset { background: var(--light-bg); color: var(--text-secondary); border: 1px solid var(--border-color); }

.count {
    margin-left: 0.4rem;
    background: var(--light-bg);
    color: var(--text-secondary);
    font-size: 0.75rem;
    padding: 0.15rem 0.55rem;
    border-radius: 999px;
}

.users-table td { vertical-align: middle; }
.user-cell { display: flex; align-items: center; gap: 0.85rem; }
.avatar {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    overflow: hidden;
    background: linear-gradient(135deg, var(--primary-color), #831010);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    flex-shrink: 0;
}
.avatar img { width: 100%; height: 100%; object-fit: cover; }
.user-cell strong { display: block; }
.user-cell span { color: var(--text-muted); font-size: 0.8rem; }

.pill {
    display: inline-flex;
    padding: 0.28rem 0.7rem;
    border-radius: 999px;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: capitalize;
}
.pill-admin, .pill-Admin { background: rgba(229,9,20,.18); color: #ff6b73; }
.pill-moderator { background: rgba(156,39,176,.18); color: #ce93d8; }
.pill-user, .pill-subscriber { background: rgba(33,150,243,.18); color: #64b5f6; }
.pill-plan-free { background: rgba(128,128,128,.18); color: #bdbdbd; }
.pill-plan-basic { background: rgba(33,150,243,.18); color: #64b5f6; }
.pill-plan-premium { background: rgba(255,193,7,.18); color: #ffc107; }

.dot-status {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.8rem;
    font-weight: 600;
}
.dot-status::before {
    content: '';
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: currentColor;
}
.status-active { color: var(--success-color); }
.status-inactive { color: var(--text-muted); }
.status-suspended { color: var(--danger-color); }

.joined strong { display: block; font-size: 0.85rem; }
.joined span { color: var(--text-muted); font-size: 0.75rem; }

.btn-icon.danger:hover { background: var(--danger-color); border-color: var(--danger-color); color: #fff; }

.empty {
    text-align: center;
    padding: 3.5rem 1rem;
    color: var(--text-secondary);
}
.empty i { font-size: 2.4rem; color: var(--primary-color); margin-bottom: 0.75rem; }
.empty h3 { margin-bottom: 0.35rem; color: #fff; }
.empty p { margin-bottom: 1.25rem; }

.pagination-wrap { padding: 1rem 1.5rem; border-top: 1px solid var(--border-color); }
.pagination-wrap nav, .pagination-wrap .pagination { display: flex; gap: 0.4rem; flex-wrap: wrap; }
.pagination-wrap a, .pagination-wrap span {
    padding: 0.4rem 0.75rem;
    border-radius: 8px;
    color: var(--text-secondary);
    text-decoration: none;
    background: var(--light-bg);
}
.pagination-wrap .active span, .pagination-wrap [aria-current] span {
    background: var(--primary-color);
    color: #fff;
}

@media (max-width: 768px) {
    .toolbar select { flex: 1; min-width: 0; }
}
</style>
@endsection

@section('scripts')
<script>
function suspendUser(userId) {
    if (!confirm('Suspend this user?')) return;

    fetch(`/admin/users/${userId}/suspend`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.message || 'Could not suspend this user.');
        }
    })
    .catch(() => alert('Could not suspend this user.'));
}
</script>
@endsection
