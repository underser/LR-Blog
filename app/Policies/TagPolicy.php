<?php

namespace App\Policies;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TagPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(?User $user): bool
    {
        return (bool)$user?->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(?User $user, Tag $tag): bool
    {
        return (bool)$user?->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?User $user, Tag $tag): bool
    {
        return (bool)$user?->isAdmin();
    }
}
