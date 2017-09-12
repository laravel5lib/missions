<?php

namespace App\Policies;

use App\Models\v1\Room;
use App\Models\v1\Team;
use App\Models\v1\Trip;
use App\Models\v1\User;
use App\Models\v1\Region;
use App\Models\v1\Campaign;
use App\Models\v1\RoomType;
use App\Models\v1\Transport;
use App\Models\v1\Promotional;

class CampaignPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the campaign.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Campaign  $campaign
     * @return mixed
     */
    public function view(User $user)
    {
        switch (request()->route('tab')) {
            case 'trips':
                return $user->can('view_campaigns') && $user->can('view', Trip::class);
                break;

            case 'squads':
                return $user->can('view_campaigns') && $user->can('view', Team::class);
                break;

            case 'squad-types':
                return $user->can('view_campaigns') && $user->can('view', Team::class);
                break;

            case 'accommodations':
                return $user->can('view_campaigns') && $user->can('view', Accommodation::class);
                break;

            case 'rooming-manager':
                return $user->can('view_campaigns') && $user->can('view', Room::class);
                break;

            case 'room-types':
                return $user->can('view_campaigns') && $user->can('view', RoomType::class);
                break;

            case 'transports':
                return $user->can('view_campaigns') && $user->can('view', Transport::class);
                break;

            case 'promotionals':
                return $user->can('view_campaigns') && $user->can('view', Promotional::class);
                break;

            case 'regions':
                return $user->can('view_campaigns') && $user->can('view', Region::class);
                break;

            default:
                return $user->can('view_campaigns');
                break;
        }
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
    public function update(User $user)
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
    public function delete(User $user)
    {
        return $user->can('delete_campaigns');
    }
}
