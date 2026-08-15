@extends('layouts.admin')

@section('title', 'Payment Details - Admin')

@section('content')
<div class="admin-payment-details">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-credit-card"></i> Payment Details
            </h1>
            <p class="page-subtitle">View payment transaction details</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.payments.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Payments
            </a>
        </div>
    </div>

    <!-- Payment Details Card -->
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-receipt"></i> Transaction #{{ $payment->id }}
            </h3>
        </div>
        <div class="card-body">
            <div class="payment-details-grid">
                <!-- User Information -->
                <div class="detail-section">
                    <h4><i class="fas fa-user"></i> User Information</h4>
                    <div class="detail-row">
                        <span class="label">Name:</span>
                        <span class="value">{{ $payment->user->name ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Email:</span>
                        <span class="value">{{ $payment->user->email ?? 'N/A' }}</span>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="detail-section">
                    <h4><i class="fas fa-credit-card"></i> Payment Information</h4>
                    <div class="detail-row">
                        <span class="label">Plan:</span>
                        <span class="value">
                            <span class="plan-badge plan-{{ strtolower($payment->plan ?? 'basic') }}">
                                <i class="fas fa-{{ $payment->plan == 'premium' ? 'crown' : ($payment->plan == 'standard' ? 'check-circle' : 'star') }}"></i>
                                {{ ucfirst($payment->plan ?? 'Basic') }}
                            </span>
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Amount:</span>
                        <span class="value">${{ number_format($payment->amount ?? 0, 2) }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Payment Method:</span>
                        <span class="value">
                            <span class="payment-method">
                                <i class="fas fa-{{ $payment->payment_method == 'credit_card' ? 'credit-card' : ($payment->payment_method == 'paypal' ? 'cc-paypal' : ($payment->payment_method == 'stripe' ? 'stripe' : 'wallet')) }}"></i>
                                {{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'Card')) }}
                            </span>
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Status:</span>
                        <span class="value">
                            <span class="status-badge status-{{ strtolower($payment->status ?? 'pending') }}">
                                {{ ucfirst($payment->status ?? 'Pending') }}
                            </span>
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Transaction ID:</span>
                        <span class="value">{{ $payment->transaction_id ?? 'N/A' }}</span>
                    </div>
                </div>

                <!-- Subscription Information -->
                <div class="detail-section">
                    <h4><i class="fas fa-calendar"></i> Subscription Information</h4>
                    <div class="detail-row">
                        <span class="label">Start Date:</span>
                        <span class="value">{{ $payment->subscription_start_date ? $payment->subscription_start_date->format('M d, Y') : 'N/A' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">End Date:</span>
                        <span class="value">{{ $payment->subscription_end_date ? $payment->subscription_end_date->format('M d, Y') : 'N/A' }}</span>
                    </div>
                </div>

                <!-- Timestamp Information -->
                <div class="detail-section">
                    <h4><i class="fas fa-clock"></i> Timestamp Information</h4>
                    <div class="detail-row">
                        <span class="label">Created At:</span>
                        <span class="value">{{ $payment->created_at ? $payment->created_at->format('M d, Y h:i A') : 'N/A' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Updated At:</span>
                        <span class="value">{{ $payment->updated_at ? $payment->updated_at->format('M d, Y h:i A') : 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
/* Enhanced Payment Details Page */
.admin-payment-details {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.payment-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.detail-section {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    padding: 1.75rem;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}

.detail-section:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    border-color: rgba(255, 255, 255, 0.2);
}

.detail-section h4 {
    margin: 0 0 1.25rem 0;
    font-size: 1.1rem;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-section h4 i {
    color: var(--primary-color);
    font-size: 1.25rem;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    transition: all 0.3s ease;
}

.detail-row:hover {
    background: rgba(255, 255, 255, 0.02);
    padding-left: 0.5rem;
    padding-right: 0.5rem;
    border-radius: 8px;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-row .label {
    color: var(--text-muted);
    font-size: 0.9rem;
    font-weight: 500;
}

.detail-row .value {
    color: var(--text-primary);
    font-weight: 600;
    font-size: 0.95rem;
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

/* Enhanced Header Actions */
.header-actions .btn-secondary {
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
}

.header-actions .btn-secondary:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-2px);
    border-color: rgba(255, 255, 255, 0.2);
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
</style>
@endsection