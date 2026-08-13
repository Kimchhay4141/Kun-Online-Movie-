<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine if the user can view any users.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('users.view');
    }

    /**
     * Determine if the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Users can view their own profile
        if ($user->id === $model->id) {
            return true;
        }

        // Admins and support can view other users
        return $user->hasPermission('users.view');
    }

    /**
     * Determine if the user can create users.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('users.create');
    }

    /**
     * Determine if the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Users can update their own profile
        if ($user->id === $model->id) {
            return true;
        }

        // Admins can update other users
        return $user->hasPermission('users.edit');
    }

    /**
     * Determine if the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Users cannot delete themselves
        if ($user->id === $model->id) {
            return false;
        }

        // Cannot delete admin users (unless you're also admin)
        if ($model->isAdmin() && !$user->isAdmin()) {
            return false;
        }

        return $user->hasPermission('users.delete');
    }

    /**
     * Determine if the user can manage roles.
     */
    public function manageRoles(User $user, User $model): bool
    {
        // Cannot manage your own roles
        if ($user->id === $model->id) {
            return false;
        }

        return $user->hasPermission('users.manage-roles');
    }

    /**
     * Determine if the user can ban users.
     */
    public function ban(User $user, User $model): bool
    {
        // Cannot ban yourself
        if ($user->id === $model->id) {
            return false;
        }

        // Cannot ban admin users
        if ($model->isAdmin()) {
            return false;
        }

        return $user->hasPermission('users.ban');
    }

    /**
     * Determine if the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->hasPermission('users.delete');
    }

    /**
     * Determine if the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->isAdmin();
    }
}
