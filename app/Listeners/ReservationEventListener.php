<?php

namespace App\Listeners;

use App\Jobs\AddReferral;
use App\Jobs\MakeDeposit;
use App\Jobs\ApplyPromoCode;
use App\Jobs\Reservations\SetupFunding;
use App\Jobs\Reservations\ProcessReservation;

class ReservationEventListener
{

    /**
     * Register for the Trip.
     *
     * @param $event
     */
    public function register($event)
    {
        $this->process($event);
        $this->updateSpotsAvailable($event);

        $params = $event->request->only(
            'donor',
            'payment',
            'token',
            'amount',
            'donor_id',
            'currency',
            'description',
            'details'
        ) + ['fund_id' => $event->reservation->fund->id, 'fund_name' => $event->reservation->fund->name];

        if ($event->request->get('amount') && $event->request->get('amount') > 0) {
            dispatch(new MakeDeposit($params));
        }

        $this->promos($event);
    }

    /**
     * Manage Promotionals
     *
     * @param  $event
     */
    public function promos($event)
    {
        if ($event->request->get('promocode')) {
            dispatch(new ApplyPromoCode($event->reservation, $event->request->get('promocode')));
        }

        if ($promos = $event->reservation->canBeRewarded()) {
            dispatch(new AddReferral($event->reservation, $promos));
        }
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
            'App\Events\RegisteredForTrip',
            'App\Listeners\ReservationEventListener@register'
        );
    }
}
