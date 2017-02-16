<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\v1\Reservation;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPaymentsUpdatedEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public $reservation;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $reservation = $this->reservation;

        $mailer->send('emails.reservations.payments.updated', [
            'reservation' => $reservation
        ], function($m) use($reservation) {
            $m->to($reservation->user->email, $reservation->user->name)
              ->subject('Your trip costs have been updated!');
        });
    }
}
