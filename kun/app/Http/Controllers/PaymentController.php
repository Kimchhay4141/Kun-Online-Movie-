<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->middleware('auth');
        $this->paymentService = $paymentService;
    }

    /**
     * Display subscription plans
     */
    public function plans()
    {
        $plans = [
            [
                'name' => 'Basic',
                'price' => 9.99,
                'features' => [
                    'HD Quality',
                    '1 Device',
                    'Limited Content',
                ],
            ],
            [
                'name' => 'Standard',
                'price' => 14.99,
                'features' => [
                    'Full HD Quality',
                    '2 Devices',
                    'All Content',
                    'Download',
                ],
                'popular' => true,
            ],
            [
                'name' => 'Premium',
                'price' => 19.99,
                'features' => [
                    '4K Ultra HD',
                    '4 Devices',
                    'All Content',
                    'Download',
                    'Early Access',
                ],
            ],
        ];

        return view('subscription.plans', compact('plans'));
    }

    /**
     * Show checkout page
     */
    public function checkout(Request $request)
    {
        $plan = $request->get('plan', 'standard');
        $amount = $this->getPlanAmount($plan);

        return view('subscription.checkout', compact('plan', 'amount'));
    }

    /**
     * Process payment
     */
    public function process(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:basic,standard,premium',
            'payment_method' => 'required|in:card,paypal,stripe',
        ]);

        try {
            $payment = $this->paymentService->processPayment(
                auth()->user(),
                $request->plan,
                $request->payment_method,
                $request->all()
            );

            return redirect()->route('subscription.success')
                ->with('success', 'Payment successful! Your subscription is now active.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    /**
     * Show payment success page
     */
    public function success()
    {
        return view('subscription.success');
    }

    /**
     * Show payment history
     */
    public function history()
    {
        $payments = auth()->user()
            ->payments()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('user.payments', compact('payments'));
    }

    /**
     * Cancel subscription
     */
    public function cancel()
    {
        try {
            $this->paymentService->cancelSubscription(auth()->user());

            return redirect()->back()
                ->with('success', 'Subscription cancelled successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to cancel subscription: ' . $e->getMessage());
        }
    }

    /**
     * Get plan amount
     */
    protected function getPlanAmount($plan)
    {
        $amounts = [
            'basic' => 9.99,
            'standard' => 14.99,
            'premium' => 19.99,
        ];

        return $amounts[$plan] ?? 14.99;
    }
}
