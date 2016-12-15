<?php

namespace App\Filters\v1;

use App\Models\v1\User;

trait Manageable {

    /**
     * By the managing/facilitating user.
     *
     * @param $user_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function manager($user_id)
    {
        $user = User::findOrFail($user_id);

        $facilitated_users = $user->facilitating->flatMap(function ($trip)
        {
            return $trip->reservations->pluck('user_id');
        });

        $managed_users = $user->managing->flatMap(function ($group)
        {
            return $group->trips->flatMap(function ($trip) {
                return $trip->reservations->pluck('user_id');
            });
        });

        $users = $facilitated_users->merge($managed_users)->unique()->all();

        return $this->whereIn('user_id', $users)->orWhere('user_id', $user_id);
    }
}