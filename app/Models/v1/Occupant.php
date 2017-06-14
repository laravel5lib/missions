<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Occupant extends Model
{
    use Filterable, UuidForKey;

    protected $guarded = [];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function rooms()
    {
        return $this->belongsTo(Room::class);
    }

    public function reservations()
    {
        return $this->belongsTo(Reservation::class);
    }
}
