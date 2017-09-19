<?php

namespace App\Policies;

use App\Models\v1\User;
use App\Models\v1\Referral;

class ReferralPolicy extends BasePolicy
{

    /**
     * Determine whether the user can view the referral.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Referral  $referral
     * @return mixed
     */
    public function view(User $user, Referral $referral = null)
    {
        // has permission?
        if ($user->can('view_referrals')) {
            return true;
        }

        if (! $referral) {
            return false;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($referral) ?: $user->id == $referral->user_id;
    }

    /**
     * Determine whether the user can create referrals.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        // admin routes require permission
        if (request()->is('admin/*')) {
            return $user->can('add_referrals');
        }

        return true;
    }

    /**
     * Determine whether the user can update the referral.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Referral  $referral
     * @return mixed
     */
    public function update(User $user, Referral $referral)
    {
        // has permission?
        if ($user->can('edit_referrals')) {
            return true;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($referral) ?: $user->id == $referral->user_id;
    }

    /**
     * Determine whether the user can delete the referral.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Referral  $referral
     * @return mixed
     */
    public function delete(User $user, Referral $referral)
    {
        // has permission?
        if ($user->can('delete_referrals')) {
            return true;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($referral) ?: $user->id == $referral->user_id;
    }
}
