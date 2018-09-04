<?php

namespace App\Policies;

use App\Models\v1\User;
use App\Models\v1\MinorRelease;

class MinorReleasePolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the MinorRelease.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\MinorRelease  $release
     * @return mixed
     */
    public function view(User $user, MinorRelease $release)
    {
        // has permission?
        if ($user->can('view_minor_releases')) {
            return true;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($release) ?: $user->id == $release->reservation->user_id;
    }

    /**
     * Determine whether the user can create MinorReleases.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        // admin routes require permission
        if (request()->is('admin/*')) {
            return $user->can('add_minor_releases');
        }

        return true;
    }

    /**
     * Determine whether the user can update the MinorRelease.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\MinorRelease  $release
     * @return mixed
     */
    public function update(User $user, MinorRelease $release)
    {
        // has permission?
        if ($user->can('edit_minor_releases')) {
            return true;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($release) ?: $user->id == $release->reservation->user_id;
    }

    /**
     * Determine whether the user can delete the MinorRelease.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\MinorRelease  $release
     * @return mixed
     */
    public function delete(User $user, MinorRelease $release = null)
    {
        // has permission?
        if ($user->can('delete_minor_releases')) {
            return true;
        }

        if (! $release) {
            return false;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($release) ?: $user->id == $release->reservation->user_id;
    }
}
