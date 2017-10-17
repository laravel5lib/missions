<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Models\v1\User;
use App\Http\Controllers\Controller;

class UserRolesController extends Controller
{

    /**
     * UserRolesController constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Assign role to user.
     *
     * @param $id
     * @return array
     */
    public function store($id)
    {
        $user = $this->user->findOrFail($id);

        if ($user->assignRole(request()->get('name'))) {
            return ['message' => 'Role assigned.'];
        }

        return ['message' => 'Unable to assign role.'];
    }

    /**
     * Remove role from user.
     *
     * @param $id
     * @return array
     */
    public function destroy($id, $role)
    {
        $user = $this->user->findOrFail($id);

        if ($user->removeRole($role)) {
            return ['message' => 'Role revoked.'];
        }

        return ['message' => 'Unable to revoke role.'];
    }
}
