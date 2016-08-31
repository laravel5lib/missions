<?php

namespace App\Listeners;

use App\Events\ReservationWasCreated;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyFacilitatorsOfNewReservation implements ShouldQueue
{
    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * Create the event listener.
     *
     * @param Mailer $mailer
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     *
     * @param  ReservationWasCreated  $event
     * @return void
     */
    public function handle(ReservationWasCreated $event)
    {
        $facilitators = $event->reservation->trip->facilitators;

        foreach($facilitators as $facilitator)
        {
            $this->sendEmail($facilitator, $event);
        }
    }

    /**
     * Send the email message.
     *
     * @param $facilitator
     * @param $event
     */
    public function sendEmail($facilitator, $event)
    {
        $this->mailer->send('emails.reservations.notification', [
            'reservation' => $event->reservation,
            'trip' => $event->trip,
            'facilitator' => $facilitator,
        ], function($m) use($event, $facilitator) {
            $m->to($facilitator->email, $facilitator->name)
                ->subject(
                    'New ' .
                    $event->reservation->trip->campaign->name .
                    ' ' . ucwords($event->reservation->trip->type) .
                    ' Trip Reservation!'
                );
        });
    }
}
