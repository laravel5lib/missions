<?php

namespace App\Listeners;

use App\Events\DonationWasMade;
use App\Models\v1\Reservation;
use App\Models\v1\Trip;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyRecipient
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
     * @param  DonationWasMade  $event
     * @return void
     */
    public function handle(DonationWasMade $event)
    {
        $recipient = $this->getRecipient($event->donation);

        $this->mailer->send('emails.donations.notification', [
            'donation' => $event->donation, 'donor' => $event->donor, 'recipient' => $recipient
        ], function ($m) use ($recipient) {
            $m->to($recipient->email, $recipient->name)->subject('New Donation Received!');
        });
    }

    private function getRecipient($donation)
    {
        $fundable = $donation->fund->fundable;

        // get the user managing the reservation
        if($fundable instanceof Reservation::class) {
            return $fundable->user;
        }

        // get the group managing the trip
        if($fundable instanceof Trip::class) {
            return $fundable->group;
        }

        // otherwise
        return $fundable;
    }
}
