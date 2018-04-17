<?php

namespace App\Models\v1;

use App\UuidForKey;
use Carbon\Carbon;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Cost extends Model
{
    use Filterable, UuidForKey;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'costs';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['active_at'];

    /**
     * All of the relationships to be touched.
     * Update the parent's timestamp.
     *
     * @var array
     */
    protected $touches = ['reservations'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the amount in dollars
     *
     * @return string
     */
    public function amountInDollars()
    {
        $dollars = bcdiv($this->amount, 100, 2); // convert to dollars

        return number_format($dollars, 2, '.', '');
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
     * Get all of the owning cost assignable models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function costAssignable()
    {
        return $this->morphTo('cost_assignable');
    }

    /**
     * Get all the reservations with the cost.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function reservations()
    {
        return $this->belongsToMany(Reservation::class, 'reservation_costs')
                    ->withPivot('locked')
                    ->withTimestamps();
    }

    /**
     * Get all the payments for the cost.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get today's or the given date's balance due for the cost.
     *
     * @param null $date
     * @return mixed
     */
    public function getBalanceDue($date = null)
    {
        is_null($date) ? $date = Carbon::now() : $date;

        $this->load('payments');

        return $this->payments()->where('due_at', '<=', $date)->sum('amount_owed');
    }

    /**
     * Get only active costs.
     *
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->whereDate('active_at', '<=', Carbon::now())
                     ->orderBy('active_at', 'desc');
    }

    /**
     * Get only costs of the specified type.
     *
     * @param $query
     * @param $type
     * @return mixed
     */
    public function scopeType($query, $type)
    {
        return $query->whereType($type);
    }

    /**
     * Create a default payment for the cost
     * @return Payment
     */
    public function createDefaultPayment()
    {
        $payment = $this->payments()->create([
            'amount_owed' => $this->amount/100, // convert back to dollars
            'percent_owed' => 100,
            'due_at' => null,
            'upfront' => true,
            'grace_period' => 0
        ]);

        return $payment;
    }
}
