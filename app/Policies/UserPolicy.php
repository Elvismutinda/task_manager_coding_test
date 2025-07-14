<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Only admins can view the list of users.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Only admins can view individual user details.
     */
    public function view(User $user, User $model): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Only admins can create users.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Only admins can update users.
     */
    public function update(User $user, User $model): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Only admins can delete users.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}
