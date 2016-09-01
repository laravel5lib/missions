<?php

namespace App\Events;

use App\Events\Event;
use App\Models\v1\Reservation;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ReservationWasCreated extends Event
{
    use SerializesModels;

    /**
     * @var Reservation
     */
    public $reservation;

    /**
     * Create a new event instance.
     *
     * @param Reservation $reservation
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
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
