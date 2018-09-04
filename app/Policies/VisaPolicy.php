<?php

namespace App\Policies;

use App\Models\v1\User;
use App\Models\v1\Visa;

class VisaPolicy extends BasePolicy
{

    /**
     * Determine whether the user can view the visa.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Visa  $visa
     * @return mixed
     */
    public function view(User $user, Visa $visa)
    {
        if ($user->can('view_visas')) {
            return true;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($visa) ?: $user->id == $visa->user_id;
    }

    /**
     * Determine whether the user can create visas.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        // admin routes require permission
        if (request()->is('admin/*')) {
            return $user->can('add_visas');
        }

        return true;
    }

    /**
     * Determine whether the user can update the visa.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Visa  $visa
     * @return mixed
     */
    public function update(User $user, Visa $visa)
    {
        // has permission?
        if ($user->can('edit_visas')) {
            return true;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($visa) ?: $user->id == $visa->user_id;
    }

    /**
     * Determine whether the user can delete the visa.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Visa  $visa
     * @return mixed
     */
    public function delete(User $user, Visa $visa)
    {
        // has permission?
        if ($user->can('delete_visas')) {
            return true;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($visa) ?: $user->id == $visa->user_id;
    }
}
