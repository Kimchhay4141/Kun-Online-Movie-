@extends('layouts.admin')

@section('title', 'Admin Dashboard - KUN Movie')

@section('content')
<div class="admin-dashboard">
    <!-- Dashboard Header -->
    <div class="dashboard-header-section">
        <div class="header-content">
            <h1 class="dashboard-title">Dashboard</h1>
            <p class="dashboard-subtitle">Welcome back, Admin! Here's what's happening with your movie platform.</p>
        </div>
        <div class="date-selector">
            <button class="btn-date-range">
                <i class="fas fa-calendar-alt"></i>
                <span>May 15 - Jun 15, 2025</span>
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="stats-cards-grid">
        <!-- Movies Card -->
        <div class="stat-card-modern purple-gradient">
            <div class="stat-icon-wrapper purple">
                <i class="fas fa-film"></i>
            </div>
            <div class="stat-content-modern">
                <span class="stat-label-modern">Movies</span>
                <h3 class="stat-value-modern">{{ \App\Models\Movie::count() }}</h3>
                <div class="stat-change-modern positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+12.5% vs last 30 days</span>
                </div>
            </div>
        </div>

        <!-- Users Card -->
        <div class="stat-card-modern blue-gradient">
            <div class="stat-icon-wrapper blue">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content-modern">
                <span class="stat-label-modern">Users</span>
                <h3 class="stat-value-modern">{{ number_format(\App\Models\User::count()) }}</h3>
                <div class="stat-change-modern positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+18.3% vs last 30 days</span>
                </div>
            </div>
        </div>

        <!-- Roles Card -->
        <div class="stat-card-modern orange-gradient">
            <div class="stat-icon-wrapper orange">
                <i class="fas fa-user-tag"></i>
            </div>
            <div class="stat-content-modern">
                <span class="stat-label-modern">Roles</span>
                <h3 class="stat-value-modern">{{ \App\Models\Role::count() }}</h3>
                <div class="stat-change-modern positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+8% vs last 30 days</span>
                </div>
            </div>
        </div>

        <!-- Permissions Card -->
        <div class="stat-card-modern green-gradient">
            <div class="stat-icon-wrapper green">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="stat-content-modern">
                <span class="stat-label-modern">Permissions</span>
                <h3 class="stat-value-modern">{{ \App\Models\Permission::count() }}</h3>
                <div class="stat-change-modern positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+7.7% vs last 30 days</span>
                </div>
            </div>
        </div>

        <!-- Subscriptions Card -->
        <div class="stat-card-modern pink-gradient">
            <div class="stat-icon-wrapper pink">
                <i class="fas fa-crown"></i>
            </div>
            <div class="stat-content-modern">
                <span class="stat-label-modern">Subscriptions</span>
                <h3 class="stat-value-modern">{{ \App\Models\User::where('subscription_status', 'active')->count() }}</h3>
                <div class="stat-change-modern positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+22.1% vs last 30 days</span>
                </div>
            </div>
        </div>

        <!-- New Users Card -->
        <div class="stat-card-modern teal-gradient">
            <div class="stat-icon-wrapper teal">
                <i class="fas fa-user-plus"></i>
            </div>
            <div class="stat-content-modern">
                <span class="stat-label-modern">New Users</span>
                <h3 class="stat-value-modern">{{ \App\Models\User::whereDate('created_at', '>=', now()->subDays(30))->count() }}</h3>
                <div class="stat-change-modern positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+28.9% vs last 30 days</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions-section">
        <h2 class="section-title">
            <i class="fas fa-bolt"></i>
            Quick Actions
        </h2>
        <div class="quick-actions-grid-modern">
            <a href="{{ route('admin.movies.create') }}" class="quick-action-card">
                <div class="qa-icon purple">
                    <i class="fas fa-plus"></i>
                </div>
                <span>Add Movie</span>
                <small>Add new movie</small>
            </a>
            <a href="{{ route('admin.users.index') }}" class="quick-action-card">
                <div class="qa-icon blue">
                    <i class="fas fa-users"></i>
                </div>
                <span>Manage Users</span>
                <small>View all users</small>
            </a>
            <a href="{{ route('admin.roles.index') }}" class="quick-action-card">
                <div class="qa-icon orange">
                    <i class="fas fa-user-shield"></i>
                </div>
                <span>Manage Roles</span>
                <small>Create & edit roles</small>
            </a>
            <a href="{{ route('admin.permissions.index') }}" class="quick-action-card">
                <div class="qa-icon green">
                    <i class="fas fa-lock"></i>
                </div>
                <span>Manage Permissions</span>
                <small>Set permissions</small>
            </a>
            <a href="{{ route('admin.payments.index') }}" class="quick-action-card">
                <div class="qa-icon pink">
                    <i class="fas fa-credit-card"></i>
                </div>
                <span>View Payments</span>
                <small>Payment records</small>
            </a>
            <a href="#" class="quick-action-card">
                <div class="qa-icon teal">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <span>Reports</span>
                <small>View analytics</small>
            </a>
        </div>
    </div>

    <!-- Analytics Section -->
    <div class="analytics-row">
        <!-- Platform Analytics Chart -->
        <div class="analytics-card large-card">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-chart-line"></i>
                    Platform Analytics
                </h3>
                <select class="time-selector">
                    <option>Last 30 Days</option>
                    <option>Last 7 Days</option>
                    <option>Last 90 Days</option>
                    <option>Last Year</option>
                </select>
            </div>
            <div class="card-body-modern">
                <canvas id="platformChart" height="80"></canvas>
                <div class="analytics-stats-row">
                    <div class="analytics-stat-item">
                        <span class="stat-label-small">Total Views</span>
                        <h4 class="stat-value-small">48,290</h4>
                        <span class="stat-change-small positive">+16.3%</span>
                    </div>
                    <div class="analytics-stat-item">
                        <span class="stat-label-small">Total Watch Time</span>
                        <h4 class="stat-value-small">2,142h</h4>
                        <span class="stat-change-small positive">+18.7%</span>
                    </div>
                    <div class="analytics-stat-item">
                        <span class="stat-label-small">Avg. Watch Time</span>
                        <h4 class="stat-value-small">42m</h4>
                        <span class="stat-change-small positive">+9.2%</span>
                    </div>
                    <div class="analytics-stat-item">
                        <span class="stat-label-small">Bounce Rate</span>
                        <h4 class="stat-value-small">24.6%</h4>
                        <span class="stat-change-small negative">+3.1%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users by Role Pie Chart -->
        <div class="analytics-card">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-users"></i>
                    Users by Role
                </h3>
            </div>
            <div class="card-body-modern">
                <canvas id="usersRoleChart" height="180"></canvas>
                <div class="role-legend">
                    @php
                        $roleStats = \App\Models\Role::withCount('users')->get();
                        $totalUsers = \App\Models\User::count();
                    @endphp
                    @foreach($roleStats as $role)
                    <div class="legend-item">
                        <span class="legend-dot" style="background: {{ ['#8b5cf6', '#3b82f6', '#f59e0b', '#10b981'][$loop->index % 4] }}"></span>
                        <span class="legend-label">{{ $role->name }}</span>
                        <span class="legend-value">{{ $role->users_count }} ({{ $totalUsers > 0 ? number_format(($role->users_count / $totalUsers) * 100, 1) : 0 }}%)</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Row -->
    <div class="bottom-row">
        <!-- Recent Activity -->
        <div class="activity-card">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-clock"></i>
                    Recent Activity
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="activity-list">
                    @php
                        $recentUsers = \App\Models\User::latest()->take(3)->get();
                    @endphp
                    @foreach($recentUsers as $user)
                    <div class="activity-item">
                        <div class="activity-icon user-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="activity-content">
                            <p class="activity-text">New user registered: <strong>{{ $user->name }}</strong></p>
                            <span class="activity-time">{{ $user->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    @endforeach
                    
                    <div class="activity-item">
                        <div class="activity-icon movie-icon">
                            <i class="fas fa-film"></i>
                        </div>
                        <div class="activity-content">
                            <p class="activity-text">New movie added: <strong>The Flash</strong></p>
                            <span class="activity-time">15 minutes ago</span>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon role-icon">
                            <i class="fas fa-user-tag"></i>
                        </div>
                        <div class="activity-content">
                            <p class="activity-text">Role updated: <strong>Editor</strong></p>
                            <span class="activity-time">1 hour ago</span>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon permission-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="activity-content">
                            <p class="activity-text">Permission updated: <strong>Movies Manage</strong></p>
                            <span class="activity-time">2 hours ago</span>
                        </div>
                    </div>
                </div>
                <a href="#" class="view-all-link">
                    View all activity <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Top Movies -->
        <div class="movies-card">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-star"></i>
                    Top Movies
                </h3>
                <div class="card-tabs">
                    <span class="tab-item">Views</span>
                    <span class="tab-item active">Revenue</span>
                </div>
            </div>
            <div class="card-body-modern">
                <div class="movies-list">
                    <div class="movie-item">
                        <img src="https://image.tmdb.org/t/p/w200/or06FN3Dka5tukK1e9sl16pB3iy.jpg" alt="Avengers: Endgame" class="movie-thumb">
                        <div class="movie-info">
                            <h4 class="movie-title">Avengers: Endgame</h4>
                        </div>
                        <div class="movie-stats">
                            <span class="movie-views">12,540</span>
                            <span class="movie-revenue">$2,540</span>
                        </div>
                    </div>
                    <div class="movie-item">
                        <img src="https://image.tmdb.org/t/p/w200/qJ2tW6WMUDux911r6m7haRef0WH.jpg" alt="The Dark Knight" class="movie-thumb">
                        <div class="movie-info">
                            <h4 class="movie-title">The Dark Knight</h4>
                        </div>
                        <div class="movie-stats">
                            <span class="movie-views">9,621</span>
                            <span class="movie-revenue">$1,870</span>
                        </div>
                    </div>
                    <div class="movie-item">
                        <img src="https://image.tmdb.org/t/p/w200/9gk7adHYeDvHkCSEqAvQNLV5Uge.jpg" alt="Inception" class="movie-thumb">
                        <div class="movie-info">
                            <h4 class="movie-title">Inception</h4>
                        </div>
                        <div class="movie-stats">
                            <span class="movie-views">7,982</span>
                            <span class="movie-revenue">$1,320</span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.movies.index') }}" class="view-all-link">
                    View all movies <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Dashboard Styles */
.dashboard-header-section {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2rem;
}

.dashboard-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.dashboard-subtitle {
    color: var(--text-secondary);
    font-size: 0.95rem;
}

.btn-date-range {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-date-range:hover {
    border-color: var(--primary-color);
    background: var(--light-bg);
}

/* Modern Stats Cards */
.stats-cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card-modern {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.25rem;
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
}

.stat-card-modern::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, rgba(255,255,255,0.1), transparent);
    border-radius: 50%;
    transform: translate(30%, -30%);
}

.stat-card-modern:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

.stat-icon-wrapper {
    width: 60px;
    height: 60px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.stat-icon-wrapper.purple { background: linear-gradient(135deg, #8b5cf6, #6d28d9); color: white; }
.stat-icon-wrapper.blue { background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; }
.stat-icon-wrapper.orange { background: linear-gradient(135deg, #f59e0b, #d97706); color: white; }
.stat-icon-wrapper.green { background: linear-gradient(135deg, #10b981, #059669); color: white; }
.stat-icon-wrapper.pink { background: linear-gradient(135deg, #ec4899, #db2777); color: white; }
.stat-icon-wrapper.teal { background: linear-gradient(135deg, #14b8a6, #0d9488); color: white; }

.stat-content-modern {
    flex: 1;
}

.stat-label-modern {
    color: var(--text-secondary);
    font-size: 0.85rem;
    display: block;
    margin-bottom: 0.5rem;
}

.stat-value-modern {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.stat-change-modern {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.8rem;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
}

.stat-change-modern.positive {
    background: rgba(16, 185, 129, 0.15);
    color: #10b981;
}

.stat-change-modern.negative {
    background: rgba(239, 68, 68, 0.15);
    color: #ef4444;
}

/* Quick Actions Section */
.quick-actions-section {
    margin-bottom: 2rem;
}

.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.section-title i {
    color: var(--primary-color);
}

.quick-actions-grid-modern {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 1rem;
}

.quick-action-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem 1rem;
    text-align: center;
    text-decoration: none;
    color: var(--text-primary);
    transition: all 0.3s;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
}

.quick-action-card:hover {
    transform: translateY(-3px);
    border-color: var(--primary-color);
    background: var(--light-bg);
}

.qa-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
}

.qa-icon.purple { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
.qa-icon.blue { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
.qa-icon.orange { background: linear-gradient(135deg, #f59e0b, #d97706); }
.qa-icon.green { background: linear-gradient(135deg, #10b981, #059669); }
.qa-icon.pink { background: linear-gradient(135deg, #ec4899, #db2777); }
.qa-icon.teal { background: linear-gradient(135deg, #14b8a6, #0d9488); }

.quick-action-card span {
    font-weight: 600;
    font-size: 0.9rem;
}

.quick-action-card small {
    color: var(--text-muted);
    font-size: 0.75rem;
}

/* Analytics Row */
.analytics-row {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.analytics-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    overflow: hidden;
}

.card-header-modern {
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-title-modern {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.card-title-modern i {
    color: var(--primary-color);
}

.time-selector {
    background: var(--light-bg);
    border: 1px solid var(--border-color);
    color: var(--text-primary);
    padding: 0.5rem 1rem;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.85rem;
}

.card-body-modern {
    padding: 1.5rem;
}

.analytics-stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-top: 1.5rem;
}

.analytics-stat-item {
    text-align: center;
}

.stat-label-small {
    color: var(--text-muted);
    font-size: 0.75rem;
    display: block;
    margin-bottom: 0.25rem;
}

.stat-value-small {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.stat-change-small {
    font-size: 0.75rem;
    font-weight: 500;
}

.stat-change-small.positive { color: #10b981; }
.stat-change-small.negative { color: #ef4444; }

/* Role Legend */
.role-legend {
    margin-top: 2rem;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--border-color);
}

.legend-item:last-child {
    border-bottom: none;
}

.legend-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.legend-label {
    flex: 1;
    color: var(--text-primary);
    font-size: 0.9rem;
}

.legend-value {
    color: var(--text-muted);
    font-size: 0.85rem;
}

/* Bottom Row */
.bottom-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.activity-card,
.movies-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    overflow: hidden;
}

.activity-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.activity-item {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: white;
}

.activity-icon.user-icon { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
.activity-icon.movie-icon { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
.activity-icon.role-icon { background: linear-gradient(135deg, #f59e0b, #d97706); }
.activity-icon.permission-icon { background: linear-gradient(135deg, #10b981, #059669); }

.activity-content {
    flex: 1;
}

.activity-text {
    color: var(--text-primary);
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.activity-time {
    color: var(--text-muted);
    font-size: 0.75rem;
}

.movies-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.movie-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem;
    background: var(--light-bg);
    border-radius: 10px;
    transition: all 0.3s;
}

.movie-item:hover {
    background: var(--hover-bg);
}

.movie-thumb {
    width: 50px;
    height: 75px;
    object-fit: cover;
    border-radius: 8px;
}

.movie-info {
    flex: 1;
}

.movie-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-primary);
}

.movie-stats {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.25rem;
}

.movie-views,
.movie-revenue {
    font-size: 0.85rem;
    color: var(--text-secondary);
}

.movie-revenue {
    color: var(--success-color);
    font-weight: 600;
}

.view-all-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--primary-color);
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 600;
    margin-top: 1rem;
    transition: gap 0.3s;
}

.view-all-link:hover {
    gap: 0.75rem;
}

.card-tabs {
    display: flex;
    gap: 0.5rem;
}

.tab-item {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.3s;
    color: var(--text-secondary);
}

.tab-item.active {
    background: var(--primary-color);
    color: white;
}

@media (max-width: 1200px) {
    .analytics-row {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .bottom-row {
        grid-template-columns: 1fr;
    }
    
    .analytics-stats-row {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Platform Analytics Chart
const platformCtx = document.getElementById('platformChart');
if (platformCtx) {
    new Chart(platformCtx, {
        type: 'line',
        data: {
            labels: ['May 15', 'May 20', 'May 25', 'May 30', 'Jun 4', 'Jun 9', 'Jun 15'],
            datasets: [{
                label: 'Users',
                data: [400, 450, 480, 520, 580, 650, 700],
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true,
                borderWidth: 2
            }, {
                label: 'Movie Views',
                data: [300, 380, 420, 480, 550, 620, 680],
                borderColor: '#8b5cf6',
                backgroundColor: 'rgba(139, 92, 246, 0.1)',
                tension: 0.4,
                fill: true,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#b3b3b3',
                        usePointStyle: true
                    }
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

// Users by Role Pie Chart
const usersRoleCtx = document.getElementById('usersRoleChart');
if (usersRoleCtx) {
    new Chart(usersRoleCtx, {
        type: 'doughnut',
        data: {
            labels: ['Admin', 'Editor', 'Moderator', 'Subscriber'],
            datasets: [{
                data: [3, 8, 15, 1219],
                backgroundColor: [
                    '#8b5cf6',
                    '#3b82f6',
                    '#f59e0b',
                    '#10b981'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.parsed + ' users';
                        }
                    }
                }
            }
        }
    });
}
</script>
@endsection
