<?php

namespace App\Policies;

use App\Models\v1\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BasePolicy
{
    use HandlesAuthorization;

    public function before(User $user)
    {
        if ($user->hasAnyRole('super_admin|admin')) {
            return true;
        }
    }
}
