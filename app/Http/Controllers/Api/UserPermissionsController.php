<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\User;
use App\Http\Controllers\Controller;

class UserPermissionsController extends Controller
{

    /**
     * UserPermissionsController constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Give permission to user.
     *
     * @param $id
     * @return array
     */
    public function store($id)
    {
        $user = $this->user->findOrFail($id);

        if ($user->givePermissionTo(request()->get('name'))) {
            return ['message' => 'Permission given.'];
        }

        return ['message' => 'Unable to give permission.'];
    }

    /**
     * Revoke permission from user.
     *
     * @param $id
     * @return array
     */
    public function destroy($id)
    {
        $user = $this->user->findOrFail($id);

        if ($user->revokePermissionTo(request()->get('name'))) {
            return ['message' => 'Permission revoked.'];
        }

        return ['message' => 'Unable to revoke permission.'];
    }
}
