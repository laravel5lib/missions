<?php

namespace App\Listeners;

use App\Events\DonationWasMade;
use App\Jobs\Transactions\SendReceiptEmail;

class EmailReceipt
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

        dispatch(new SendReceiptEmail($donation));
    }
}
