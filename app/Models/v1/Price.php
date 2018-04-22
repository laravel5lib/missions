<?php

namespace App\Models\v1;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\v1\Cost;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
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

    protected $fillable = ['cost_id', 'price_id', 'amount', 'active_at'];

    protected $dates = ['created_at', 'updated_at', 'active_at'];

    public function cost()
    {
        return $this->belongsTo(Cost::class);
    }

    public function model()
    {
        return $this->morphTo('model');
    }

    /**
     * Set the active_at attribute
     *
     * @param integer $value
     */
    public function setActiveAtAttribute($value)
    {
        if (is_string($value)) {
            $value = $value ? Carbon::parse($value) : null;
        }

        $this->attributes['active_at'] = $value;
    }

    /**
     * Set the amount attribute
     *
     * @param integer $value
     */
    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = (int) bcmul($value, 100); // convert to cents
    }
}
