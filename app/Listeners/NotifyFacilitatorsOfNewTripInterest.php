<?php

namespace App\Listeners;

use App\Events\TripInterestWasCreated;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyFacilitatorsOfNewTripInterest implements ShouldQueue
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
        if (! $event->interest->trip->group->managers->count()) {
            return;
        }

        $event->interest->trip->group->managers->each(function ($manager) use ($event) {
            $this->mailer->send(
                'emails.interests.notification',
                ['interest' => $event->interest],
                function ($m) use ($manager) {
                    $m->to(
                        $manager->email,
                        $manager->name
                    )
                        ->subject('New Trip Interest!');
                }
            );
        });
    }
}
