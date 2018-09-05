<?php

namespace App\Policies;

use App\Models\v1\User;
use App\Models\v1\MedicalCredential;

class MedicalCredentialPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the MedicalCredential.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\MedicalCredential  $credential
     * @return mixed
     */
    public function view(User $user, MedicalCredential $credential)
    {
        // has permission?
        if ($user->can('view_medical_credentials')) {
            return true;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($credential) ?: $user->id == $credential->user_id;
    }

    /**
     * Determine whether the user can create MedicalCredentials.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        // admin routes require permission
        if (request()->is('admin/*')) {
            return $user->can('add_medical_credentials');
        }

        return true;
    }

    /**
     * Determine whether the user can update the MedicalCredential.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\MedicalCredential  $credential
     * @return mixed
     */
    public function update(User $user, MedicalCredential $credential)
    {
        // has permission?
        if ($user->can('edit_medical_credentials')) {
            return true;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($credential) ?: $user->id == $credential->user_id;
    }

    /**
     * Determine whether the user can delete the MedicalCredential.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\MedicalCredential  $credential
     * @return mixed
     */
    public function delete(User $user, MedicalCredential $credential = null)
    {
        // has permission?
        if ($user->can('delete_medical_credentials')) {
            return true;
        }

        if (! $credential) {
            return false;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($credential) ?: $user->id == $credential->user_id;
    }
}
