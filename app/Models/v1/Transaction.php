<?php

namespace App\Models\v1;

use App\UuidForKey;
use Conner\Tagging\Taggable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use UuidForKey, Filterable, Taggable, SoftDeletes;

    /**
     * The table used by the model.
     *
     * @var string
     */
    protected $table = 'transactions';

    /**
     * Relationships to include by default.
     *
     * @var string
     */
    protected $with = 'fund';

    protected $fillable = [
        'amount', 'type', 'details', 'fund_id', 'donor_id', 'anonymous', 'created_at', 'updated_at'
    ];

    public function amountInDollars()
    {
        return number_format($this->amount/100, 2, '.', ''); // convert to dollars
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = $value*100; // convert to cents
    }

    /**
     * Get the transactions description.
     *
     * @return string
     */
    public function getDescriptionAttribute()
    {
        $direction = $this->amount > 0 ? ' to ' : ' from ';

        return ucfirst(str_replace('_', ' ', $this->type) . $direction . $this->fund->name);
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['description'];

    /**
     * Set the transaction's details.
     *
     * @param $value
     */
    public function setDetailsAttribute($value)
    {
        $this->attributes['details'] = json_encode($value);
    }

    /**
     * Attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'details' => 'array'
    ];

    /**
     * Get the parent fund the transaction belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fund()
    {
        return $this->belongsTo(Fund::class)->withTrashed();
    }

    /**
     * If there is one, get the donor who made the transaction.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    /**
     * Get all of the reservation's notes
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notes()
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    /**
     * Only get transactions of the given type.
     *
     * @param $query
     * @param $type
     * @return mixed
     */
    public function scopeType($query, $type)
    {
        $query = $query->whereType($type);

        return $query;
    }

    /**
     * Get transactions on or after the given date.
     * @param $query
     * @param $date
     * @return mixed
     */
    public function scopeFrom($query, $date)
    {
        return $query->where('created_at', '>=', $date);
    }

    /**
     * Get transactions on or before the given date.
     *
     * @param $query
     * @param $date
     * @return mixed
     */
    public function scopeTo($query, $date)
    {
        return $query->where('created_at', '<=', $date);
    }
    
    /**
     * Scope a query to only include transactions 
     * with a donor matching the name.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDonorName($query, $name)
    {
        return $query->whereHas('donor', function ($subQuery) use ($name) {
            return $subQuery->whereRaw("concat(first_name, ' ', last_name) LIKE '%$name%'");
        });
    }

    /**
     * Scope a query to only include transactions 
     * with a fund matching the name.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFundName($query, $name)
    {
        return $query->whereHas('fund', function ($subQuery) use ($name) {
            return $subQuery->where('name', 'LIKE', "%$name%");
        });
    }

    public function scopeAmount($query, $amount)
    {
        return $query->where('amount', $amount*100);
    }

    public function scopeDonorEmail($query, $email)
    {
        return $query->whereHas('donor', function ($subQuery) use ($email) {
            return $subQuery->where('email', 'LIKE', "%$email%");
        });
    }

    public function scopePaymentMethod($query, $method)
    {
        return $query->where('details->type', $method);
    }

    public function scopeCardLastFour($query, $numbers)
    {
        return $query->where('details->last_four', $numbers);
    }

    public function scopeCreatedBetween($query, ...$dates)
    {
        return $query->whereDate('created_at', '>=', $dates[0])
              ->whereDate('created_at', '<=', $dates[1]);
    }

    public function scopeAccountingClass($query, $class)
    {
        return $query->whereHas('fund.accountingClass', function ($query) use ($class) {
            return $query->where('name', 'LIKE', "%$class%");
        });
    }

    public function scopeAccountingItem($query, $item)
    {
        return $query->whereHas('fund.accountingItem', function ($query) use ($item) {
            return $query->where('name', 'LIKE', "%$item%");
        });
    }
}
