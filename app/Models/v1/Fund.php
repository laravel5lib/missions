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
     * @return mixed
     */
    public function donations()
    {
        return $this->transactions()
                    ->type('donation')
                    ->latest();
    }

    /**
     * Get all the donors who donated to the fund.
     *
     * @return mixed
     */
    public function donors()
    {
        return $this->belongsToMany(Donor::class, 'transactions')
                    ->withPivot('created_at')
                    ->groupBy('name')
                    ->orderBy('name');
    }
}
