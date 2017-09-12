<?php

namespace App\Policies;

use App\Models\v1\User;
use App\App\Models\v1\Room;

class RoomPolicy extends BasePolicy
{

    /**
     * Determine whether the user can view the room.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->can('view_rooms');
    }

    /**
     * Determine whether the user can create rooms.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('add_rooms');
    }

    /**
     * Determine whether the user can update the room.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\App\Models\v1\Room  $room
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->can('edit_rooms');
    }

    /**
     * Determine whether the user can delete the room.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\App\Models\v1\Room  $room
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->can('delete_rooms');
    }
}
