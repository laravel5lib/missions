<?php

namespace App;

use App\Models\v1\Campaign;
use App\Models\v1\Hub;
use App\Models\v1\Passenger;
use Carbon\Carbon;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampaignTransport extends Model
{
    use UuidForKey, SoftDeletes, Filterable;

    protected $table = 'transports';

    protected $guarded = [];

    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'depart_at', 'arrive_at'];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function departureHub()
    {
        return $this->belongsTo(Hub::class, 'departure_hub_id');
    }

    public function arrivalHub()
    {
        return $this->belongsTo(Hub::class, 'arrival_hub_id');
    }

    public function passengers()
    {
        return $this->hasMany(Passenger::class, 'transport_id');
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower(trim($value));
    }

    public function setCallSignAttribute($value)
    {
        if (! is_null($value)) {
            $value = strtolower(trim($value));
        }

        $this->attributes['call_sign'] = $value;
    }

    public function setDepartAtAttribute($value)
    {
        if (! is_null($value)) {
            $value = Carbon::parse($value);
            // we make sure trailing seconds are 0
            $value->setTime($value->format("H"), $value->format("i"), 0);
        }

        $this->attributes['depart_at'] = $value;
    }

    public function setArriveAtAttribute($value)
    {
        if (! is_null($value)) {
            $value = Carbon::parse($value);
            // we make sure trailing seconds are 0
            $value->setTime($value->format("H"), $value->format("i"), 0);
        }

        $this->attributes['arrive_at'] = $value;
    }

    public function seatsLeft()
    {
        $this->load('passengers');

        return $this->capacity - $this->passengers()->count();
    }

    public function groups()
    {
        $this->load('passengers.reservation.trip.group');

        if ($this->passengers)
            $groups = $this->passengers()
                ->get()
                ->pluck('reservation')
                ->flatten()
                ->pluck('trip')
                ->flatten()
                ->pluck('group')
                ->flatten()
                ->unique();

        return $groups;

        return [];
    }

    public function designations()
    {
        $this->load('passengers.reservation');

        if ($this->passengers)
            $designations = $this->passengers()
                ->get()
                ->pluck('reservation.designation')
                ->unique();

        return $designations;

        return [];
    }
}
