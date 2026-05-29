<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    /**
     * Determine whether the user can view/download the document.
     */
    public function view(User $user, Document $document): bool
    {
        return $user->id === $document->user_id || $user->hasAnyRole(['administrator', 'kierownik']);
    }

    /**
     * Determine whether the user can delete the document.
     */
    public function delete(User $user, Document $document): bool
    {
        return $user->hasAnyRole(['administrator', 'kierownik']);
    }

    /**
     * Determine whether the user can create documents.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['administrator', 'kierownik']);
    }
}
