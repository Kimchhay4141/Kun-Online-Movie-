<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{
    /**
     * Display a listing of permissions.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Permission::class);

        $query = Permission::query()->withCount('roles');

        // Filter by group/module
        if ($request->filled('module')) {
            $query->where('group', $request->module);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('slug', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $permissions = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $modules = Permission::distinct()->pluck('group')->filter()->sort();
        $totalPermissions = Permission::count();
        $assignedPermissions = Permission::has('roles')->count();
        $moduleCount = $modules->count();
        $newPermissionsToday = Permission::whereDate('created_at', today())->count();

        return view('admin.permissions.index', compact(
            'permissions',
            'modules',
            'totalPermissions',
            'assignedPermissions',
            'moduleCount',
            'newPermissionsToday'
        ));
    }

    /**
     * Show the form for creating a new permission.
     */
    public function create()
    {
        $this->authorize('create', Permission::class);

        $modules = Permission::distinct()->pluck('group')->filter()->sort();

        return view('admin.permissions.create', compact('modules'));
    }

    /**
     * Store a newly created permission.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Permission::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:permissions,slug',
            'description' => 'nullable|string|max:500',
            'group' => 'required|string|max:100',
        ]);

        Permission::create($validated);

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    /**
     * Display the specified permission.
     */
    public function show(Permission $permission)
    {
        $this->authorize('view', $permission);

        $permission->load(['roles.users']);

        return view('admin.permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified permission.
     */
    public function edit(Permission $permission)
    {
        $this->authorize('update', $permission);

        $modules = Permission::distinct()->pluck('group')->filter()->sort();

        return view('admin.permissions.edit', compact('permission', 'modules'));
    }

    /**
     * Update the specified permission.
     */
    public function update(Request $request, Permission $permission)
    {
        $this->authorize('update', $permission);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('permissions')->ignore($permission->id)],
            'description' => 'nullable|string|max:500',
            'group' => 'required|string|max:100',
        ]);

        $permission->update($validated);

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified permission.
     */
    public function destroy(Permission $permission)
    {
        $this->authorize('delete', $permission);

        // Check if permission is assigned to any roles
        if ($permission->roles()->count() > 0) {
            return redirect()
                ->route('admin.permissions.index')
                ->with('error', 'Cannot delete permission assigned to roles. Please remove from roles first.');
        }

        $permission->delete();

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }
}
