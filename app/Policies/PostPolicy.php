<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Post $post): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['administrator', 'kierownik']);
    }

    public function update(User $user, Post $post): bool
    {
        return $user->hasAnyRole(['administrator', 'kierownik']);
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->hasAnyRole(['administrator', 'kierownik']);
    }

    public function restore(User $user, Post $post): bool
    {
        return $user->hasRole('administrator');
    }

    public function forceDelete(User $user, Post $post): bool
    {
        return $user->hasRole('administrator');
    }
}
