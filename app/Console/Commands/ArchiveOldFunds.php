<?php

namespace App\Console\Commands;

use App\Models\v1\Fund;
use App\Models\v1\Trip;
use App\Models\v1\Reservation;
use Illuminate\Console\Command;

class ArchiveOldFunds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archive:funds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive all old funds or a fund by id';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Reservation $reservation, Trip $trip)
    {
        parent::__construct();
        $this->reservation = $reservation;
        $this->trip = $trip;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->confirm('Are you sure you want to archive all old funds? [y|N]')) {
            $reservations = $this->reservation->past()->has('fund')->with('fund')->get();

            foreach ($reservations as $reservation) {
                $reservation->fund->archive();
                $this->info('Archived fund '. $reservation->fund->id . ' [reservation]');
            }

            $trips = $this->trip->past()->has('fund')->with('fund')->get();

            foreach ($trips as $trip) {
                $trip->fund->archive();
                $this->info('Archived fund '. $trip->fund->id . ' [trip]');
            }
        }
    }
}
