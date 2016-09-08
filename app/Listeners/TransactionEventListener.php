<?php

namespace App\Listeners;

use App\Events\DonationWasMade;
use App\Models\v1\Reservation;

class TransactionEventListener {

    /**
     * Handle updating the fund balance.
     * @param $event
     */
    public function updateFundBalance($event) {
        $transaction = $event->transaction;

        $balance = $transaction->fund->balance + $transaction->amount;
        $transaction->fund->balance = $balance;
        $transaction->fund->save();

        if($transaction->type === 'donation') {
            event(new DonationWasMade($transaction, $transaction->donor));
        }
    }

    /**
     * Handle updating outstanding balances on payments due.
     *
     * @param $event
     */
    public function applyAsPayment($event) {
        $transaction = $event->transaction;

        if ($transaction->fund->fundable instanceof Reservation) {
            $transaction->fund->fundable
                ->payments()
                ->updateBalances($transaction->amount);
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

        $events->listen(
            'App\Events\TransactionWasCreated',
            'App\Listeners\TransactionEventListener@applyAsPayment'
        );
    }

}