<?php

namespace App\Jobs\Reservations;

use App\Jobs\Job;
use App\Models\v1\Reservation;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetupFunding extends Job
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
        // create fundraiser
        $this->reservation->fund->fundraisers()->create([
            'name' => generateFundraiserName($this->reservation),
            'sponsor_type' => 'users',
            'sponsor_id' => $this->reservation->user_id,
            'goal_amount' => $this->reservation->getTotalCost()/100,
            'started_at' => $this->reservation->created_at,
            'ended_at' => $this->reservation
                    ->trip
                    ->activeCosts()
                    ->type('incremental')
                    ->first()
                    ->payments
                    ->last()
                    ->due_at,
            'public' => false // public by default
        ]);
    }

    protected function makeSlug()
    {
        $slug = generate_fundraiser_slug(
            country($this->reservation->trip->country_code).
                    '-'.
                    $this->reservation->trip->started_at->format('Y')
        );
        return $slug;
    }
}
