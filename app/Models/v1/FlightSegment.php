<?php

namespace App\Models\v1;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;

class FlightSegment extends Model
{
    protected $fillable = ['name', 'campaign_id'];

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
}
