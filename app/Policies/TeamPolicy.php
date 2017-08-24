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
     * @param  \App\App\Models\v1\Team  $team
     * @return mixed
     */
    public function view(User $user, Team $team)
    {
        return $user->can('view_teams');
    }

    /**
     * Determine whether the user can create teams.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('add_teams');
    }

    /**
     * Determine whether the user can update the team.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\App\Models\v1\Team  $team
     * @return mixed
     */
    public function update(User $user, Team $team)
    {
        return $user->can('edit_teams');
    }

    /**
     * Determine whether the user can delete the team.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\App\Models\v1\Team  $team
     * @return mixed
     */
    public function delete(User $user, Team $team)
    {
        return $user->can('delete_teams');
    }
}
