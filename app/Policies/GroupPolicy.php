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
        return $group ? $this->isManager($user, $group) : false;
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
        return $this->isManager($user, $group);
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

    /**
     * Determine if the user is a group manager.
     * 
     * @param  User  $user
     * @param  Group  $group
     * @return boolean
     */
    private function isManager($user, $group)
    {
        $managers = $group->managers()->get(['id'])->toArray() ?? [];

        return in_array($user->id, $managers);
    }
}
