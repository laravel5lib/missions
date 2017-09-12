<?php

namespace App\Policies;

use App\Models\v1\User;

class RoomTypePolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the roomType.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->can('view_room_types');
    }

    /**
     * Determine whether the user can create roomTypes.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('add_room_types');
    }

    /**
     * Determine whether the user can update the roomType.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->can('edit_room_types');
    }

    /**
     * Determine whether the user can delete the roomType.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->can('delete_room_types');
    }
}
