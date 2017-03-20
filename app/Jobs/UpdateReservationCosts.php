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
        $this->cost->costAssignable->reservations->each(function($reservation) {

            $active = $reservation->trip->activeCosts()->get();

            $maxDate = $active->where('type', 'incremental')->max('active_at');

            $tripCosts = $active->reject(function ($value) {
                // we remove optional costs so they don't 
                // get added to the reservation
                return $value->type == 'optional';
            })->reject(function ($value) use ($maxDate) {
                // we remove any incremental costs that are not the most 
                // recently activated cost to make sure they don't 
                // get added to the reservation
                return $value->type == 'incremental' && $value->active_at < $maxDate;
            });

            // we overwrite existing and remove duplicates
            // we remove other incremental costs already assigned to the reservation 
            // to make sure only one incremental cost is being applied
            $costs = $reservation->costs
                        ->merge($tripCosts)
                        ->unique()
                        ->intersect($active)->reject(function ($value) use($maxDate) {
                            return $value->type == 'incremental' && $value->active_at < $maxDate;
                        });

            $reservation->syncCosts($costs);
        });
    }
}
