<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\CompanyInfo;
use App\Models\User;

class CompanyInfoPolicy
{
    /**
     * Determine whether the user can view the company info.
     */
    public function viewAny(User $user): bool
    {
        return true; // Everyone can see company info
    }

    /**
     * Determine whether the user can update the company info.
     */
    public function update(User $user, CompanyInfo $companyInfo): bool
    {
        return $user->hasRole('administrator');
    }

    /**
     * Determine whether the user can create the company info.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('administrator');
    }
}
