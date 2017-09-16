<?php

namespace App\Policies;

use App\Models\v1\User;
use App\App\Models\v1\Team;

class TeamPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the team.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->can('view_squads');
    }

    /**
     * Determine whether the user can create teams.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('add_squads');
    }

    /**
     * Determine whether the user can update the team.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->can('edit_squads');
    }

    /**
     * Determine whether the user can delete the team.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->can('delete_squads');
    }
}
