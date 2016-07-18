<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Model;

class Companion extends Model
{
    protected $table = 'companions';

    protected $fillable = [
        'reservation_id', 'companion_reservation_id', 'relationship'
    ];

    public $timestamps = false;

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function companion_reservation()
    {
        return $this->belongsTo(Reservation::class, 'companion_reservation_id');
    }

    /**
     * Get all of the companion's tags.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
