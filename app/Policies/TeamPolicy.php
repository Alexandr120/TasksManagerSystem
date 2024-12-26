<?php

namespace App\Policies;

use App\Models\User;

class TeamPolicy
{
    /**
     * Determine if can be view teams list.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('read teams list', 'api');
    }

    /**
     * Determine if can be view team.
     *
     * @param  User  $user
     * @return bool
     */
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('read team', 'api');
    }

    /**
     * Determine if the given team can be created.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create team', 'api');
    }

    /**
     * Determine if the given team can be updated.
     *
     * @param  User  $user
     * @return bool
     */
    public function update(User $user): bool
    {
        return $user->hasPermissionTo('update team', 'api');
    }

    /**
     * Determine if the given team user can be updated.
     *
     * @param  User  $user
     * @return bool
     */
    public function updateTeamUsers(User $user): bool
    {
        return $user->hasPermissionTo('update team users', 'api');
    }

    /**
     * Determine if the given team can be deleted.
     *
     * @param  User  $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('delete team', 'api');
    }
}
