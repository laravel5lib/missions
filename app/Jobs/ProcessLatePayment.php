<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\v1\Reservation;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessLatePayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $reservation;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($reservation = null)
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
        // process single reservation
        if ($this->reservation) {
            return $this->reservation->processLatePayment();
        }

        // process all current reservations
        Reservation::current()->get()->each(function($reservation) {
            $reservation->processLatePayment();
        });
    }
}
