<?php

namespace App\Models\v1;

use App\UuidForKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Itinerary extends Model
{
    use SoftDeletes, UuidForKey;

    protected $guarded = [];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function items()
    {
        return $this->belongsToMany(ItineraryItem::class, 'itinerary_item');
    }
}
