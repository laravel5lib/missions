<?php

namespace App\Policies;

use App\Models\v1\User as Member;
use Illuminate\Foundation\Auth\User;

class UserPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the member.
     *
     * @param  \Illuminate\Foundation\Auth\User  $user
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->can('view_users');
    }

    /**
     * Determine whether the user can create reservations.
     *
     * @param  \Illuminate\Foundation\Auth\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('add_users');
    }

    /**
     * Determine whether the user can update the member.
     *
     * @param \Illuminate\Foundation\Auth\User  $user
     * @param  \App\Models\v1\User as Member  $member
     * @return mixed
     */
    public function update(User $user, Member $member)
    {
        if ($user->can('edit_users')) {
            return true;
        }

        // only the user can update oneself
        return $user->id === $member->id;
    }

    /**
     * Determine whether the user can delete the member.
     *
     * @param  \Illuminate\Foundation\Auth\User   $user
     * @param  \App\Models\v1\User as Member  $member
     * @return mixed
     */
    public function delete(User $user, Member $member)
    {
        return $user->can('delete_users');
    }
}
