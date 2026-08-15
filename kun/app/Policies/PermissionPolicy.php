<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;

class PermissionPolicy
{
    /**
     * Determine if the user can view any permissions.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('permission.read') || $user->isAdmin();
    }

    /**
     * Determine if the user can view the permission.
     */
    public function view(User $user, Permission $permission): bool
    {
        return $user->hasPermission('permission.read') || $user->isAdmin();
    }

    /**
     * Determine if the user can create permissions.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('permission.create') || $user->isAdmin();
    }

    /**
     * Determine if the user can update the permission.
     */
    public function update(User $user, Permission $permission): bool
    {
        return $user->hasPermission('permission.update') || $user->isAdmin();
    }

    /**
     * Determine if the user can delete the permission.
     */
    public function delete(User $user, Permission $permission): bool
    {
        return $user->hasPermission('permission.delete') || $user->isAdmin();
    }
}
