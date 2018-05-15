<?php

namespace App\Listeners;

use App\Events\DonationWasMade;
use App\Models\v1\Reservation;

class TransactionEventListener
{

    /**
     * Handle updating the fund balance.
     * @param $event
     */
    public function updateFundBalance($event)
    {
        $transaction = $event->transaction;

        $transaction->fund->reconcile();

        if ($transaction->type == 'donation') {
            event(new DonationWasMade($transaction, $transaction->donor));
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\TransactionWasCreated',
            'App\Listeners\TransactionEventListener@updateFundBalance'
        );
    }
}
