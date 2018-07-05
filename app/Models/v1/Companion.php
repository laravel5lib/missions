<?php

namespace App\Models\v1;

use App\UuidForKey;
use Illuminate\Database\Eloquent\Model;

class Companion extends Model
{
    use UuidForKey;

    protected $guarded = [];

    public $timestamps = false;

    /**
     * The reservation the companion belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * The other companions related to this companion.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function companionReservation()
    {
        return $this->belongsTo(Reservation::class, 'companion_id');
    }

    public function scopeNotInSquad($query, $uuid)
    {
        return $query->whereHas('companionReservation.squadMemberships', function ($memberships) use ($uuid) {
            return $memberships->whereHas('squad', function ($squad) use ($uuid) {
                return $squad->where('uuid', '<>', $uuid);
            });
        })->orDoesntHave('companionReservation.squadMemberships');
    }
}
