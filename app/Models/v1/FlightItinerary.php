<?php

namespace App\Models\v1;

use Ramsey\Uuid\Uuid;
use App\Models\v1\Flight;
use App\Models\v1\Reservation;
use Illuminate\Database\Eloquent\Model;

class FlightItinerary extends Model
{
    protected $fillable = ['type', 'record_locator', 'published'];

    protected $casts = ['published' => 'boolean'];

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

    public function flights()
    {
        return $this->hasMany(Flight::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function scopeForCampaign($query, $campaignId)
    {
        return $query->whereHas('flights', function ($flight) use ($campaignId) {
            return $flight->whereHas('flightSegment', function ($segment) use ($campaignId) {
                return $segment->where('campaign_id', $campaignId);
            });
        });
    }
}
