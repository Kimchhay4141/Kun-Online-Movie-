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
            <button class="btn-secondary" onclick="exportPayments()">
                <i class="fas fa-download"></i> Export
            </button>
            <button class="btn-secondary" onclick="toggleFilters()">
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
                                    <i class="fas fa-{{ $payment->plan == 'premium' ? 'crown' : 'star' }}"></i>
                                    {{ ucfirst($payment->plan ?? 'Basic') }}
                                </span>
                            </td>
                            <td>
                                <strong class="amount-text">${{ number_format($payment->amount ?? 0, 2) }}</strong>
                            </td>
                            <td>
                                <span class="payment-method">
                                    <i class="fas fa-{{ $payment->payment_method == 'credit_card' ? 'credit-card' : ($payment->payment_method == 'paypal' ? 'cc-paypal' : 'wallet') }}"></i>
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
                                    <strong>{{ $payment->created_at->format('M d, Y') }}</strong>
                                    <small>{{ $payment->created_at->format('h:i A') }}</small>
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
                {{ $payments->links() }}
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
.user-info-mini strong {
    display: block;
    margin-bottom: 0.15rem;
}

.user-info-mini small {
    color: var(--text-muted);
    font-size: 0.75rem;
}

.plan-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.35rem 0.85rem;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 600;
}

.plan-basic {
    background: rgba(33, 150, 243, 0.15);
    color: var(--info-color);
}

.plan-premium {
    background: linear-gradient(135deg, rgba(255, 215, 0, 0.2), rgba(255, 193, 7, 0.2));
    color: #ffa500;
    border: 1px solid rgba(255, 193, 7, 0.3);
}

.amount-text {
    color: var(--success-color);
    font-size: 1.1rem;
}

.payment-method {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.date-info strong {
    display: block;
    margin-bottom: 0.15rem;
}

.date-info small {
    color: var(--text-muted);
    font-size: 0.75rem;
}

.status-refunded {
    background: rgba(156, 39, 176, 0.15);
    color: var(--purple-color);
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
    alert('View payment details - ID: ' + id);
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
                borderColor: var(--success-color),
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
