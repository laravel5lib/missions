<?php

namespace App\Events;

use App\Events\Event;
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
     * Create a new event instance.
     *
     * @param Transaction $donation
     */
    public function __construct(Transaction $donation)
    {
        $this->donation = $donation;
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
