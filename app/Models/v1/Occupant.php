<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Occupant extends Model
{
    use Filterable, UuidForKey;

    protected $fillable = [
        'room_no', 'accommodation_id', 'reservation_id', 'room_leader'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function roommates()
    {
        // do something amazing
    }
}
