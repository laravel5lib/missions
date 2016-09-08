<?php

namespace App\Jobs\Reservations;

use App\Jobs\Job;
use App\Models\v1\Reservation;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetupFunding extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
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
        // create fund
        $this->reservation->fund()->create([
            'name' => generateFundName($this->reservation),
            'balance' => 0
        ]);

        // create fundraiser
        $this->reservation->fund->fundraisers()->create([
            'name' => generateFundraiserName($this->reservation),
            'url' => $this->makeSlug(),
            'description' => file_get_contents(resource_path('assets/sample_fundraiser.md')),
            'sponsor_type' => 'users',
            'sponsor_id' => $this->reservation->user_id,
            'goal_amount' => $this->reservation->getTotalCost(),
            'started_at' => $this->reservation->created_at,
            'ended_at' => $this->reservation->trip->started_at,
            'banner_upload_id' => $this->reservation->trip->campaign->banner->id
        ]);
    }

    protected function makeSlug()
    {
        $slug = str_slug(country($this->reservation->trip->country_code)).
                '-'.
                $this->reservation->trip->started_at->format('Y').
                '-'.
                str_random(4);

        return $slug;
    }
}
