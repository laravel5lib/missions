<?php

namespace App\Providers;

use App\Models\v1\Fund;
use App\Models\v1\Reservation;
use App\Models\v1\Transaction;
use App\Models\v1\Trip;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\DonationWasMade' => [
            'App\Listeners\EmailReceipt',
            'App\Listeners\NotifyRecipient'
        ],
        'App\Events\ReservationWasProcessed' => [
//            'App\Listeners\EmailReservationConfirmation',
//            'App\Listeners\NotifyFacilitatorsOfNewReservation'
        ]
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        'App\Listeners\ReservationEventListener',
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        Transaction::created(function ($transaction) {
            $balance = $transaction->fund->balance + $transaction->amount;
            $transaction->fund->balance = $balance;
            $transaction->fund->save();

            if($transaction->fund->fundable instanceof Reservation)
            {
                $amount = $transaction->amount;
                while ($amount > 0)
                {
                    $due = $transaction->fund->fundable->dues()
                        ->where('outstanding_balance', '>', 0)
                        ->orderBy('due_at', 'asc')
                        ->first();

                    $difference = $due->outstanding_balance - $amount;

                    if ($difference < 0)
                    {
                        $due->outstanding_balance = 0;
                        $amount = ($amount * -1); // make positive
                    } else
                    {
                        $due->outstanding_balance = $difference;
                        $amount = $amount - $difference;
                    }

                    $due->save();
                }
            }
        });

        Trip::created(function ($trip) {
            $trip->fund()->create([
                'name' => generateFundName($trip),
                'balance' => 0
            ]);
        });
    }
}
