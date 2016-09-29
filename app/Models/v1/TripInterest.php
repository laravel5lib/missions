<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class TripInterest extends Model
{
    use UuidForKey, Filterable;

    protected $fillable = [
        'name', 'email', 'phone', 'communication_preferences', 'trip_id'
    ];

    protected $casts = [
        'communication_preferences' => 'array'
    ];

    public function setCommunicationPreferencesAttribute($value)
    {
        $this->attributes['communication_preferences'] = json_encode($value);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}
