<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    use Filterable, UuidForKey;

    protected $fillable = [
        'reservation_id', 'transport_id',
        'seat_no'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function transport()
    {
        return $this->belongsTo(Transport::class);
    }

    /**
     * Get all of the passenger's tags.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
