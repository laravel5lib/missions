<?php

namespace App\Policies;

use App\Models\v1\User;
use App\Models\v1\MedicalRelease;

class MedicalReleasePolicy extends BasePolicy
{

    /**
     * Determine whether the user can view the medicalRelease.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\MedicalRelease  $medicalRelease
     * @return mixed
     */
    public function view(User $user, MedicalRelease $medicalRelease = null)
    {
        // has permission?
        if ($user->can('view_medical_releases')) {
            return true;
        }

        if (! $medicalRelease) {
            return false;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($medicalRelease) ?: $user->id == $medicalRelease->user_id;
    }

    /**
     * Determine whether the user can create medicalReleases.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        // admin routes require permission
        if (request()->is('admin/*')) {
            return $user->can('add_medical_releases');
        }

        return true;
    }

    /**
     * Determine whether the user can update the medicalRelease.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\MedicalRelease  $medicalRelease
     * @return mixed
     */
    public function update(User $user, MedicalRelease $medicalRelease)
    {
        // has permission?
        if ($user->can('edit_medical_releases')) {
            return true;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($medicalRelease) ?: $user->id == $medicalRelease->user_id;
    }

    /**
     * Determine whether the user can delete the medicalRelease.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\MedicalRelease  $medicalRelease
     * @return mixed
     */
    public function delete(User $user, MedicalRelease $medicalRelease)
    {
        // has permission?
        if ($user->can('delete_medical_releases')) {
            return true;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($medicalRelease) ?: $user->id == $medicalRelease->user_id;
    }
}
