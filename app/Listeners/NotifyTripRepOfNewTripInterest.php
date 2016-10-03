<?php

namespace App\Listeners;

use App\Events\TripInterestWasCreated;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyTripRepOfNewTripInterest implements ShouldQueue
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
     * @param  TripInterestWasCreated  $event
     * @return void
     */
    public function handle(TripInterestWasCreated $event)
    {
        if ( ! $event->interest->trip->rep) return;

        $this->mailer->send(
            'emails.interests.notification',
            ['interest' => $event->interest],
            function ($m) use ($event) {
                $m->to(
                    $event->interest->trip->rep->email,
                    $event->interest->trip->rep->name)
                  ->subject('New Trip Interest!');
        });
    }
}
