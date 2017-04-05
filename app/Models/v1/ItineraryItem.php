<?php

namespace App\Models\v1;

use App\UuidForKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItineraryItem extends Model
{
    use SoftDeletes, UuidForKey;

    protected $guarded = [];

    protected $dates = ['created_at', 'updated_at', 'occurs_at', 'deleted_at'];

    public function itineraries()
    {
        return $this->belongsToMany(Itinerary::class, 'itinerary_item');
    }

    public function attachment()
    {
        return $this->morphTo();
    }

    public function addAttachment(Array $attachment)
    {
        $this->attachment_type = $attachment['type'];
        $this->attachment_id = $attachment['id'];
        $this->save();
    }
}
