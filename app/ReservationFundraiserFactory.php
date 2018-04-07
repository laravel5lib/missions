<?php
namespace App;

use App\Models\v1\Reservation;

class ReservationFundraiserFactory 
{
    protected $reservation;
    
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function make()
    {
        return [
            'type' => 'Mission Trip',
            'goal_amount' => $this->reservation->getTotalCost()/100,
            'sponsor_id' => $this->reservation->user_id,
            'sponsor_type' => 'users',
            'started_at' => \Carbon\Carbon::today()->toDateTimeString(),
            'ended_at' => $this->getDeadline()
        ];
    }

    private function getDeadline()
    {
        return $this->reservation->trip->started_at->toDateTimeString();
        
        // $cost = $this->reservation->trip->activeCosts()->type('incremental')->first();
        
        // return $cost->payments->last()->due_at->toDateTimeString();
    }
}