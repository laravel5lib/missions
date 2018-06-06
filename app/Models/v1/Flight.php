<?php

namespace App\Models\v1;

use Ramsey\Uuid\Uuid;
use App\Models\v1\FlightSegment;
use App\Models\v1\FlightItinerary;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $fillable = ['flight_segment_id', 'flight_no', 'date', 'time', 'iata_code', 'flight_itinerary_id'];

    protected $dates = ['date', 'created_at', 'updated_at'];

    /**
     * Boot the Uuid trait for the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Uuid::uuid4();
        });
    }

    public function flightItinerary()
    {
        return $this->belongsTo(FlightItinerary::class)->withCount('reservations');
    }

    public function flightSegment()
    {
        return $this->belongsTo(FlightSegment::class);
    }

    public function scopeItinerary($query, $uuid)
    {
        return $query->whereHas('flightItinerary', function ($subQuery) use ($uuid) {
            return $subQuery->whereUuid($uuid);
        });
    }

    public function scopeSegment($query, $uuid)
    {
        return $query->whereHas('flightSegment', function ($subQuery) use ($uuid) {
            return $subQuery->whereUuid($uuid);
        });
    }

    public function setFlightNoAttribute($value)
    {
        $this->attributes['flight_no'] = strtoupper($value);
    }

    public function setIataCodeAttribute($value)
    {
        $this->attributes['iata_code'] = strtoupper($value);
    }
}
