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
        'amount', 'type', 'details', 'fund_id', 'donor_id'
    ];

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
        'details' => 'array',
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
