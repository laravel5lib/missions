<?php

namespace App\Jobs\Reservations;

use App\Jobs\Job;
use App\Models\v1\Reservation;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendConfirmationEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    /**
     * @var Reservation
     */
    private $reservation;

    /**
     * @var email
     */
    private $email;

    /**
     * Create a new job instance.
     *
     * @param Reservation $reservation
     */
    public function __construct(Reservation $reservation, $email = null)
    {
        $this->reservation = $reservation;
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @param Mailer $mailer
     */
    public function handle(Mailer $mailer)
    {
        $reservation = $this->reservation;
        $email = $this->email ? $this->email : $reservation->user->email;

        $mailer->send('emails.reservations.confirmation', [
            'reservation' => $reservation
        ], function ($m) use ($reservation, $email) {
            $m->to($email, $reservation->user->name)
              ->subject('Pack Your Bags!');
        });
    }
}
