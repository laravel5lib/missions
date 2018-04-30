<?php

namespace App\Models\v1;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\v1\Cost;
use App\Models\v1\Trip;
use App\Models\v1\Payment;
use App\Models\v1\Reservation;
use Illuminate\Support\Facades\DB;
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

    /**
     * Mass assignable attributes
     *
     * @var array
     */
    protected $fillable = ['cost_id', 'price_id', 'amount', 'active_at', 'grace_days'];

    /**
     * Attributes that should be cast to dateTime instances.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'active_at'];

    /**
     * The cost this price belongs to.
     *
     * @return void
     */
    public function cost()
    {
        return $this->belongsTo(Cost::class);
    }

    /**
     * The owning model.
     *
     * @return void
     */
    public function model()
    {
        return $this->morphTo('model');
    }

    /**
     * Trips using this price.
     *
     * @return void
     */
    public function trips()
    {
        return $this->morphedByMany(Trip::class, 'priceable');
    }
    
    /**
     * Reservations using this price.
     *
     * @return void
     */
    public function reservations()
    {
        return $this->morphedByMany(Reservation::class, 'priceable');
    }

    /**
     * Payments owned by this price.
     *
     * @return void
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'price_id');
    }

    /**
     * Synchronize payments with this price.
     *
     * @param array $payments
     * @return void
     */
    public function syncPayments(array $payments)
    {
        // rollback in case of failure
        return DB::transaction(function() use($payments) {
            // remove old payments
            $this->payments()->delete();

            // add new payments
            $this->payments()->createMany($payments);
        });
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

    /**
     * Set the amount attribute
     *
     * @param integer $value
     */
    public function getAmountAttribute($value)
    {
        return (int) bcdiv($value, 100);
    }
}
