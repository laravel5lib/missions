<?php

namespace App\Observers;

use App\Models\v1\Trip;
use Illuminate\Support\Facades\DB;

class TripObserver
{
    public function deleting(Trip $trip)
    {
        DB::transaction(function () use ($trip) {
            $trip->reservations()->delete();
        });
    }
}