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
        'name', 'email', 'phone', 'address_one', 'address_two',
        'city', 'state', 'zip', 'country_code',
        'account_holder_id', 'account_holder_type'
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function accountHolder()
    {
        return $this->morphTo();
    }

    /**
     * Get the total amount donated by the donor for all time
     * or by passing a specific designation filter.
     *
     * @param array $designation ['reservation' => '{id}']
     * @return mixed
     */
    public function totalDonated(array $designation = [])
    {
        return $this->donations()
                    ->filter($designation)
                    ->sum('amount');
    }
}
