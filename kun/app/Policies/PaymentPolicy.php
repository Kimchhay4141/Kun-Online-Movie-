<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    /**
     * Determine if the user can view any payments.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('payments.view');
    }

    /**
     * Determine if the user can view the payment.
     */
    public function view(User $user, Payment $payment): bool
    {
        // Users can view their own payments
        if ($user->id === $payment->user_id) {
            return true;
        }

        // Admins and support can view all payments
        return $user->hasPermission('payments.view');
    }

    /**
     * Determine if the user can process refunds.
     */
    public function refund(User $user, Payment $payment): bool
    {
        return $user->hasPermission('payments.refund');
    }

    /**
     * Determine if the user can manage subscriptions.
     */
    public function manageSubscriptions(User $user): bool
    {
        return $user->hasPermission('payments.manage-subscriptions');
    }

    /**
     * Determine if the user can view revenue reports.
     */
    public function viewReports(User $user): bool
    {
        return $user->hasPermission('payments.view-reports');
    }
}
