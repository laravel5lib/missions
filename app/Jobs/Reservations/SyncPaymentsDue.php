<?php

namespace App\Jobs\Reservations;

use App\Jobs\Job;
use App\Models\v1\Reservation;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SyncPaymentsDue extends Job implements ShouldQueue
{
    use SerializesModels;
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
     * @return void
     */
    public function handle()
    {
        $this->reservation->payments()->sync($this->reservation->costs);
    }
}
