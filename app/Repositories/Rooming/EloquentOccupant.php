<?php

namespace App\Repositories\Rooming;

use App\Models\v1\Reservation;
use App\Repositories\EloquentRepository;
use App\Repositories\Rooming\Interfaces\Occupant;

class EloquentOccupant extends EloquentRepository implements Occupant
{   
    protected $model;
    
    function __construct(Reservation $model)
    {
        $this->model = $model;
    }

    public function promote($roomId, $id)
    {
        //
    }

    public function demote($roomId, $id)
    {
        //
    }
}