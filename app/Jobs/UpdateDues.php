<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\v1\Payment;
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
        $this->payment->due ? $this->updateDues() : $this->createDues();
    }

    private function updateDues()
    {
        $this->payment->due->payable->each(function ($payable) {
            $payable->payments()->sync();
        });
    }

    private function createDues()
    {
        $assignable = $this->payment->cost->costAssignable;

        if ($assignable instanceof Project) {
            $assignable->payments()->sync();
        } else {
            $assignable->reservations->each(function($reservation) {
                $reservation->payments()->sync();
            });
        }
    }
}
