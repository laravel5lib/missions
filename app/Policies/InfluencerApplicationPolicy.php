<?php

namespace App\Policies;

use App\Models\v1\User;
use App\Models\v1\InfluencerApplication;

class InfluencerApplicationPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the InfluencerApplication.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\InfluencerApplication  $application
     * @return mixed
     */
    public function view(User $user, InfluencerApplication $application)
    {
        // has permission?
        if ($user->can('view_influencer_applications')) {
            return true;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($application) ?: $user->id == $application->user_id;
    }

    /**
     * Determine whether the user can create InfluencerApplications.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        // admin routes require permission
        if (request()->is('admin/*')) {
            return $user->can('add_influencer_applications');
        }

        return true;
    }

    /**
     * Determine whether the user can update the InfluencerApplication.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\InfluencerApplication  $application
     * @return mixed
     */
    public function update(User $user, InfluencerApplication $application)
    {
        // has permission?
        if ($user->can('edit_influencer_applications')) {
            return true;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($application) ?: $user->id == $application->user_id;
    }

    /**
     * Determine whether the user can delete the InfluencerApplication.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\InfluencerApplication  $application
     * @return mixed
     */
    public function delete(User $user, InfluencerApplication $application = null)
    {
        // has permission?
        if ($user->can('delete_influencer_applications')) {
            return true;
        }

        if (! $application) {
            return false;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($application) ?: $user->id == $application->user_id;
    }
}
