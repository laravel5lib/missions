<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transport extends Model
{
    use Filterable, UuidForKey, SoftDeletes;

    protected $guarded = [];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function seatsLeft()
    {
        $this->load('passengers');

        return $this->capacity - $this->passengers()->count();
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function passengers()
    {
        return $this->hasMany(Passenger::class);
    }

    public function activities()
    {
        return $this->morphToMany(Activity::class, 'activitable');
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
