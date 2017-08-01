<?php

namespace App\Console\Commands;

use App\Jobs\Reservations\SendFacilitatorNotificationEmail;
use App\Models\v1\Reservation;
use Illuminate\Console\Command;
use App\Jobs\Reservations\SendConfirmationEmail as SendEmail;

class SendReservationConfirmationEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-reservation-confirmation 
                            { id : The reservation ID }
                            { email? : Recipient email }
                            {--notify : Whether the facilitator(s) should be notified.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a reservation confirmation email.';
    /**
     * @var Reservation
     */
    private $reservation;

    /**
     * Create a new command instance.
     *
     * @param Reservation $reservation
     */
    public function __construct(Reservation $reservation)
    {
        parent::__construct();
        $this->reservation = $reservation;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $id = $this->argument('id');
        $email = $this->argument('email');

        $reservation = $this->reservation->find($id);

        if (! $reservation) {
            $this->error('The reservation does not exist. Nothing sent.');
        }

        if ($reservation) {
            dispatch(new SendEmail($reservation, $email));
            $this->info('Done. Confirmation email processed.');

            if ($this->option('notify')) {
                dispatch(new SendFacilitatorNotificationEmail($reservation));
                $this->info('Facilitators have been notified.');
            }
        }
    }
}
