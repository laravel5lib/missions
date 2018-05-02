<?php

namespace App\Models\v1;

use Ramsey\Uuid\Uuid;
use App\Models\v1\Group;
use App\Models\v1\Price;
use App\Models\v1\Campaign;
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
        return $this->prices()->whereHas('cost', function ($cost) {
            return $cost->whereType('incremental');
        })->first();
    }

    public function getUpfrontCosts()
    {
        return $this->prices()->whereHas('cost', function ($cost) {
            return $cost->whereType('upfront');
        });
    }

    public function getCurrentStartingCostAttribute()
    {
        return optional($this->getCurrentRate())->amount + $this->getUpfrontCosts()->sum('amount');
    }

    public function getStatusAttribute()
    {
        return groupStatus($this->status_id);
    }
}
