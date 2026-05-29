<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\WorkLog;

class WorkLogPolicy
{
    /**
     * Determine whether the user can view the work log.
     */
    public function view(User $user, WorkLog $workLog): bool
    {
        return $user->id === $workLog->user_id || $user->hasAnyRole(['administrator', 'kierownik']);
    }

    /**
     * Determine whether the user can create work logs.
     */
    public function create(User $user): bool
    {
        return true; // Everyone can create logs (employees for themselves, managers for others)
    }

    /**
     * Determine whether the user can update the work log.
     */
    public function update(User $user, WorkLog $workLog): bool
    {
        return $user->hasAnyRole(['administrator', 'kierownik']);
    }

    /**
     * Determine whether the user can add comments.
     */
    public function comment(User $user, WorkLog $workLog): bool
    {
        return $user->id === $workLog->user_id || $user->hasAnyRole(['administrator', 'kierownik']);
    }
}
