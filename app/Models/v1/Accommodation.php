<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Accommodation extends Model
{
    use Filterable, UuidForKey;

    protected $fillable = [
        'name', 'address_one', 'address_two', 'city',
        'state', 'zip', 'phone', 'fax', 'country_code',
        'email', 'url', 'region_id', 'short_desc'
    ];

    protected $dates = [
        'check_in_at', 'check_out_at', 'created_at', 'updated_at'
    ];

    public function region()
    {
        // PROPOSED CHANGE
//        return $this->belongsToMany(Region::class, 'region_accommodations');
        return $this->belongsTo(Region::class);
    }

    public function occupants()
    {
        return $this->hasMany(Occupant::class);
    }

    /**
     * Get all of the accommodation's tags.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
