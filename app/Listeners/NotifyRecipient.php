<?php

namespace App\Listeners;

use App\Events\DonationWasMade;
use App\Jobs\Transactions\SendDonationNotificationEmail;

class NotifyRecipient
{
    /**
     * Handle the event.
     *
     * @param  DonationWasMade  $event
     * @return void
     */
    public function handle(DonationWasMade $event)
    {
        $donation = $event->donation;

        dispatch(new SendDonationNotificationEmail($donation));
    }
}
