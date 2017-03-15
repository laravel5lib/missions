<?php

namespace App\Models\v1;

use App\UuidForKey;
use Conner\Tagging\Taggable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donor extends Model
{
    use UuidForKey, Filterable, Taggable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone', 'company', 'zip',
        'country_code', 'account_id', 'account_type',
        'customer_id', 'address', 'city', 'state',
        'created_at', 'updated_at'
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['total_donated_amount'];

    public function getTotalDonatedAmountAttribute()
    {
        return $this->donations()->sum('amount');
    }

    public function totalDonatedAmountInDollars()
    {
        return number_format($this->total_donated_amount/100, 2, '.', '');
    }

    public function account()
    {
        return $this->morphTo();
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
     * Get all the donor's donations.
     *
     * @param array $designation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function donations($designation = [])
    {
        $transactions = $this->hasMany(Transaction::class);

        // We can limit the results with a designation constraint.
        if( $designation <> []) {
            // Let's make the designation array a collection object
            // so it is easier to work with.
            $designation = collect($designation);

            // We limit the transactions returned to a specific fund
            // and between a start date and end date.
            // These values are passed by default when querying
            // for donations related to a fundraiser.
            $transactions = $transactions->where('fund_id', $designation->get('fund_id'));
        }

        return $transactions;
    }

    /**
     * Get all the funds the donor has given to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function funds()
    {
        return $this->belongsToMany(Fund::class, 'transactions')->withTrashed();
    }
}
