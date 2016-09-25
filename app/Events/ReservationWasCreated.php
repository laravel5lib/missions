<?php

namespace App\Events;

use App\Events\Event;
use App\Models\v1\Reservation;
use Illuminate\Http\Request;
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
     * @var Request
     */
    public $request;

    /**
     * Create a new event instance.
     *
     * @param Reservation $reservation
     * @param Request $request
     */
    public function __construct(Reservation $reservation, Request $request)
    {
        $this->reservation = $reservation;
        $this->request = $request;
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
