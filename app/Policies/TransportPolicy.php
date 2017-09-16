<?php

namespace App\Policies;

use App\Models\v1\User;
use App\CampaignTransport;

class TransportPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the campaignTransport.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->can('view_transports');
    }

    /**
     * Determine whether the user can create campaignTransports.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('add_transports');
    }

    /**
     * Determine whether the user can update the campaignTransport.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->can('edit_transports');
    }

    /**
     * Determine whether the user can delete the campaignTransport.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->can('delete_transports');
    }
}
