<?php

namespace App\Policies;

use App\Models\v1\User;
use App\App\Models\v1\TeamType;

class TeamTypePolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the teamType.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->can('view_team_types');
    }

    /**
     * Determine whether the user can create teamTypes.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('add_team_types');
    }

    /**
     * Determine whether the user can update the teamType.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->can('edit_team_types');
    }

    /**
     * Determine whether the user can delete the teamType.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->can('delete_team_types');
    }
}
