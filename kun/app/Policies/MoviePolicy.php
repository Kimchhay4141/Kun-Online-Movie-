<?php

namespace App\Policies;

use App\Models\Movie;
use App\Models\User;

class MoviePolicy
{
    /**
     * Determine if the user can view any movies.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('movies.view') || $user->hasPermission('movies.view-all');
    }

    /**
     * Determine if the user can view the movie.
     */
    public function view(User $user, Movie $movie): bool
    {
        // Users can view published movies
        if ($movie->status === 'published' && $user->hasPermission('movies.view')) {
            return true;
        }

        // Admins and content managers can view all movies
        return $user->hasPermission('movies.view-all');
    }

    /**
     * Determine if the user can create movies.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('movies.create');
    }

    /**
     * Determine if the user can update the movie.
     */
    public function update(User $user, Movie $movie): bool
    {
        return $user->hasPermission('movies.edit');
    }

    /**
     * Determine if the user can delete the movie.
     */
    public function delete(User $user, Movie $movie): bool
    {
        return $user->hasPermission('movies.delete');
    }

    /**
     * Determine if the user can publish the movie.
     */
    public function publish(User $user, Movie $movie): bool
    {
        return $user->hasPermission('movies.publish');
    }

    /**
     * Determine if the user can manage movie videos.
     */
    public function manageVideos(User $user, Movie $movie): bool
    {
        return $user->hasPermission('movies.manage-videos');
    }

    /**
     * Determine if the user can restore the movie.
     */
    public function restore(User $user, Movie $movie): bool
    {
        return $user->hasPermission('movies.delete');
    }

    /**
     * Determine if the user can permanently delete the movie.
     */
    public function forceDelete(User $user, Movie $movie): bool
    {
        return $user->isAdmin();
    }
}
