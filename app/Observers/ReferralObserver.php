<?php

namespace App\Observers;

use App\Models\v1\Referral;
use App\Jobs\SendReferralRequestEmail;

class ReferralObserver
{
    /**
     * Handle to the referral "created" event.
     *
     * @param  App\Models\v1\Referral  $referral
     * @return void
     */
    public function created(Referral $referral)
    {
        SendReferralRequestEmail::dispatch($referral);
    }

    /**
     * Handle the referral "updated" event.
     *
     * @param  App\Models\v1\Referral  $referral
     * @return void
     */
    public function updated(Referral $referral)
    {
        if (is_null($referral->responded_at)) {
            SendReferralRequestEmail::dispatch($referral);
        }
    }

    /**
     * Handle the referral "deleting" event.
     * 
     * @param  App\Models\v1\Referral $referral
     * @return void
     */
    public function deleting(Referral $referral)
    {
        $referral->detachFromAllReservations();
    }
}
