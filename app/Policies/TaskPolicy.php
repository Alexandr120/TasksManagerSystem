<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if can be view tasks list.
     *
     * @param  User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('read tasks list', 'api');
    }

    /**
     * Determine if can be view task.
     *
     * @param  User  $user
     * @return bool
     */
    public function view(User $user, Task $task): bool
    {
        if (!$user->hasRole('manager')) {
            return $user->hasPermissionTo('read task', 'api') && $user->id === $task->user_id;
        }

        return $user->hasPermissionTo('read task', 'api');
    }

    /**
     * Determine if the given task can be created by the user.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create task', 'api');
    }


    /**
     * Determine if the given task can be updated by the user.
     *
     * @param  User  $user
     * @param  Task  $task
     * @return bool
     */
    public function update(User $user, Task $task): bool
    {
        if (!$user->hasRole('manager')) {
            return $user->hasPermissionTo('update task', 'api') && $user->id === $task->user_id;
        }

        return $user->hasPermissionTo('update task', 'api');
    }

    /**
     * Determine if the given task can be deleted by the user.
     *
     * @param  User  $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('delete task', 'api');
    }

    /**
     * Determine if the given task can be read comments by the user.
     *
     * @param  User  $user
     * @param  Task  $task
     * @return bool
     */
    public function showComments(User $user, Task $task): bool
    {
        if (!$user->hasRole('manager')) {
            return $user->hasPermissionTo('read task comments', 'api') && $user->id === $task->user_id;
        }

        return $user->hasPermissionTo('read task comments', 'api');
    }

    /**
     * Determine if the given task can be added comments by the user.
     *
     * @param  User  $user
     * @param  Task  $task
     * @return bool
     */
    public function addComments(User $user, Task $task): bool
    {
        if (!$user->hasRole('manager')) {
            return $user->hasPermissionTo('create comment', 'api') && $user->id === $task->user_id;
        }

        return $user->hasPermissionTo('create comment', 'api');
    }
}
