<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class ReservationRequirement extends Model
{
    use UuidForKey, Filterable;

    protected $table = 'reservation_requirements';

    protected $guarded = [];

    /**
     * Get the associated document that fulfills the requirement.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function document()
    {
        return $this->morphTo();
    }

    /**
     * Get the associated reservation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class)->withTrashed();
    }

    /**
     * Get the parent requirement.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function requirement()
    {
        return $this->belongsTo(Requirement::class);
    }
}
