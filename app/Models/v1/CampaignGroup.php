<?php

namespace App\Models\v1;

use App\Models\v1\Campaign;
use App\Models\v1\Group;
use App\Models\v1\Price;
use App\Models\v1\Reservation;
use App\Traits\HasRequirements;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Ramsey\Uuid\Uuid;

class CampaignGroup extends Pivot
{
    use HasRequirements;
    
    /**
     * Attributes cast to native types.
     * 
     * @var array
     */
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

    /**
     * Get all the model's requirements.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function requirements()
    {
        return $this->morphMany(Requirement::class, 'requester', 'requester_type', 'requester_id', 'uuid');
    }

    /**
     * Get all requirements assigned to the campaign group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function requireables()
    {
        return $this->morphToMany(Requirement::class, 'requireable', 'requireables', 'requireable_id', 'requirement_id', 'uuid', 'id')
                    ->withTimestamps();
    }

    /**
     * Get the campaign the group belongs to.
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get the organization the group belongs to.
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    /**
     * Get the group's prices.
     * 
     * @return Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function prices()
    {
        return $this->morphMany(Price::class, 'model', 'model_type', 'model_id', 'uuid');
    }

    /**
     * Get the group's current rate.
     * 
     * @return Illuminate\Database\Eloquent\Collection
     */
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

    /**
     * Get the group's upfront costs.
     * 
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getUpfrontCosts()
    {
        return $this->prices()->whereHas('cost', function ($cost) {
            return $cost->whereType('upfront');
        })->get();
    }

    /**
     * Get the group's current starting cost.
     * 
     * @return integer
     */
    public function getCurrentStartingCostAttribute()
    {
        return (int) optional($this->getCurrentRate())->amount + $this->getUpfrontCosts()->sum('amount');
    }

    /**
     * Get the group's upcoming payment deadline.
     * 
     * @return Carbon\Carbon | null
     */
    public function getUpcomingDeadline()
    {
        $payments = optional($this->getCurrentRate())->payments;
        
        return $payments ? $payments->first()->due_at : null;
    }

    /**
     * Get the group's status attribute.
     * 
     * @return string
     */
    public function getStatusAttribute()
    {
        return groupStatus($this->status_id);
    }

    /**
     * Get the total number of trips belonging to the group.
     * 
     * @return integer
     */
    public function tripsCount()
    {
        return $this->organization->trips()->where('campaign_id', $this->campaign_id)->count();
    }

    /**
     * Get the total number of reservations belonging to the group.
     * 
     * @return integer
     */
    public function reservationsCount()
    {
        $tripIds = $this->organization
            ->trips()
            ->where('campaign_id', $this->campaign_id)
            ->pluck('id')
            ->all();

        return Reservation::whereIn('trip_id', $tripIds)->count();
    }

    /**
     * Scope a query to the name of the group's organization.
     * 
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @param  string $name
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeName($query, $name)
    {
        $query->whereHas('organization', function($org) use ($name) {
            return $org->where('name', 'LIKE', "%$name%");
        });
    }

    /**
     * Scope a query to only groups with published and public trips.
     * 
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @return Illuminate\Database\Eloquent\Builder 
     */
    public function scopeHasPublishedTrips($query)
    {
        $query->whereHas('campaign', function($campaign) {
            return $campaign->whereHas('trips', function ($trip) {
                return $trip->public()->published();
            });
        });
    }
}
