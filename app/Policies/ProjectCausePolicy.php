<?php

namespace App\Policies;

use App\Models\v1\User;
use App\Models\v1\ProjectCause;

class ProjectCausePolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the projectCause.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\ProjectCause  $projectCause
     * @return mixed
     */
    public function view(User $user, ProjectCause $projectCause)
    {
        return $user->can('view_causes');
    }

    /**
     * Determine whether the user can create projectCauses.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('add_causes');
    }

    /**
     * Determine whether the user can update the projectCause.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\ProjectCause  $projectCause
     * @return mixed
     */
    public function update(User $user, ProjectCause $projectCause)
    {
        return $user->can('edit_causes');
    }

    /**
     * Determine whether the user can delete the projectCause.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\ProjectCause  $projectCause
     * @return mixed
     */
    public function delete(User $user, ProjectCause $projectCause)
    {
        return $user->can('delete_causes');
    }
}
