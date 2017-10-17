<?php

namespace App\Policies;

use App\Models\v1\Cost;
use App\Models\v1\Note;
use App\Models\v1\Todo;
use App\Models\v1\Trip;
use App\Models\v1\User;
use App\Models\v1\Requirement;

class TripPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the trip.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Trip  $trip
     * @return mixed
     */
    public function view(User $user, Trip $trip = null)
    {
        switch (request()->route('tab')) {
            case 'pricing':
                return $user->can('view_trips') && $user->can('view', Cost::class);
                break;

            case 'todos':
                return $user->can('view_trips') && $user->can('view', Todo::class);
                break;

            case 'notes':
                return $user->can('view_trips') && $user->can('view', Note::class);
                break;

            case 'requirements':
                return $user->can('view_trips') && $user->can('view', Requirement::class);
                break;

            default:
                if (! $trip) {
                    return $user->can('view_trips');
                }

                return $user->can('view_trips') ?: (
                    $trip->group->managers->contains('id', $user->id) ?:
                    $trip->facilitators->contains('id', $user->id)
                );

                break;
        }
    }

    /**
     * Determine whether the user can create trips.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('add_trips');
    }

    /**
     * Determine whether the user can update the trip.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->can('edit_trips');
    }

    /**
     * Determine whether the user can delete the trip.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->can('delete_trips');
    }
}
