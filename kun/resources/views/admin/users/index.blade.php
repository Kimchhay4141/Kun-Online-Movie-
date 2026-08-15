@extends('layouts.admin')

@section('title', 'Users Management - Admin')

@section('content')
<div class="admin-users">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-users"></i> Users Management
            </h1>
            <p class="page-subtitle">Manage all registered users</p>
        </div>
        <div class="header-actions">
            <button class="btn-secondary" onclick="exportUsers()">
                <i class="fas fa-download"></i> Export
            </button>
            <button class="btn-secondary" onclick="toggleFilters()">
                <i class="fas fa-filter"></i> Filters
            </button>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section" id="filtersSection" style="display: none;">
        <form method="GET" action="{{ route('admin.users.index') }}" class="filters-form">
            <div class="filter-group">
                <label>Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email...">
            </div>
            <div class="filter-group">
                <label>Role</label>
                <select name="role">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                    <option value="moderator" {{ request('role') == 'moderator' ? 'selected' : '' }}>Moderator</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Subscription</label>
                <select name="subscription">
                    <option value="">All Plans</option>
                    <option value="free" {{ request('subscription') == 'free' ? 'selected' : '' }}>Free</option>
                    <option value="basic" {{ request('subscription') == 'basic' ? 'selected' : '' }}>Basic</option>
                    <option value="premium" {{ request('subscription') == 'premium' ? 'selected' : '' }}>Premium</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Status</label>
                <select name="status">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-apply">Apply</button>
                <a href="{{ route('admin.users.index') }}" class="btn-reset">Reset</a>
            </div>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="stats-row">
        <div class="stat-card-small">
            <div class="stat-icon bg-primary">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h4>{{ $totalUsers ?? 0 }}</h4>
                <p>Total Users</p>
            </div>
        </div>
        <div class="stat-card-small">
            <div class="stat-icon bg-success">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-info">
                <h4>{{ $activeUsers ?? 0 }}</h4>
                <p>Active Users</p>
            </div>
        </div>
        <div class="stat-card-small">
            <div class="stat-icon bg-warning">
                <i class="fas fa-crown"></i>
            </div>
            <div class="stat-info">
                <h4>{{ $premiumUsers ?? 0 }}</h4>
                <p>Premium Users</p>
            </div>
        </div>
        <div class="stat-card-small">
            <div class="stat-icon bg-info">
                <i class="fas fa-user-plus"></i>
            </div>
            <div class="stat-info">
                <h4>{{ $newUsersToday ?? 0 }}</h4>
                <p>New Today</p>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-list"></i> All Users ({{ $users->total() }})
            </h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Subscription</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="user-info-cell">
                                    <div class="user-avatar-small">
                                        @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                                        @else
                                        <div class="avatar-placeholder-small">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                        @endif
                                    </div>
                                    <div>
                                        <strong>{{ $user->name }}</strong>
                                        <small>ID: {{ $user->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->roles->count() > 0)
                                    @foreach($user->roles as $role)
                                    <span class="role-badge role-{{ strtolower($role->name) }}">
                                        {{ ucfirst($role->name) }}
                                    </span>
                                    @endforeach
                                @else
                                <span class="role-badge role-user">User</span>
                                @endif
                            </td>
                            <td>
                                <span class="subscription-badge subscription-{{ strtolower($user->subscription_plan ?? 'free') }}">
                                    <i class="fas fa-{{ ($user->subscription_plan == 'premium') ? 'crown' : (($user->subscription_plan == 'basic') ? 'star' : 'user') }}"></i>
                                    {{ ucfirst($user->subscription_plan ?? 'Free') }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-{{ strtolower($user->subscription_status ?? 'inactive') }}">
                                    {{ ucfirst($user->subscription_status ?? 'Inactive') }}
                                </span>
                            </td>
                            <td>
                                <small>{{ $user->created_at->format('M d, Y') }}</small>
                                <br>
                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn-icon" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="btn-icon" onclick="editUser({{ $user->id }})" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @if(!$user->hasRole('admin'))
                                    <button class="btn-icon btn-danger" onclick="suspendUser({{ $user->id }})" title="Suspend">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="empty-state-small">
                                    <i class="fas fa-users"></i>
                                    <p>No users found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($users->hasPages())
            <div class="pagination-wrapper">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.user-info-cell {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.user-avatar-small {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
}

.user-avatar-small img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder-small {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1rem;
    color: white;
}

.user-info-cell strong {
    display: block;
    margin-bottom: 0.15rem;
}

.user-info-cell small {
    color: var(--text-muted);
    font-size: 0.75rem;
}

.role-badge {
    display: inline-block;
    padding: 0.3rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.role-admin {
    background: rgba(229, 9, 20, 0.15);
    color: var(--primary-color);
}

.role-user {
    background: rgba(33, 150, 243, 0.15);
    color: var(--info-color);
}

.role-moderator {
    background: rgba(156, 39, 176, 0.15);
    color: var(--purple-color);
}

.subscription-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.3rem 0.75rem;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 600;
}

.subscription-free {
    background: rgba(128, 128, 128, 0.15);
    color: var(--text-muted);
}

.subscription-basic {
    background: rgba(33, 150, 243, 0.15);
    color: var(--info-color);
}

.subscription-premium {
    background: linear-gradient(135deg, rgba(255, 215, 0, 0.2), rgba(255, 193, 7, 0.2));
    color: #ffa500;
    border: 1px solid rgba(255, 193, 7, 0.3);
}

.status-active {
    background: rgba(70, 211, 105, 0.15);
    color: var(--success-color);
}

.status-inactive {
    background: rgba(128, 128, 128, 0.15);
    color: var(--text-muted);
}

.status-suspended {
    background: rgba(255, 68, 68, 0.15);
    color: var(--danger-color);
}
</style>
@endsection

@section('scripts')
<script>
function toggleFilters() {
    const filters = document.getElementById('filtersSection');
    filters.style.display = filters.style.display === 'none' ? 'block' : 'none';
}

function editUser(userId) {
    // Redirect to edit page or open modal
    alert('Edit user functionality - ID: ' + userId);
    // window.location.href = `/admin/users/${userId}/edit`;
}

function suspendUser(userId) {
    if (!confirm('Are you sure you want to suspend this user?')) {
        return;
    }
    
    fetch(`/admin/users/${userId}/suspend`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('User suspended successfully');
            window.location.reload();
        } else {
            alert('Error suspending user');
        }
    })
    .catch(error => {
        alert('Error suspending user');
        console.error(error);
    });
}

function exportUsers() {
    window.location.href = '{{ route('admin.export') }}?type=users';
}
</script>
@endsection
