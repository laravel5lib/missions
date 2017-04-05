<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{
    use Filterable, UuidForKey;

    protected $guarded = [];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function passengers()
    {
        return $this->hasMany(Passenger::class);
    }

    public function schedule()
    {
        return $this->morphMany(ItineraryItem::class, 'attachment');
    }

    public function addPassenger()
    {
        //
    }

    public function removePassenger()
    {
        //
    }
}
