<?php

namespace App\Providers;

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
//            'App\Listeners\NotifyRecipient'
        ],
        'App\Events\ReservationWasCreated' => [
            'App\Listeners\EmailReservationConfirmation',
            'App\Listeners\NotifyFacilitatorsOfNewReservation'
        ]
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
        });

        Trip::created(function ($trip) {
            $trip->fund()->create([
                'name' => generateFundName($trip),
                'balance' => 0
            ]);
        });

        Reservation::created(function ($reservation) {

            $active = $reservation->trip->activeCosts()->with('payments')->get();

            $maxDate = $active->where('type', 'incremental')->max('active_at');

            $dues = $active->reject(function ($value) use($maxDate) {
                return $value->type == 'incremental' && $value->active_at < $maxDate;
            })->flatMap(function ($cost) {
                return $cost->payments->map(function ($payment) {
                    return [
                        'payment_id' => $payment->id,
                        'due_at' => $payment->due_at,
                        'grace_period' => $payment->grace_period,
                        'outstanding_balance' => $payment->amount_owed,
                    ];
                })->all();
            });

            $reservation->addDues($dues);
            $reservation->syncRequirements($reservation->trip->requirements);
            $reservation->syncDeadlines($reservation->trip->deadlines);
            $reservation->addTodos($reservation->trip->todos);

            $name = 'Send ' . $reservation->name . ' to ' . country($reservation->trip->country_code);

            $reservation->fund()->create([
                'name' => generateFundName($reservation),
                'balance' => 0
            ]);

            $reservation->fund->fundraisers()->create([
                'name' => $name,
                'url' => str_slug(country($reservation->trip->country_code)).'-'.$reservation->trip->started_at->format('Y').'-'.str_random(4),
                'description' => file_get_contents(resource_path('assets/sample_fundraiser.md')),
                'sponsor_type' => 'users',
                'sponsor_id' => $reservation->user_id,
                'goal_amount' => $reservation->getTotalCost(),
                'started_at' => $reservation->created_at,
                'ended_at' => $reservation->trip->started_at,
                'banner_upload_id' => $reservation->trip->campaign->banner->id
            ]);

            $reservation->trip()->update([
                'spots' => $reservation->trip->spots - 1
            ]);

//            event(new ReservationWasCreated($reservation));
        });
    }
}
