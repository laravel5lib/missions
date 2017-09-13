<?php

namespace App\Policies;

use App\Models\v1\User;

class RequirementPolicy extends BasePolicy
{

    /**
     * Determine whether the user can view the requirement.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->can('view_requirements');
    }

    /**
     * Determine whether the user can create requirements.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('add_requirements');
    }

    /**
     * Determine whether the user can update the requirement.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->can('edit_requirements');
    }

    /**
     * Determine whether the user can delete the requirement.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->can('delete_requirements');
    }
}
