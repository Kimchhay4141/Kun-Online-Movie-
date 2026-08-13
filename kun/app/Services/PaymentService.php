<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Str;

class PaymentService
{
    /**
     * Process a payment
     */
    public function processPayment(User $user, string $plan, string $paymentMethod, array $data)
    {
        $amount = $this->getPlanAmount($plan);

        // Here you would integrate with actual payment gateway
        // For now, we'll simulate a successful payment
        $transactionId = $this->generateTransactionId();

        // Create payment record
        $payment = Payment::create([
            'user_id' => $user->id,
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'currency' => 'USD',
            'payment_method' => $paymentMethod,
            'status' => 'completed',
            'plan_type' => $plan,
            'billing_period' => 'monthly',
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'metadata' => json_encode($data),
        ]);

        // Update user subscription
        $this->updateUserSubscription($user, $plan, $payment);

        // Send payment confirmation email
        // Mail::to($user->email)->send(new PaymentConfirmation($payment));

        return $payment;
    }

    /**
     * Update user subscription status
     */
    protected function updateUserSubscription(User $user, string $plan, Payment $payment)
    {
        $user->update([
            'subscription_plan' => $plan,
            'subscription_status' => 'active',
            'subscription_start' => now(),
            'subscription_end' => now()->addMonth(),
            'last_payment_id' => $payment->id,
        ]);
    }

    /**
     * Cancel subscription
     */
    public function cancelSubscription(User $user)
    {
        // Here you would cancel with the payment gateway
        
        $user->update([
            'subscription_status' => 'cancelled',
            'subscription_end' => now(),
        ]);

        // Log activity
        activity()
            ->causedBy($user)
            ->log('Subscription cancelled');

        return true;
    }

    /**
     * Renew subscription
     */
    public function renewSubscription(User $user)
    {
        if (!$user->subscription_plan) {
            throw new \Exception('No active subscription plan found');
        }

        return $this->processPayment(
            $user,
            $user->subscription_plan,
            $user->last_payment->payment_method ?? 'card',
            []
        );
    }

    /**
     * Check if subscription is active
     */
    public function isSubscriptionActive(User $user)
    {
        return $user->subscription_status === 'active' 
            && $user->subscription_end 
            && $user->subscription_end->isFuture();
    }

    /**
     * Get plan amount
     */
    protected function getPlanAmount(string $plan)
    {
        $amounts = [
            'basic' => 9.99,
            'standard' => 14.99,
            'premium' => 19.99,
        ];

        return $amounts[$plan] ?? 14.99;
    }

    /**
     * Generate transaction ID
     */
    protected function generateTransactionId()
    {
        return 'TXN_' . strtoupper(Str::random(12)) . '_' . time();
    }

    /**
     * Get payment history for user
     */
    public function getPaymentHistory(User $user, $limit = null)
    {
        $query = $user->payments()->orderBy('created_at', 'desc');
        
        return $limit ? $query->take($limit)->get() : $query->paginate(20);
    }

    /**
     * Process refund
     */
    public function processRefund(Payment $payment, $reason = null)
    {
        // Here you would process refund with payment gateway
        
        $payment->update([
            'status' => 'refunded',
            'refund_reason' => $reason,
            'refunded_at' => now(),
        ]);

        // Update user subscription if needed
        if ($payment->user->last_payment_id === $payment->id) {
            $payment->user->update([
                'subscription_status' => 'cancelled',
            ]);
        }

        return $payment;
    }

    /**
     * Get subscription details
     */
    public function getSubscriptionDetails(User $user)
    {
        return [
            'plan' => $user->subscription_plan,
            'status' => $user->subscription_status,
            'start_date' => $user->subscription_start,
            'end_date' => $user->subscription_end,
            'is_active' => $this->isSubscriptionActive($user),
            'days_remaining' => $user->subscription_end 
                ? now()->diffInDays($user->subscription_end) 
                : 0,
            'auto_renew' => $user->auto_renew ?? false,
        ];
    }

    /**
     * Get payment statistics
     */
    public function getPaymentStatistics()
    {
        return [
            'total_payments' => Payment::count(),
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'monthly_revenue' => Payment::where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->sum('amount'),
            'active_subscriptions' => User::where('subscription_status', 'active')->count(),
            'cancelled_subscriptions' => User::where('subscription_status', 'cancelled')->count(),
            'plan_distribution' => User::whereNotNull('subscription_plan')
                ->selectRaw('subscription_plan, COUNT(*) as count')
                ->groupBy('subscription_plan')
                ->get(),
        ];
    }
}
