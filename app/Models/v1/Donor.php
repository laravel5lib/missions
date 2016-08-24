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
        'name', 'email', 'phone', 'company', 'zip', 'country_code'
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

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

            $transactions = $transactions->where('fund_id', $designation->get('fund_id'))
                ->whereBetween('created_at', [
                    $designation->get('started_at'),
                    $designation->get('ended_at')
                ]);
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
        return $this->belongsToMany(Fund::class, 'transactions');
    }
}
