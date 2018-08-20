<?php

namespace App\Jobs\Reservations;

use App\Jobs\Job;
use App\Models\v1\Reservation;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendFacilitatorNotificationEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    /**
     * @var Reservation
     */
    private $reservation;

    /**
     * Create a new job instance.
     *
     * @param Reservation $reservation
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Execute the job.
     *
     * @param Mailer $mailer
     * @return bool
     */
    public function handle(Mailer $mailer)
    {
        $reservation = $this->reservation;

        $emails = $reservation->trip->group->managers()->pluck('email')->toArray();

        // admin monitors
        $emails = $emails + ['matt@missions.me', 'neil@missions.me'];

        if (! count($emails)) {
            return false;
        }

        $mailer->send(
            'emails.reservations.notification',
            ['reservation' => $reservation],
            function ($m) use ($reservation, $emails) {
                $m->bcc($emails)->subject('Congrats! Someone signed up for your trip.');
            }
        );

        return true;
    }
}
