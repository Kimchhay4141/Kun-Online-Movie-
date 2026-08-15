<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles');

        // Search filter
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Subscription filter
        if ($request->filled('subscription')) {
            $query->where('subscription_plan', $request->subscription);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('subscription_status', $request->status);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        // Stats
        $totalUsers = User::count();
        $activeUsers = User::where('subscription_status', 'active')->count();
        $premiumUsers = User::where('subscription_plan', 'premium')->count();
        $newUsersToday = User::whereDate('created_at', today())->count();

        return view('admin.users.index', compact(
            'users',
            'totalUsers',
            'activeUsers',
            'premiumUsers',
            'newUsersToday'
        ));
    }

    public function show($id)
    {
        $user = User::with(['roles', 'favorites.movie', 'payments', 'movieViews.movie'])
            ->findOrFail($id);

        $stats = [
            'total_favorites' => $user->favorites()->count(),
            'total_views' => $user->movieViews()->count(),
            'total_payments' => $user->payments()->count(),
            'total_spent' => $user->payments()->where('status', 'completed')->sum('amount'),
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    public function suspend($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->hasRole('admin')) {
            return response()->json(['success' => false, 'message' => 'Cannot suspend admin users'], 403);
        }

        $user->update(['subscription_status' => 'suspended']);

        return response()->json(['success' => true, 'message' => 'User suspended successfully']);
    }
}
