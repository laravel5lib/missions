<?php

namespace App\Models\v1;

use App\UuidForKey;
use Conner\Tagging\Taggable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Fund extends Model
{
    use UuidForKey, Filterable, Taggable;

    protected $fillable = ['name', 'balance', 'fundable_id', 'fundable_type'];

    /**
     * Get all the fund's fundraisers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fundraisers()
    {
        return $this->hasMany(Fundraiser::class);
    }

    /**
     * Get all the models that can have a fund.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function fundable()
    {
        return $this->morphTo();
    }

    /**
     * Get all the fund's transactions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get all the donations made to this fund.
     *
     * @param null $started_at
     * @param null $ended_at
     * @return mixed
     */
    public function donations($started_at = null, $ended_at = null)
    {
        // we only want transactions that are donations.
        $donations = $this->transactions()->type('donation')->latest();

        // we can limit the results by passing in a start date.
        if( ! is_null($started_at))
            $donations->where('created_at', '>=', $started_at);

        // we can limit the results by passing in an end date.
        if( ! is_null($ended_at))
            $donations->where('created_at', '<=', $ended_at);

        return $donations;
    }

    /**
     * Get all the donors who donated to the fund.
     *
     * @param null $started_at
     * @param null $ended_at
     * @return mixed
     */
    public function donors($started_at = null, $ended_at = null)
    {
        // we grab the transaction's created_at timestamp so we can run queries against it.
        $donors = $this->belongsToMany(Donor::class, 'transactions')
            ->withPivot('created_at')
            ->groupBy('name')
            ->orderBy('name');

        // we can limit the results by passing in a start date.
        if( ! is_null($started_at))
            $donors = $donors->wherePivot('created_at', '>=', $started_at);

        // we can limit the results by passing in an end date.
        if( ! is_null($ended_at))
            $donors = $donors->wherePivot('created_at', '<=', $ended_at);

        return $donors;
    }
}
