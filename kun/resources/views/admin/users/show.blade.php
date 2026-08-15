@extends('layouts.admin')

@section('title', 'User Details - Admin')

@section('content')
<div class="admin-user-detail">
    <!-- Back Button -->
    <div class="back-section">
        <a href="{{ route('admin.users.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
    </div>

    <!-- User Profile Card -->
    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar-large">
                @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                @else
                <div class="avatar-placeholder-large">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                @endif
            </div>
            <div class="profile-info">
                <h1>{{ $user->name }}</h1>
                <p class="user-email"><i class="fas fa-envelope"></i> {{ $user->email }}</p>
                <div class="user-badges">
                    @foreach($user->roles as $role)
                    <span class="role-badge role-{{ strtolower($role->name) }}">{{ ucfirst($role->name) }}</span>
                    @endforeach
                    <span class="subscription-badge subscription-{{ strtolower($user->subscription_plan ?? 'free') }}">
                        <i class="fas fa-crown"></i> {{ ucfirst($user->subscription_plan ?? 'Free') }}
                    </span>
                    <span class="status-badge status-{{ strtolower($user->subscription_status ?? 'inactive') }}">
                        {{ ucfirst($user->subscription_status ?? 'Inactive') }}
                    </span>
                </div>
            </div>
            <div class="profile-actions">
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-secondary"><i class="fas fa-edit"></i> Edit User</a>
                @unless($user->isAdmin() || $user->id === auth()->id())
                <button class="btn-danger" onclick="suspendUser()"><i class="fas fa-ban"></i> Suspend</button>
                @endunless
            </div>
        </div>

        <div class="profile-stats">
            <div class="stat-item">
                <div class="stat-icon bg-primary">
                    <i class="fas fa-heart"></i>
                </div>
                <div>
                    <h3>{{ $stats['total_favorites'] }}</h3>
                    <p>Favorites</p>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon bg-success">
                    <i class="fas fa-eye"></i>
                </div>
                <div>
                    <h3>{{ $stats['total_views'] }}</h3>
                    <p>Total Views</p>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon bg-warning">
                    <i class="fas fa-credit-card"></i>
                </div>
                <div>
                    <h3>{{ $stats['total_payments'] }}</h3>
                    <p>Payments</p>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon bg-info">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div>
                    <h3>${{ number_format($stats['total_spent'], 2) }}</h3>
                    <p>Total Spent</p>
                </div>
            </div>
        </div>
    </div>

    <!-- User Details Grid -->
    <div class="details-grid">
        <!-- Personal Information -->
        <div class="content-card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user"></i> Personal Information</h3>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <label>Full Name</label>
                        <p>{{ $user->name }}</p>
                    </div>
                    <div class="info-item">
                        <label>Email</label>
                        <p>{{ $user->email }}</p>
                    </div>
                    <div class="info-item">
                        <label>Phone</label>
                        <p>{{ $user->phone ?? 'Not provided' }}</p>
                    </div>
                    <div class="info-item">
                        <label>Date of Birth</label>
                        <p>{{ $user->date_of_birth ? $user->date_of_birth->format('M d, Y') : 'Not provided' }}</p>
                    </div>
                    <div class="info-item">
                        <label>Member Since</label>
                        <p>{{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                    <div class="info-item">
                        <label>Last Login</label>
                        <p>{{ $user->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subscription Information -->
        <div class="content-card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-crown"></i> Subscription</h3>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <label>Plan</label>
                        <p><strong>{{ ucfirst($user->subscription_plan ?? 'Free') }}</strong></p>
                    </div>
                    <div class="info-item">
                        <label>Status</label>
                        <p><span class="status-badge status-{{ strtolower($user->subscription_status ?? 'inactive') }}">{{ ucfirst($user->subscription_status ?? 'Inactive') }}</span></p>
                    </div>
                    <div class="info-item">
                        <label>Start Date</label>
                        <p>{{ $user->subscription_start ? $user->subscription_start->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    <div class="info-item">
                        <label>End Date</label>
                        <p>{{ $user->subscription_end ? $user->subscription_end->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    <div class="info-item">
                        <label>Auto Renew</label>
                        <p>{{ $user->auto_renew ? 'Yes' : 'No' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Tabs -->
    <div class="content-card">
        <div class="card-header">
            <div class="tabs-header">
                <button class="tab-btn active" onclick="switchTab('favorites')">Favorite Movies</button>
                <button class="tab-btn" onclick="switchTab('history')">Watch History</button>
                <button class="tab-btn" onclick="switchTab('payments')">Payment History</button>
            </div>
        </div>
        <div class="card-body">
            <!-- Favorites Tab -->
            <div id="favorites-tab" class="tab-content active">
                <div class="movies-grid">
                    @forelse($user->favorites->take(12) as $favorite)
                    <div class="movie-card-small">
                        <img src="{{ $favorite->movie->thumbnail ?? 'https://via.placeholder.com/200x300' }}" alt="{{ $favorite->movie->title }}">
                        <h4>{{ $favorite->movie->title }}</h4>
                        <p>{{ $favorite->created_at->format('M d, Y') }}</p>
                    </div>
                    @empty
                    <p class="text-muted">No favorite movies yet</p>
                    @endforelse
                </div>
            </div>

            <!-- History Tab -->
            <div id="history-tab" class="tab-content">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Movie</th>
                                <th>Watched On</th>
                                <th>Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->movieViews->take(10) as $view)
                            <tr>
                                <td>{{ $view->movie->title ?? 'N/A' }}</td>
                                <td>{{ $view->created_at->format('M d, Y H:i') }}</td>
                                <td>{{ $view->movie->duration ?? 'N/A' }} min</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">No watch history</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Payments Tab -->
            <div id="payments-tab" class="tab-content">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Plan</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->payments as $payment)
                            <tr>
                                <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                <td>{{ ucfirst($payment->plan ?? 'N/A') }}</td>
                                <td>${{ number_format($payment->amount ?? 0, 2) }}</td>
                                <td><span class="status-badge status-{{ strtolower($payment->status ?? 'pending') }}">{{ ucfirst($payment->status ?? 'Pending') }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No payment history</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.back-section {
    margin-bottom: 1.5rem;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: var(--light-bg);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    color: var(--text-primary);
    text-decoration: none;
    transition: all 0.3s;
}

.btn-back:hover {
    background: var(--hover-bg);
}

.profile-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 2rem;
}

.profile-header {
    padding: 2rem;
    display: flex;
    align-items: flex-start;
    gap: 2rem;
    border-bottom: 1px solid var(--border-color);
}

.profile-avatar-large {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
}

.profile-avatar-large img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder-large {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 2.5rem;
    color: white;
}

.profile-info {
    flex: 1;
}

.profile-info h1 {
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
}

.user-email {
    color: var(--text-secondary);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.user-badges {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.profile-actions {
    display: flex;
    gap: 0.75rem;
}

.profile-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 0;
    border-top: 1px solid var(--border-color);
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem 2rem;
    border-right: 1px solid var(--border-color);
}

.stat-item:last-child {
    border-right: none;
}

.stat-item h3 {
    font-size: 1.75rem;
    font-weight: 800;
    margin-bottom: 0.25rem;
}

.stat-item p {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.info-item label {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-muted);
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-item p {
    font-size: 1rem;
    color: var(--text-primary);
}

.tabs-header {
    display: flex;
    gap: 0.5rem;
}

.tab-btn {
    padding: 0.75rem 1.5rem;
    background: transparent;
    border: none;
    color: var(--text-secondary);
    font-weight: 600;
    cursor: pointer;
    border-bottom: 3px solid transparent;
    transition: all 0.3s;
}

.tab-btn.active {
    color: var(--primary-color);
    border-bottom-color: var(--primary-color);
}

.tab-content {
    display: none;
    padding-top: 1rem;
}

.tab-content.active {
    display: block;
}

.movies-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 1rem;
}

.movie-card-small {
    text-align: center;
}

.movie-card-small img {
    width: 100%;
    aspect-ratio: 2/3;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 0.5rem;
}

.movie-card-small h4 {
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.movie-card-small p {
    font-size: 0.75rem;
    color: var(--text-muted);
}

.btn-danger {
    background: var(--danger-color);
    color: white;
}

@media (max-width: 768px) {
    .profile-header {
        flex-direction: column;
    }
    
    .profile-stats {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .stat-item {
        border-right: none;
        border-bottom: 1px solid var(--border-color);
    }
    
    .details-grid {
        grid-template-columns: 1fr;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection

@section('scripts')
<script>
function switchTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById(tabName + '-tab').classList.add('active');
    event.target.classList.add('active');
}

function suspendUser() {
    if (!confirm('Are you sure you want to suspend this user?')) {
        return;
    }
    
    fetch('{{ route('admin.users.suspend', $user->id) }}', {
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
            alert('Error: ' + data.message);
        }
    });
}
</script>
@endsection
