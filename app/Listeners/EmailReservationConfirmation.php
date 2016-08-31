<?php

namespace App\Listeners;

use App\Events\ReservationWasCreated;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailReservationConfirmation
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
        $this->mailer->send('emails.reservations.confirmation', [
            'reservation' => $event->reservation
        ], function($m) use($event) {
            $m->to($event->reservation->user->email, $event->reservation->user->name)->subject('Pack Your Bags!');
        });
    }
}
