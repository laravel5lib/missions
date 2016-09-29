<?php

namespace App\Events;

use App\Events\Event;
use App\Models\v1\TripInterest;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TripInterestWasCreated extends Event
{
    use SerializesModels;
    /**
     * @var TripInterest
     */
    public $interest;

    /**
     * Create a new event instance.
     *
     * @param TripInterest $interest
     */
    public function __construct(TripInterest $interest)
    {
        $this->interest = $interest;
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
