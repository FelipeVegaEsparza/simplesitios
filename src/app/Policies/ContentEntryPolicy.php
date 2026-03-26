<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ContentEntry;

class ContentEntryPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ContentEntry $contentEntry): bool
    {
        return $user->isSuperAdmin() || $user->client_id === $contentEntry->client_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, ContentEntry $contentEntry): bool
    {
        return $user->isSuperAdmin() || $user->client_id === $contentEntry->client_id;
    }

    public function delete(User $user, ContentEntry $contentEntry): bool
    {
        return $user->isSuperAdmin() || $user->client_id === $contentEntry->client_id;
    }
}
