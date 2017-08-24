<?php

namespace App\Policies;

use App\Models\v1\User;
use App\Models\v1\Campaign;

class CampaignPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the campaign.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Campaign  $campaign
     * @return mixed
     */
    public function view(User $user, Campaign $campaign)
    {
        return $user->can('view_campaigns');
    }

    /**
     * Determine whether the user can create campaigns.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('add_campaigns');
    }

    /**
     * Determine whether the user can update the campaign.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Campaign  $campaign
     * @return mixed
     */
    public function update(User $user, Campaign $campaign)
    {
        return $user->can('edit_campaigns');
    }

    /**
     * Determine whether the user can delete the campaign.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Campaign  $campaign
     * @return mixed
     */
    public function delete(User $user, Campaign $campaign)
    {
        return $user->can('delete_campaigns');
    }
}
