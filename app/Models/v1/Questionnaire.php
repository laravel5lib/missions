<?php

namespace App\Models\v1;

use App\UuidForKey;
use App\Models\v1\Reservation;
use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    use UuidForKey;

    protected $fillable = ['type', 'content', 'reservation_id'];

    protected $casts = ['content' => 'array'];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function scopeManagedBy($query, $managerId)
    {
        $ids = static::whereHas('reservation.trip.group.managers', function ($manager) use($managerId) {
            return $manager->whereId($managerId);
        })->pluck('reservation_id')->toArray();

        array_push($ids, $this->reservation_id);

        return $query->whereIn('reservation_id', $ids);
    }
}
