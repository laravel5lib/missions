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
        'amount', 'type', 'payment', 'anonymous', 'fund_id', 'donor_id', 'description'
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
        return $this->hasOne(Donor::class);
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
}
