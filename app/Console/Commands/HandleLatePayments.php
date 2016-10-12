<?php

namespace App\Console\Commands;

use App\Models\v1\Reservation;
use Illuminate\Console\Command;

class HandleLatePayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:penalize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bump late payments to the next incrementing cost.';
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
        $reservations = $this->reservation->current()->get();

        $reservations->each(function($reservation) {
            $reservation->payments()->bump() ?
                $this->info('Costs updated for reservation id: '. $reservation->id) :
                $this->error('No changes made to reservation id: '. $reservation->id);
        });

        $this->info('All reservation costs updated.');
    }
}
