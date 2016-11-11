<?php

namespace App\Models\v1;

use App\UuidForKey;
use Conner\Tagging\Taggable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use UuidForKey, Filterable, Taggable;

    protected $table = 'transactions';

    protected $fillable = [
        'amount', 'type', 'payment', 'fund_id', 'donor_id', 'description'
    ];

    public function setPaymentAttribute($value)
    {
        $this->attributes['payment'] = json_encode($value);
    }

    protected $casts = [
        'payment' => 'array',
    ];

    /**
     * Get the parent fund the transaction belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fund()
    {
        return $this->belongsTo(Fund::class);
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
}
