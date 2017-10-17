<?php

namespace App\Policies;

use App\Models\v1\User;

class CostPolicy extends BasePolicy
{

    /**
     * Determine whether the user can view the cost.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->can('view_costs');
    }

    /**
     * Determine whether the user can create costs.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('add_costs');
    }

    /**
     * Determine whether the user can update the cost.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->can('edit_costs');
    }

    /**
     * Determine whether the user can delete the cost.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->can('delete_costs');
    }
}
