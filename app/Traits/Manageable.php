<?php

namespace App\Traits;

trait Manageable
{
    /** 
     * Scope a query to those managed by the given group manager.
     * 
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @param  String $managerId
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeManagedBy($query, $managerId)
    {
        $userIds = static::whereHas('user', function($user) use($managerId) {
            return $user->whereHas('reservations', function ($reservation) use($managerId) {
                return $reservation->whereHas('trip', function ($trip) use($managerId) {
                    return $trip->whereHas('group', function ($group) use($managerId) {
                        return $group->whereHas('managers', function ($manager) use($managerId) {
                            return $manager->whereId($managerId);
                        });
                    });
                });
            });
        })->pluck('user_id')->toArray();

        array_push($userIds, $managerId);

        return $query->whereIn('user_id', $userIds);
    }
}