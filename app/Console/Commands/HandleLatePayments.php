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
    protected $signature = 'payments:penalize
                            { id : Reservation ID. }';

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
        $reservation = $this->reservation->findOrfail($this->argument('id'));

        $reservation->payments()->bump();
    }
}
