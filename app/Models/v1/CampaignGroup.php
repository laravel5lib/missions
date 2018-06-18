<?php

namespace App\Models\v1;

use Ramsey\Uuid\Uuid;
use App\Models\v1\Group;
use App\Models\v1\Price;
use App\Models\v1\Campaign;
use App\Models\v1\Reservation;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CampaignGroup extends Pivot
{
    protected $casts = ['meta' => 'array'];
    
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

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function organization()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function prices()
    {
        return $this->morphMany(Price::class, 'model', 'model_type', 'model_id', 'uuid');
    }

    public function getCurrentRate()
    {
        $rate = $this->prices()->whereHas('cost', function ($cost) {
            return $cost->whereType('incremental');
        })->whereHas('payments', function ($q) {

            return $q->whereDate('due_at', '>=', now());

        })->orderBy('active_at')->first();

        return $rate ?? $this->priceables()->whereHas('cost', function ($q) {

            return $q->type('incremental');

        })->first();
    }

    public function getUpfrontCosts()
    {
        return $this->prices()->whereHas('cost', function ($cost) {
            return $cost->whereType('upfront');
        })->get();
    }

    public function getCurrentStartingCostAttribute()
    {
        return (int) optional($this->getCurrentRate())->amount + $this->getUpfrontCosts()->sum('amount');
    }

    public function getUpcomingDeadline()
    {
        $payments = optional($this->getCurrentRate())->payments;
        
        return $payments ? $payments->first()->due_at : null;
    }

    public function getStatusAttribute()
    {
        return groupStatus($this->status_id);
    }

    public function tripsCount()
    {
        return $this->organization->trips()->where('campaign_id', $this->campaign_id)->count();
    }

    public function reservationsCount()
    {
        $tripIds = $this->organization
            ->trips()
            ->where('campaign_id', $this->campaign_id)
            ->pluck('id')
            ->all();

        return Reservation::whereIn('trip_id', $tripIds)->count();
    }

    public function scopeName($query, $name)
    {
        $query->whereHas('organization', function($org) use ($name) {
            return $org->where('name', 'LIKE', "%$name%");
        });
    }

    public function scopeHasPublishedTrips($query)
    {
        $query->whereHas('campaign', function($campaign) {
            return $campaign->whereHas('trips', function ($trip) {
                return $trip->public()->published();
            });
        });
    }
}
