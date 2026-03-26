<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Media;

class MediaPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Media $media): bool
    {
        return $user->isSuperAdmin() || $user->client_id === $media->client_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Media $media): bool
    {
        return $user->isSuperAdmin() || $user->client_id === $media->client_id;
    }

    public function delete(User $user, Media $media): bool
    {
        return $user->isSuperAdmin() || $user->client_id === $media->client_id;
    }
}
