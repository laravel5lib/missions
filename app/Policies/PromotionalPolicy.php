<?php

namespace App\Policies;

use App\Models\v1\User;

class PromotionalPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the promotional.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->can('view_promotionals');
    }

    /**
     * Determine whether the user can create promotionals.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('add_promotionals');
    }

    /**
     * Determine whether the user can update the promotional.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->can('edit_promotionals');
    }

    /**
     * Determine whether the user can delete the promotional.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->can('delete_promotionals');
    }
}
