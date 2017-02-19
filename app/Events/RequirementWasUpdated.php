<?php

namespace App\Events;

use App\Events\Event;
use App\Models\v1\ReservationRequirement;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RequirementWasUpdated extends Event
{
    use SerializesModels;

    /**
     * @var ReservationRequirement
     */
    public $requirement;

    /**
     * Create a new event instance.
     *
     * @param ReservationRequirement $requirement
     */
    public function __construct(ReservationRequirement $requirement)
    {
        $this->requirement = $requirement;
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
