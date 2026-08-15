@extends('layouts.admin')

@section('title', 'Admin Dashboard - KUN Movie')

@section('content')
<div class="admin-dashboard">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-chart-line"></i> Dashboard Overview
            </h1>
            <p class="page-subtitle">Welcome back, {{ auth()->user()->name }}! Here's what's happening with your platform.</p>
        </div>
        <div class="header-actions">
            <button class="btn-refresh" onclick="refreshStats()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
            <a href="{{ route('home') }}" class="btn-secondary">
                <i class="fas fa-home"></i> View Site
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <!-- Total Movies -->
        <div class="stat-card stat-primary">
            <div class="stat-icon">
                <i class="fas fa-film"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-value" id="total-movies">{{ $stats['total_movies'] ?? 0 }}</h3>
                <p class="stat-label">Total Movies</p>
                <div class="stat-meta">
                    <span class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> 12 this month
                    </span>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="stat-card stat-success">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-value" id="total-users">{{ $stats['total_users'] ?? 0 }}</h3>
                <p class="stat-label">Total Users</p>
                <div class="stat-meta">
                    <span class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> 24% growth
                    </span>
                </div>
            </div>
        </div>

        <!-- Total Views -->
        <div class="stat-card stat-info">
            <div class="stat-icon">
                <i class="fas fa-eye"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-value" id="total-views">{{ $stats['total_views'] ?? 0 }}</h3>
                <p class="stat-label">Total Views</p>
                <div class="stat-meta">
                    <span class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> 1.2K today
                    </span>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="stat-card stat-warning">
            <div class="stat-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-value" id="total-revenue">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</h3>
                <p class="stat-label">Total Revenue</p>
                <div class="stat-meta">
                    <span class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> $450 today
                    </span>
                </div>
            </div>
        </div>

        <!-- Active Subscriptions -->
        <div class="stat-card stat-purple">
            <div class="stat-icon">
                <i class="fas fa-crown"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-value">{{ $stats['active_subscriptions'] ?? 0 }}</h3>
                <p class="stat-label">Active Subscriptions</p>
                <div class="stat-meta">
                    <span class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> 18% increase
                    </span>
                </div>
            </div>
        </div>

        <!-- New Users Today -->
        <div class="stat-card stat-danger">
            <div class="stat-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-value">{{ $stats['new_users_today'] ?? 0 }}</h3>
                <p class="stat-label">New Users Today</p>
                <div class="stat-meta">
                    <span class="stat-change">
                        <i class="fas fa-clock"></i> Last 24 hours
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="charts-row">
        <!-- User Growth Chart -->
        <div class="chart-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-area"></i> User Growth
                </h3>
                <div class="card-actions">
                    <select class="period-select">
                        <option>Last 7 Days</option>
                        <option selected>Last 30 Days</option>
                        <option>Last 3 Months</option>
                        <option>Last Year</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <canvas id="userGrowthChart"></canvas>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="chart-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-line"></i> Revenue Overview
                </h3>
                <div class="card-actions">
                    <select class="period-select">
                        <option>This Week</option>
                        <option selected>This Month</option>
                        <option>This Year</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="content-row">
        <!-- Popular Movies -->
        <div class="content-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-fire"></i> Top 10 Popular Movies
                </h3>
                <a href="{{ route('admin.movies.index') }}" class="btn-link">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Movie</th>
                                <th>Views</th>
                                <th>Rating</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($popularMovies ?? [] as $index => $movie)
                            <tr>
                                <td>
                                    <span class="rank-badge rank-{{ $index + 1 }}">{{ $index + 1 }}</span>
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
                                    <span class="badge badge-info">
                                        <i class="fas fa-eye"></i> {{ number_format($movie->view_count ?? 0) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="rating-badge">
                                        <i class="fas fa-star"></i> {{ number_format($movie->rating ?? 0, 1) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.movies.edit', $movie->id) }}" class="btn-icon" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('movie.show', $movie->id) }}" class="btn-icon" title="View" target="_blank">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No movies found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="content-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-users"></i> Recent Users
                </h3>
                <a href="{{ route('admin.users.index') }}" class="btn-link">View All</a>
            </div>
            <div class="card-body">
                <div class="user-list">
                    @forelse($recentUsers ?? [] as $user)
                    <div class="user-item">
                        <div class="user-avatar">
                            @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                            @else
                            <div class="avatar-placeholder">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                            @endif
                        </div>
                        <div class="user-info">
                            <h4 class="user-name">{{ $user->name }}</h4>
                            <p class="user-email">{{ $user->email }}</p>
                            <p class="user-meta">
                                <span class="badge badge-{{ $user->subscription_status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($user->subscription_plan ?? 'free') }}
                                </span>
                                <small class="text-muted">Joined {{ $user->created_at->diffForHumans() }}</small>
                            </p>
                        </div>
                        <div class="user-actions">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn-icon">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-muted">No recent users</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Quick Stats -->
    <div class="content-row">
        <!-- Recent Payments -->
        <div class="content-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-credit-card"></i> Recent Payments
                </h3>
                <a href="{{ route('admin.payments.index') }}" class="btn-link">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Plan</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPayments ?? [] as $payment)
                            <tr>
                                <td>{{ $payment->user->name ?? 'N/A' }}</td>
                                <td><span class="badge badge-info">{{ ucfirst($payment->plan ?? 'N/A') }}</span></td>
                                <td><strong>${{ number_format($payment->amount ?? 0, 2) }}</strong></td>
                                <td>
                                    <span class="status-badge status-{{ strtolower($payment->status ?? 'pending') }}">
                                        {{ ucfirst($payment->status ?? 'Pending') }}
                                    </span>
                                </td>
                                <td><small>{{ $payment->created_at->format('M d, Y') }}</small></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No recent payments</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="content-card quick-actions-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bolt"></i> Quick Actions
                </h3>
            </div>
            <div class="card-body">
                <div class="quick-actions-grid">
                    <a href="{{ route('admin.movies.create') }}" class="quick-action-btn">
                        <div class="quick-action-icon bg-primary">
                            <i class="fas fa-plus"></i>
                        </div>
                        <span>Add New Movie</span>
                    </a>
                    <a href="{{ route('admin.genres.create') }}" class="quick-action-btn">
                        <div class="quick-action-icon bg-success">
                            <i class="fas fa-tag"></i>
                        </div>
                        <span>Create Genre</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="quick-action-btn">
                        <div class="quick-action-icon bg-info">
                            <i class="fas fa-users"></i>
                        </div>
                        <span>Manage Users</span>
                    </a>
                    <a href="{{ route('admin.payments.index') }}" class="quick-action-btn">
                        <div class="quick-action-icon bg-warning">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <span>View Payments</span>
                    </a>
                    <a href="{{ route('admin.stats.refresh') }}" class="quick-action-btn">
                        <div class="quick-action-icon bg-purple">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <span>View Reports</span>
                    </a>
                    <a href="{{ route('admin.export') }}" class="quick-action-btn">
                        <div class="quick-action-icon bg-danger">
                            <i class="fas fa-download"></i>
                        </div>
                        <span>Export Data</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// User Growth Chart
const userGrowthCtx = document.getElementById('userGrowthChart');
if (userGrowthCtx) {
    new Chart(userGrowthCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'New Users',
                data: [65, 78, 90, 110, 134, 156, 189, 220, 245, 289, 320, 356],
                borderColor: '#46d369',
                backgroundColor: 'rgba(70, 211, 105, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(255, 255, 255, 0.05)'
                    },
                    ticks: {
                        color: '#b3b3b3'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(255, 255, 255, 0.05)'
                    },
                    ticks: {
                        color: '#b3b3b3'
                    }
                }
            }
        }
    });
}

// Revenue Chart
const revenueCtx = document.getElementById('revenueChart');
if (revenueCtx) {
    new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [{
                label: 'Revenue ($)',
                data: [1200, 1900, 1500, 2100],
                backgroundColor: [
                    'rgba(229, 9, 20, 0.8)',
                    'rgba(255, 165, 0, 0.8)',
                    'rgba(70, 211, 105, 0.8)',
                    'rgba(33, 150, 243, 0.8)'
                ],
                borderColor: [
                    '#e50914',
                    '#ffa500',
                    '#46d369',
                    '#2196f3'
                ],
                borderWidth: 2,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(255, 255, 255, 0.05)'
                    },
                    ticks: {
                        color: '#b3b3b3',
                        callback: function(value) {
                            return '$' + value;
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#b3b3b3'
                    }
                }
            }
        }
    });
}

// Refresh Stats Function
function refreshStats() {
    const btn = document.querySelector('.btn-refresh i');
    btn.classList.add('fa-spin');
    
    fetch('{{ route('admin.stats.refresh') }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('total-movies').textContent = data.total_movies || 0;
            document.getElementById('total-users').textContent = data.total_users || 0;
            document.getElementById('total-views').textContent = data.total_views || 0;
            document.getElementById('total-revenue').textContent = '$' + (data.total_revenue || 0).toFixed(2);
            
            setTimeout(() => {
                btn.classList.remove('fa-spin');
            }, 500);
        })
        .catch(error => {
            console.error('Error refreshing stats:', error);
            btn.classList.remove('fa-spin');
        });
}
</script>
@endsection
