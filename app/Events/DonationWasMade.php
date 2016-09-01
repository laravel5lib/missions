<?php

namespace App\Events;

use App\Events\Event;
use App\Models\v1\Donor;
use App\Models\v1\Transaction;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DonationWasMade extends Event
{
    use SerializesModels;
    /**
     * @var Transaction
     */
    public $donation;
    /**
     * @var Donor
     */
    public $donor;

    /**
     * Create a new event instance.
     *
     * @param Transaction $donation
     * @param Donor $donor
     */
    public function __construct(Transaction $donation, Donor $donor)
    {
        $this->donation = $donation;
        $this->donor = $donor;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
