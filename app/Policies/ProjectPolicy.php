<?php

namespace App\Policies;

use App\Models\v1\User;
use App\Models\v1\Project;

class ProjectPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the project.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Project  $project
     * @return mixed
     */
    public function view(User $user, Project $project)
    {
        if ($user->can('view_projects')) {
            return true;
        }

        // only the sponsor can view the project
        return $user->isSponsor($project);
    }

    /**
     * Determine whether the user can create projects.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create_projects');
    }

    /**
     * Determine whether the user can update the project.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Project  $project
     * @return mixed
     */
    public function update(User $user, Project $project)
    {
        if ($user->can('edit_projects')) {
            return true;
        }

        // only the sponsor can edit the project
        return $user->isSponsor($project);
    }

    /**
     * Determine whether the user can delete the project.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Project  $project
     * @return mixed
     */
    public function delete(User $user, Project $project)
    {
        return $user->can('delete_projects');
    }

}
