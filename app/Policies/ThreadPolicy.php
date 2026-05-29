<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Thread;
use App\Models\User;

class ThreadPolicy
{
    /**
     * Determine whether the user can view the thread.
     */
    public function view(User $user, Thread $thread): bool
    {
        return $user->id === $thread->user_id || $user->hasAnyRole(['administrator', 'kierownik']);
    }

    /**
     * Determine whether the user can reply to the thread.
     */
    public function reply(User $user, Thread $thread): bool
    {
        return $thread->status === 'open' && ($user->id === $thread->user_id || $user->hasAnyRole(['administrator', 'kierownik']));
    }

    /**
     * Determine whether the user can delete the thread.
     */
    public function delete(User $user, Thread $thread): bool
    {
        return $user->hasRole('administrator');
    }

    /**
     * Determine whether the user can open/close the thread.
     */
    public function update(User $user, Thread $thread): bool
    {
        return $user->hasAnyRole(['administrator', 'kierownik']);
    }
}
