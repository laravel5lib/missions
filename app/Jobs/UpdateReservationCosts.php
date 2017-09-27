<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\v1\Cost;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateReservationCosts extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $cost;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Cost $cost)
    {
        $this->cost = $cost;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->cost->costAssignable->reservations->each(function ($reservation) {
            $reservation->updateCosts();
        });
    }
}
