<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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

        // Get all roles for filter
        $roles = Role::orderBy('name')->get();

        return view('admin.users.index', compact(
            'users',
            'totalUsers',
            'activeUsers',
            'premiumUsers',
            'newUsersToday',
            'roles'
        ));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $this->authorize('create', User::class);

        $roles = Role::orderBy('name')->get();

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'subscription_status' => 'nullable|in:active,inactive,suspended',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'subscription_status' => $validated['subscription_status'] ?? 'inactive',
        ]);

        // Assign roles
        if (isset($validated['roles'])) {
            $user->roles()->sync($validated['roles']);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully.');
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

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        
        $this->authorize('update', $user);

        $roles = Role::orderBy('name')->get();
        $userRoles = $user->roles->pluck('id')->toArray();

        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $this->authorize('update', $user);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'subscription_status' => 'nullable|in:active,inactive,suspended',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subscription_status' => $validated['subscription_status'] ?? $user->subscription_status,
        ];

        // Update password if provided
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        // Sync roles
        if (isset($validated['roles'])) {
            $user->roles()->sync($validated['roles']);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        $this->authorize('delete', $user);

        // Prevent deletion of own account
        if (auth()->id() === $user->id) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        // Prevent deletion of super admin
        if ($user->hasRole('super-admin')) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Cannot delete super admin user.');
        }

        $user->roles()->detach();
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
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

    /**
     * Assign roles to user.
     */
    public function assignRoles(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $this->authorize('update', $user);

        $validated = $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user->roles()->sync($validated['roles']);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'Roles assigned successfully.');
    }
}
