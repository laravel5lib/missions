<?php

namespace App\Policies;

use App\Models\v1\User;
use App\App\Models\v1\Region;

class RegionPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the region.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\App\Models\v1\Region  $region
     * @return mixed
     */
    public function view(User $user, Region $region)
    {
        return $user->can('view_regions');
    }

    /**
     * Determine whether the user can create regions.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('add_regions');
    }

    /**
     * Determine whether the user can update the region.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\App\Models\v1\Region  $region
     * @return mixed
     */
    public function update(User $user, Region $region)
    {
        return $user->can('edit_regions');
    }

    /**
     * Determine whether the user can delete the region.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\App\Models\v1\Region  $region
     * @return mixed
     */
    public function delete(User $user, Region $region)
    {
        return $user->can('delete_regions');
    }
}
