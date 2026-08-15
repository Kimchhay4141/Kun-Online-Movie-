<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display the payments management page
     */
    public function index(Request $request)
    {
        try {
            // Build query with filters
            $query = Payment::with('user');

            // Search by user name or email
            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Filter by status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Filter by plan
            if ($request->filled('plan')) {
                $query->where('plan', $request->plan);
            }

            // Filter by date range
            if ($request->filled('date_range')) {
                switch ($request->date_range) {
                    case 'today':
                        $query->whereDate('created_at', today());
                        break;
                    case 'week':
                        $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                        break;
                    case 'month':
                        $query->whereMonth('created_at', now()->month)
                              ->whereYear('created_at', now()->year);
                        break;
                    case 'year':
                        $query->whereYear('created_at', now()->year);
                        break;
                }
            }

            // Get paginated payments
            $payments = $query->orderBy('created_at', 'desc')->paginate(20);

            // Calculate statistics
            $totalRevenue = $this->getTotalRevenue();
            $totalPayments = Payment::count();
            $revenueToday = $this->getRevenueToday();
            $pendingPayments = Payment::where('status', 'pending')->count();

            return view('admin.payments.index', compact(
                'payments',
                'totalRevenue',
                'totalPayments',
                'revenueToday',
                'pendingPayments'
            ));
        } catch (\Exception $e) {
            // Log the error and return with empty data
            \Log::error('PaymentController@index error: ' . $e->getMessage());
            
            return view('admin.payments.index', [
                'payments' => collect(),
                'totalRevenue' => 0,
                'totalPayments' => 0,
                'revenueToday' => 0,
                'pendingPayments' => 0
            ]);
        }
    }

    /**
     * Show payment details
     */
    public function show($id)
    {
        try {
            $payment = Payment::with('user')->findOrFail($id);
            return view('admin.payments.show', compact('payment'));
        } catch (\Exception $e) {
            \Log::error('PaymentController@show error: ' . $e->getMessage());
            return redirect()->route('admin.payments.index')
                ->with('error', 'Payment not found.');
        }
    }

    /**
     * Get total revenue from completed payments
     */
    private function getTotalRevenue()
    {
        try {
            return Payment::where('status', 'completed')->sum('amount') ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get revenue for today
     */
    private function getRevenueToday()
    {
        try {
            return Payment::where('status', 'completed')
                         ->whereDate('created_at', today())
                         ->sum('amount') ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
}