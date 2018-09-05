<?php

namespace App\Policies;

use App\Models\v1\User;
use App\Models\v1\Passport;

class PassportPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the passport.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Passport  $passport
     * @return mixed
     */
    public function view(User $user, Passport $passport)
    {
        if ($user->can('view_passports')) {
            return true;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($passport) ?: $user->id == $passport->user_id;
    }

    /**
     * Determine whether the user can create passports.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if (request()->is('admin/*')) {
            return $user->can('add_passports');
        }

        return true;
    }

    /**
     * Determine whether the user can update the passport.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Passport  $passport
     * @return mixed
     */
    public function update(User $user, Passport $passport)
    {
        // has permission?
        if ($user->can('edit_passports')) {
            return true;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($passport) ?: $user->id == $passport->user_id;
    }

    /**
     * Determine whether the user can delete the passport.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Passport  $passport
     * @return mixed
     */
    public function delete(User $user, Passport $passport)
    {
        // has permission?
        if ($user->can('delete_passports')) {
            return true;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($passport) ?: $user->id == $passport->user_id;
    }
}
