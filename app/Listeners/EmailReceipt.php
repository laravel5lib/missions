<?php

namespace App\Listeners;

use App\Events\DonationWasMade;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailReceipt implements ShouldQueue
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
        $this->mailer->send('emails.donations.receipt', [
            'donation' => $event->donation, 'donor' => $event->donor
        ], function ($m) use ($event) {
            $m->to($event->donor->email, $event->donor->name)->subject('Thanks for the donation!');
        });
    }
}
