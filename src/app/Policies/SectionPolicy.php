<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Section;

class SectionPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Section $section): bool
    {
        return $user->isSuperAdmin() || $user->client_id === $section->client_id;
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function update(User $user, Section $section): bool
    {
        return $user->isSuperAdmin();
    }

    public function delete(User $user, Section $section): bool
    {
        return $user->isSuperAdmin();
    }

    public function manageFields(User $user, Section $section): bool
    {
        return $user->isSuperAdmin();
    }

    public function manageContent(User $user, Section $section): bool
    {
        return $user->isSuperAdmin() || $user->client_id === $section->client_id;
    }
}
