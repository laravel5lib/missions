<?php

namespace App\Listeners;

use App\Jobs\MakeDeposit;
use App\Jobs\Reservations\ProcessReservation;
use App\Jobs\Reservations\SetupFunding;

class ReservationEventListener {

    /**
     * Register for the Trip.
     *
     * @param $event
     */
    public function register($event)
    {
        $fund = $this->setupFunding($event);

        $this->process($event);

        $params = $event->request->only(
            'donor', 'payment', 'token', 'amount', 'donor_id',
            'currency', 'description'
        ) + ['fund_id' => $fund->id, 'fund_name' => $fund->name];

        dispatch(new MakeDeposit($params));
    }

    /**
     * Handle reservation processing.
     * @param $event
     */
    public function process($event)
    {
        dispatch(new ProcessReservation(
            $event->reservation,
            $event->request->get('costs'),
            $event->request->get('tags')
        ));
    }

    /**
     * Setup funding for the reservation.
     *
     * @param $event
     * @return mixed
     */
    public function setupFunding($event)
    {
        $name = generateFundName($event->reservation);
        $fund = $event->reservation->fund()->create([
            'name' => $name,
            'slug' => generate_fund_slug($name),
            'balance' => 0,
            'class' => generateQbClassName($event->reservation),
            'item' => 'Missionary Donation'
        ]);

        dispatch(new SetupFunding($event->reservation));

        return $fund;
    }

    /**
     * Close a spot on the trip.
     *
     * @param $event
     */
    public function updateSpotsAvailable($event)
    {
        $event->reservation->trip->updateSpots(-1);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\ReservationWasCreated',
            'App\Listeners\ReservationEventListener@setupFunding'
        );

        $events->listen(
            'App\Events\ReservationWasCreated',
            'App\Listeners\ReservationEventListener@process'
        );

        $events->listen(
            'App\Events\ReservationWasCreated',
            'App\Listeners\ReservationEventListener@updateSpotsAvailable'
        );

        $events->listen(
            'App\Events\RegisteredForTrip',
            'App\Listeners\ReservationEventListener@register'
        );
    }

}