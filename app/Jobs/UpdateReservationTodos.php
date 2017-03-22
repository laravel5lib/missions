<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\v1\Todo;
use App\Models\v1\Trip;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateReservationTodos extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $trip;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Trip $trip)
    {
        $this->trip = $trip;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->trip->reservations->each(function ($reservation) {
            $reservation->syncTodos($this->trip->todos);
        });
    }
}
