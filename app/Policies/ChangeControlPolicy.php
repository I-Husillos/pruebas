<?php

namespace App\Policies;

use App\Models\ChangeControl;
use App\Models\User;

class ChangeControlPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['super-admin', 'editor']);
    }

    public function view(User $user, ChangeControl $changeControl): bool
    {
        return $user->hasRole(['super-admin', 'editor']) || $user->id === $changeControl->requester_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['super-admin', 'editor']);
    }

    public function update(User $user, ChangeControl $changeControl): bool
    {
        return $user->hasRole(['super-admin', 'editor']) || $user->id === $changeControl->requester_id;
    }

    public function delete(User $user, ChangeControl $changeControl): bool
    {
        return $user->hasRole(['super-admin']);
    }

    public function approve(User $user, ChangeControl $changeControl): bool
    {
        return $user->hasRole(['super-admin']);
    }

    public function restore(User $user, ChangeControl $changeControl): bool
    {
        return $user->hasRole(['super-admin']);
    }

    public function forceDelete(User $user, ChangeControl $changeControl): bool
    {
        return $user->hasRole(['super-admin']);
    }
}
