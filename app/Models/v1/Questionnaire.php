<?php

namespace App\Models\v1;

use App\UuidForKey;
use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    use UuidForKey;

    protected $fillable = ['type', 'content', 'reservation_id'];

    protected $casts = ['content' => 'array'];

    /** 
     * Scope a query to those managed by the given group manager.
     * 
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @param  String $managerId
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeManagedBy($query, $managerId)
    {
        return $user->whereHas('reservations', function ($reservation) use($managerId) {
            return $reservation->whereHas('trip', function ($trip) use($managerId) {
                return $trip->whereHas('group', function ($group) use($managerId) {
                    return $group->whereHas('managers', function ($manager) use($managerId) {
                        return $manager->whereId($managerId);
                    });
                });
            });
        });
    }
}
