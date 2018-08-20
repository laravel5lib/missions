<?php

namespace App\Jobs\Reservations;

use App\Jobs\Job;
use App\Models\v1\Reservation;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessReservation extends Job
{
    use SerializesModels;
    /**
     * @var Reservation
     */
    private $reservation;
    private $costs;
    private $tags;

    /**
     * Create a new job instance.
     * @param Reservation $reservation
     * @param null $costs
     * @param null $tags
     */
    public function __construct(Reservation $reservation, $costs = null, $tags = null)
    {
        $this->reservation = $reservation;
        $this->costs = $costs;
        $this->tags = $tags;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->reservation->process($this->costs);
    }
}
