<?php

namespace App\Providers;

use App\Models\v1\Campaign;
use App\Models\v1\Trip;
use App\Models\v1\User;
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
            'App\Listeners\EmailReservationConfirmation',
            'App\Listeners\NotifyFacilitatorsOfNewReservation'
        ],
        'App\Events\TripInterestWasCreated' => [
            'App\Listeners\NotifyFacilitatorsOfNewTripInterest',
            'App\Listeners\NotifyTripRepOfNewTripInterest'
        ]
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        'App\Listeners\ReservationEventListener',
        'App\Listeners\TransactionEventListener'
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

        Trip::created(function ($trip) {
            $trip->fund()->create([
                'name' => generateFundName($trip),
                'balance' => 0,
                'class' => generateQbClassName($trip),
                'item' => 'Missionary Donation'
            ]);
        });

        Campaign::created(function ($trip) {
            $trip->fund()->create([
                'name' => generateFundName($trip),
                'balance' => 0,
                'class' => generateQbClassName($trip),
                'item' => 'General Donation'
            ]);
        });

        User::created(function ($user) {
            $user->assign('member');
        });
    }
}
