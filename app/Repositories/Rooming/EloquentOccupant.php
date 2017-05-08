<?php

namespace App\Repositories\Rooming;

use App\Models\v1\Reservation;

class EloquentOccupant implements Occupant
{   
    protected $occupant;
    
    function __construct(Reservation $occupant)
    {
        $this->occupant = $occupant;
    }
}