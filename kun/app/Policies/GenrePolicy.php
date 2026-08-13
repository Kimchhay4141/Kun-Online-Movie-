<?php

namespace App\Policies;

use App\Models\Genre;
use App\Models\User;

class GenrePolicy
{
    /**
     * Determine if the user can view any genres.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('genres.view');
    }

    /**
     * Determine if the user can view the genre.
     */
    public function view(User $user, Genre $genre): bool
    {
        return $user->hasPermission('genres.view');
    }

    /**
     * Determine if the user can create genres.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('genres.create');
    }

    /**
     * Determine if the user can update the genre.
     */
    public function update(User $user, Genre $genre): bool
    {
        return $user->hasPermission('genres.edit');
    }

    /**
     * Determine if the user can delete the genre.
     */
    public function delete(User $user, Genre $genre): bool
    {
        return $user->hasPermission('genres.delete');
    }

    /**
     * Determine if the user can restore the genre.
     */
    public function restore(User $user, Genre $genre): bool
    {
        return $user->hasPermission('genres.delete');
    }

    /**
     * Determine if the user can permanently delete the genre.
     */
    public function forceDelete(User $user, Genre $genre): bool
    {
        return $user->isAdmin();
    }
}
