<?php

namespace App\Policies;

use App\Models\v1\User;
use App\Models\v1\AirportPreference;

class AirportPreferencePolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the airport preference.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\AirportPreference  $preference
     * @return mixed
     */
    public function view(User $user, AirportPreference $preference)
    {
        // has permission?
        if ($user->can('view_airport_preferences')) {
            return true;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($preference) ?: $user->id == $preference->reservation->user_id;
    }

    /**
     * Determine whether the user can create airport preferences.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        // admin routes require permission
        if (request()->is('admin/*')) {
            return $user->can('add_airport_preferences');
        }

        return true;
    }

    /**
     * Determine whether the user can update the airport preference.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\AirportPreference  $preference
     * @return mixed
     */
    public function update(User $user, AirportPreference $preference)
    {
        // has permission?
        if ($user->can('edit_airport_preferences')) {
            return true;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($preference) ?: $user->id == $preference->reservation->user_id;
    }

    /**
     * Determine whether the user can delete the airport preference.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\AirportPreference  $preference
     * @return mixed
     */
    public function delete(User $user, AirportPreference $preference = null)
    {
        // has permission?
        if ($user->can('delete_airport_preferences')) {
            return true;
        }

        if (! $preference) {
            return false;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($preference) ?: $user->id == $preference->reservation->user_id;
    }
}
