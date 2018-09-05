<?php

namespace App\Policies;

use App\Models\v1\User;
use App\Models\v1\MediaCredential;

class MediaCredentialPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the MediaCredential.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\MediaCredential  $credential
     * @return mixed
     */
    public function view(User $user, MediaCredential $credential)
    {
        // has permission?
        if ($user->can('view_media_credentials')) {
            return true;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($credential) ?: $user->id == $credential->user_id;
    }

    /**
     * Determine whether the user can create MediaCredentials.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        // admin routes require permission
        if (request()->is('admin/*')) {
            return $user->can('add_media_credentials');
        }

        return true;
    }

    /**
     * Determine whether the user can update the MediaCredential.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\MediaCredential  $credential
     * @return mixed
     */
    public function update(User $user, MediaCredential $credential)
    {
        // has permission?
        if ($user->can('edit_media_credentials')) {
            return true;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($credential) ?: $user->id == $credential->user_id;
    }

    /**
     * Determine whether the user can delete the MediaCredential.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\MediaCredential  $credential
     * @return mixed
     */
    public function delete(User $user, MediaCredential $credential = null)
    {
        // has permission?
        if ($user->can('delete_media_credentials')) {
            return true;
        }

        if (! $credential) {
            return false;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($credential) ?: $user->id == $credential->user_id;
    }
}
