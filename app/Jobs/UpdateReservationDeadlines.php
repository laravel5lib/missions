<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\v1\Deadline;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateReservationDeadlines extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $deadline;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Deadline $deadline)
    {
        $this->deadline = $deadline;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->deadline
             ->deadlineAssignable
             ->reservations
             ->each(function ($reservation) {

                $deadlines = $this->deadline
                                  ->deadlineAssignable
                                  ->deadlines;

                $reservation->syncDeadlines($deadlines);
             });
    }
}
