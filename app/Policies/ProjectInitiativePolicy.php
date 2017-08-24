<?php

namespace App\Policies;

use App\Models\v1\User;
use App\Models\v1\ProjectInitiative;

class ProjectInitiativePolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the projectInitiative.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\ProjectInitiative  $projectInitiative
     * @return mixed
     */
    public function view(User $user, ProjectInitiative $projectInitiative)
    {
        return $user->can('view_initiatives');
    }

    /**
     * Determine whether the user can create projectInitiatives.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create_initiatives');
    }

    /**
     * Determine whether the user can update the projectInitiative.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\ProjectInitiative  $projectInitiative
     * @return mixed
     */
    public function update(User $user, ProjectInitiative $projectInitiative)
    {
        return $user->can('edit_initiatives');
    }

    /**
     * Determine whether the user can delete the projectInitiative.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\ProjectInitiative  $projectInitiative
     * @return mixed
     */
    public function delete(User $user, ProjectInitiative $projectInitiative)
    {
        return $user->can('delete_initiatives');
    }
}
