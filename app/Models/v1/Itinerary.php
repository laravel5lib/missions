<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Itinerary extends Model
{
    use SoftDeletes, UuidForKey, Filterable;

    protected $guarded = [];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function activities()
    {
        return $this->morphToMany(Activity::class, 'activitable');
    }

    public function curator()
    {
        return $this->morphTo();
    }
}
