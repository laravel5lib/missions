<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\v1\Payment;
use App\Models\v1\Project;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateDues extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $payment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $assignable = $this->payment->cost->costAssignable;

        if ($assignable instanceof Project) {
            $assignable->payments()->sync();
        } else {
            $assignable->reservations->each(function ($reservation) {
                $reservation->payments()->sync();
            });
        }
    }
}
