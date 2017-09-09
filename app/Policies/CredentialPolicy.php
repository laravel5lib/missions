<?php

namespace App\Policies;

use App\Models\v1\User;
use App\Models\v1\Credential;

class CredentialPolicy extends BasePolicy
{

    /**
     * Determine whether the user can view the credential.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Credential  $credential
     * @return mixed
     */
    public function view(User $user, Credential $credential)
    {
        // has permission?
        if ($user->can('view_credentials')) {
            return true;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($credential) ?: $user->id == $credential->holder_id;
    }

    /**
     * Determine whether the user can create credentials.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        // admin routes require permission
        if (request()->is('admin/*')) {
            return $user->can('create_credentials');
        }

        return true;
    }

    /**
     * Determine whether the user can update the credential.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Credential  $credential
     * @return mixed
     */
    public function update(User $user, Credential $credential)
    {
        // has permission?
        if ($user->can('edit_credentials')) {
            return true;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($credential) ?: $user->id == $credential->holder_id;
    }

    /**
     * Determine whether the user can delete the credential.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Credential  $credential
     * @return mixed
     */
    public function delete(User $user, Credential $credential)
    {
        // has permission?
        if ($user->can('delete_credentials')) {
            return true;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($credential) ?: $user->id == $credential->holder_id;
    }
}
