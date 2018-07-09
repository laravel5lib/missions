<?php

namespace App\Policies;

use App\Models\v1\User;
use App\Models\v1\Group;

class GroupPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the group.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Group  $group
     * @return mixed
     */
    public function view(User $user, Group $group = null)
    {
        if ($user->can('view_groups')) {
            return true;
        }

        // only group managers can view the group.
        return $group ? in_array($user->id, $group->managers()->get(['id'])->toArray()) : false;
    }

    /**
     * Determine whether the user can create groups.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('add_groups');
    }

    /**
     * Determine whether the user can update the group.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Group  $group
     * @return mixed
     */
    public function update(User $user, Group $group)
    {
        if ($user->can('edit_groups')) {
            return true;
        }

        // only group managers can update the group.
        return in_array($user->id, $group->managers()->get('id')->toArray());
    }

    /**
     * Determine whether the user can delete the group.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Group  $group
     * @return mixed
     */
    public function delete(User $user, Group $group)
    {
        return $user->can('delete_groups');
    }
}
