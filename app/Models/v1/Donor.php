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
        'city', 'state', 'country_code',
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
}
