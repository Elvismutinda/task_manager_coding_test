<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Any authenticated user can see the task list, but filtering is done in the controller.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Admins can view any task. Users can view only tasks assigned to them.
     */
    public function view(User $user, Task $task): bool
    {
        return $user->role === 'admin' || $task->user_id === $user->id;
    }

    /**
     * Only admins can create tasks.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Admins can update any task.
     * Users can only update their own task status
     */
    public function update(User $user, Task $task): bool
    {
        return $user->role === 'admin' || $task->user_id === $user->id;
    }

    /**
     * Only admins can delete tasks.
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Task $task): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        return false;
    }
}
