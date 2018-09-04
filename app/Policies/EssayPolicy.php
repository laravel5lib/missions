<?php

namespace App\Policies;

use App\Models\v1\User;
use App\Models\v1\Essay;

class EssayPolicy extends BasePolicy
{

    /**
     * Determine whether the user can view the essay.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Essay  $essay
     * @return mixed
     */
    public function view(User $user, Essay $essay)
    {
        if ($user->can('view_essays')) {
            return true;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($essay) ?: $user->id == $essay->user_id;
    }

    /**
     * Determine whether the user can create essays.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        // admin routes require permission
        if (request()->is('admin/*')) {
            return $user->can('create_essays');
        }

        return true;
    }

    /**
     * Determine whether the user can update the essay.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Essay  $essay
     * @return mixed
     */
    public function update(User $user, Essay $essay)
    {
        // has permission?
        if ($user->can('edit_essays')) {
            return true;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($essay) ?: $user->id == $essay->user_id;
    }

    /**
     * Determine whether the user can delete the essay.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Essay  $essay
     * @return mixed
     */
    public function delete(User $user, Essay $essay)
    {
        // has permission?
        if ($user->can('delete_essays')) {
            return true;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($essay) ?: $user->id == $essay->user_id;
    }
}
