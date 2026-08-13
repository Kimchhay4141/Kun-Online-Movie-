<?php

/**
 * RBAC Helper Functions
 * 
 * Global helper functions for Role-Based Access Control
 */

if (!function_exists('current_user')) {
    /**
     * Get the currently authenticated user.
     *
     * @return \App\Models\User|null
     */
    function current_user()
    {
        return auth()->user();
    }
}

if (!function_exists('user_has_role')) {
    /**
     * Check if the current user has a specific role.
     *
     * @param string|array $roles
     * @return bool
     */
    function user_has_role($roles): bool
    {
        $user = current_user();
        
        if (!$user) {
            return false;
        }

        return $user->hasRole($roles);
    }
}

if (!function_exists('user_has_permission')) {
    /**
     * Check if the current user has a specific permission.
     *
     * @param string $permission
     * @return bool
     */
    function user_has_permission(string $permission): bool
    {
        $user = current_user();
        
        if (!$user) {
            return false;
        }

        return $user->hasPermission($permission);
    }
}

if (!function_exists('user_is_admin')) {
    /**
     * Check if the current user is an admin.
     *
     * @return bool
     */
    function user_is_admin(): bool
    {
        $user = current_user();
        
        if (!$user) {
            return false;
        }

        return $user->isAdmin();
    }
}

if (!function_exists('user_can')) {
    /**
     * Check if the current user can perform an action on a model.
     *
     * @param string $ability
     * @param mixed $model
     * @return bool
     */
    function user_can(string $ability, $model = null): bool
    {
        $user = current_user();
        
        if (!$user) {
            return false;
        }

        return $user->can($ability, $model);
    }
}

if (!function_exists('user_cannot')) {
    /**
     * Check if the current user cannot perform an action on a model.
     *
     * @param string $ability
     * @param mixed $model
     * @return bool
     */
    function user_cannot(string $ability, $model = null): bool
    {
        return !user_can($ability, $model);
    }
}

if (!function_exists('abort_unless_can')) {
    /**
     * Abort with 403 unless the user can perform the action.
     *
     * @param string $ability
     * @param mixed $model
     * @param string|null $message
     * @return void
     */
    function abort_unless_can(string $ability, $model = null, ?string $message = null): void
    {
        if (user_cannot($ability, $model)) {
            abort(403, $message ?? 'Access denied.');
        }
    }
}

if (!function_exists('abort_unless_has_role')) {
    /**
     * Abort with 403 unless the user has the specified role.
     *
     * @param string|array $roles
     * @param string|null $message
     * @return void
     */
    function abort_unless_has_role($roles, ?string $message = null): void
    {
        if (!user_has_role($roles)) {
            abort(403, $message ?? 'Access denied.');
        }
    }
}

if (!function_exists('abort_unless_has_permission')) {
    /**
     * Abort with 403 unless the user has the specified permission.
     *
     * @param string $permission
     * @param string|null $message
     * @return void
     */
    function abort_unless_has_permission(string $permission, ?string $message = null): void
    {
        if (!user_has_permission($permission)) {
            abort(403, $message ?? 'Access denied.');
        }
    }
}
