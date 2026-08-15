@extends('layouts.admin')

@section('title', 'Payments Management - Admin')

@section('content')
<div class="admin-payments">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-credit-card"></i> Payments Management
            </h1>
            <p class="page-subtitle">Track all payment transactions and revenue</p>
        </div>
        <div class="header-actions">
            <button class="btn-secondary enhanced-btn" onclick="exportPayments()">
                <i class="fas fa-download"></i> Export
            </button>
            <button class="btn-secondary enhanced-btn" onclick="toggleFilters()">
                <i class="fas fa-filter"></i> Filters
            </button>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section" id="filtersSection" style="display: none;">
        <form method="GET" action="{{ route('admin.payments.index') }}" class="filters-form">
            <div class="filter-group">
                <label>Search User</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by user name or email...">
            </div>
            <div class="filter-group">
                <label>Status</label>
                <select name="status">
                    <option value="">All Status</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Plan</label>
                <select name="plan">
                    <option value="">All Plans</option>
                    <option value="basic" {{ request('plan') == 'basic' ? 'selected' : '' }}>Basic</option>
                    <option value="standard" {{ request('plan') == 'standard' ? 'selected' : '' }}>Standard</option>
                    <option value="premium" {{ request('plan') == 'premium' ? 'selected' : '' }}>Premium</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Date Range</label>
                <select name="date_range">
                    <option value="">All Time</option>
                    <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                    <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>This Week</option>
                    <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>This Month</option>
                    <option value="year" {{ request('date_range') == 'year' ? 'selected' : '' }}>This Year</option>
                </select>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-apply">Apply</button>
                <a href="{{ route('admin.payments.index') }}" class="btn-reset">Reset</a>
            </div>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="stats-row">
        <div class="stat-card-small">
            <div class="stat-icon bg-success">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-info">
                <h4>${{ number_format($totalRevenue ?? 0, 2) }}</h4>
                <p>Total Revenue</p>
            </div>
        </div>
        <div class="stat-card-small">
            <div class="stat-icon bg-primary">
                <i class="fas fa-receipt"></i>
            </div>
            <div class="stat-info">
                <h4>{{ $totalPayments ?? 0 }}</h4>
                <p>Total Payments</p>
            </div>
        </div>
        <div class="stat-card-small">
            <div class="stat-icon bg-info">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-info">
                <h4>${{ number_format($revenueToday ?? 0, 2) }}</h4>
                <p>Revenue Today</p>
            </div>
        </div>
        <div class="stat-card-small">
            <div class="stat-icon bg-warning">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div class="stat-info">
                <h4>{{ $pendingPayments ?? 0 }}</h4>
                <p>Pending</p>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-list"></i> All Payments ({{ $payments->total() ?? 0 }})
            </h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Plan</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th width="100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments ?? [] as $payment)
                        <tr>
                            <td>
                                <strong>#{{ $payment->id }}</strong>
                            </td>
                            <td>
                                <div class="user-info-mini">
                                    <strong>{{ $payment->user->name ?? 'N/A' }}</strong>
                                    <small>{{ $payment->user->email ?? 'N/A' }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="plan-badge plan-{{ strtolower($payment->plan ?? 'basic') }}">
                                    <i class="fas fa-{{ $payment->plan == 'premium' ? 'crown' : ($payment->plan == 'standard' ? 'check-circle' : 'star') }}"></i>
                                    {{ ucfirst($payment->plan ?? 'Basic') }}
                                </span>
                            </td>
                            <td>
                                <strong class="amount-text">${{ number_format($payment->amount ?? 0, 2) }}</strong>
                            </td>
                            <td>
                                <span class="payment-method">
                                    <i class="fas fa-{{ $payment->payment_method == 'credit_card' ? 'credit-card' : ($payment->payment_method == 'paypal' ? 'cc-paypal' : ($payment->payment_method == 'stripe' ? 'stripe' : 'wallet')) }}"></i>
                                    {{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'Card')) }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-{{ strtolower($payment->status ?? 'pending') }}">
                                    {{ ucfirst($payment->status ?? 'Pending') }}
                                </span>
                            </td>
                            <td>
                                <div class="date-info">
                                    <strong>{{ $payment->created_at ? $payment->created_at->format('M d, Y') : 'N/A' }}</strong>
                                    <small>{{ $payment->created_at ? $payment->created_at->format('h:i A') : 'N/A' }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-icon" onclick="viewPayment({{ $payment->id }})" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($payment->status == 'completed')
                                    <button class="btn-icon" onclick="printReceipt({{ $payment->id }})" title="Print Receipt">
                                        <i class="fas fa-print"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                <div class="empty-state-small">
                                    <i class="fas fa-credit-card"></i>
                                    <p>No payments found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if(isset($payments) && $payments->hasPages())
            <div class="pagination-wrapper">
                {{ $payments->links('pagination.admin') }}
            </div>
            @endif
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="content-card" style="margin-top: 2rem;">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-chart-bar"></i> Revenue Overview
            </h3>
            <select class="period-select" onchange="updateChart(this.value)">
                <option value="week">Last 7 Days</option>
                <option value="month" selected>Last 30 Days</option>
                <option value="year">Last 12 Months</option>
            </select>
        </div>
        <div class="card-body">
            <canvas id="revenueChart" height="80"></canvas>
        </div>
    </div>
</div>

<style>
/* Payment Page Enhanced Styles */
.admin-payments {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Enhanced Stats Cards */
.stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card-small {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.stat-card-small:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    border-color: rgba(255, 255, 255, 0.2);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    transition: transform 0.3s ease;
}

.stat-card-small:hover .stat-icon {
    transform: scale(1.1);
}

.bg-success {
    background: linear-gradient(135deg, #10b981, #059669);
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}

.bg-primary {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
}

.bg-info {
    background: linear-gradient(135deg, #06b6d4, #0891b2);
    box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
}

.bg-warning {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
}

.stat-info h4 {
    font-size: 1.75rem;
    font-weight: 700;
    margin: 0 0 0.25rem 0;
    background: linear-gradient(135deg, #fff, #e0e0e0);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.stat-info p {
    margin: 0;
    color: var(--text-muted);
    font-size: 0.9rem;
    font-weight: 500;
}

/* Enhanced Table */
.data-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.data-table thead {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
}

.data-table th {
    padding: 1rem 1.25rem;
    text-align: left;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
    border-bottom: 2px solid rgba(255, 255, 255, 0.1);
}

.data-table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.data-table tbody tr:hover {
    background: rgba(255, 255, 255, 0.08);
    transform: scale(1.01);
}

.data-table td {
    padding: 1.25rem;
    vertical-align: middle;
}

/* Enhanced User Info */
.user-info-mini {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.user-info-mini strong {
    font-weight: 600;
    color: var(--text-primary);
    display: block;
    margin-bottom: 0.25rem;
}

.user-info-mini small {
    color: var(--text-muted);
    font-size: 0.8rem;
}

/* Enhanced Plan Badges */
.plan-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: capitalize;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.plan-badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.plan-basic {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(37, 99, 235, 0.15));
    color: #60a5fa;
    border: 1px solid rgba(59, 130, 246, 0.3);
}

.plan-standard {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.15));
    color: #34d399;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.plan-premium {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(217, 119, 6, 0.15));
    color: #fbbf24;
    border: 1px solid rgba(245, 158, 11, 0.3);
    animation: premiumGlow 2s ease-in-out infinite;
}

@keyframes premiumGlow {
    0%, 100% { box-shadow: 0 2px 8px rgba(245, 158, 11, 0.1); }
    50% { box-shadow: 0 4px 16px rgba(245, 158, 11, 0.3); }
}

/* Enhanced Amount Text */
.amount-text {
    color: #10b981;
    font-size: 1.15rem;
    font-weight: 700;
    text-shadow: 0 0 20px rgba(16, 185, 129, 0.3);
}

/* Enhanced Payment Method */
.payment-method {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
    font-weight: 500;
    padding: 0.35rem 0.75rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

/* Enhanced Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.4rem 0.9rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: capitalize;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.status-completed {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.15));
    color: #34d399;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.status-pending {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(217, 119, 6, 0.15));
    color: #fbbf24;
    border: 1px solid rgba(245, 158, 11, 0.3);
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.status-failed {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 38, 0.15));
    color: #f87171;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.status-refunded {
    background: linear-gradient(135deg, rgba(156, 39, 176, 0.2), rgba(128, 28, 128, 0.15));
    color: #c084fc;
    border: 1px solid rgba(156, 39, 176, 0.3);
}

/* Enhanced Date Info */
.date-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.date-info strong {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.9rem;
}

.date-info small {
    color: var(--text-muted);
    font-size: 0.75rem;
}

/* Enhanced Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: none;
    background: rgba(255, 255, 255, 0.1);
    color: var(--text-secondary);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.btn-icon:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(229, 62, 62, 0.3);
}

/* Enhanced Empty State */
.empty-state-small {
    text-align: center;
    padding: 3rem 1rem;
    color: var(--text-muted);
}

.empty-state-small i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-state-small p {
    font-size: 1rem;
    margin: 0;
}

/* Enhanced Filters Section */
.filters-section {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    backdrop-filter: blur(10px);
}

.filters-form {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-group label {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filter-group input,
.filter-group select {
    padding: 0.75rem 1rem;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-primary);
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.filter-group input:focus,
.filter-group select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.1);
}

.filter-actions {
    display: flex;
    gap: 0.75rem;
    align-items: flex-end;
}

.btn-apply,
.btn-reset {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
}

.btn-apply {
    background: var(--primary-color);
    color: white;
}

.btn-apply:hover {
    background: #dc2626;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
}

.btn-reset {
    background: rgba(255, 255, 255, 0.1);
    color: var(--text-primary);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.btn-reset:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}

/* Enhanced Revenue Chart Section */
.period-select {
    padding: 0.5rem 1rem;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-primary);
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.period-select:hover {
    border-color: var(--primary-color);
}

.period-select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.1);
}

/* Enhanced Header Buttons */
.enhanced-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-primary);
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.enhanced-btn:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-2px);
    border-color: rgba(255, 255, 255, 0.2);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.enhanced-btn:active {
    transform: translateY(0);
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function toggleFilters() {
    const filters = document.getElementById('filtersSection');
    filters.style.display = filters.style.display === 'none' ? 'block' : 'none';
}

function viewPayment(id) {
    window.location.href = `/admin/payments/${id}`;
}

function printReceipt(id) {
    window.open(`/admin/payments/${id}/receipt`, '_blank');
}

function exportPayments() {
    window.location.href = '{{ route('admin.export') }}?type=payments';
}

// Revenue Chart
const ctx = document.getElementById('revenueChart');
if (ctx) {
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Day 7'],
            datasets: [{
                label: 'Revenue ($)',
                data: [120, 190, 150, 210, 180, 240, 280],
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
                        color: '#b3b3b3',
                        callback: function(value) {
                            return '$' + value;
                        }
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

function updateChart(period) {
    // Update chart based on selected period
    console.log('Update chart for period:', period);
}
</script>
@endsection
