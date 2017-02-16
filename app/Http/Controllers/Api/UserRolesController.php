<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserRolesController extends Controller
{

    /**
     * UserRolesController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function store($id, Request $request)
    {
        $user = $this->user->findOrFail($id);

        if ($user->assign($request->get('name'))) return ['message' => 'Role assigned.'];

        return ['message' => 'Unable to assign role.'];
    }

    public function destroy($id, Request $request)
    {
        $user = $this->user->findOrFail($id);

        if ($user->retract($request->get('name'))) return ['message' => 'Role revoked.'];

        return ['message' => 'Unable to revoke role.'];
    }
}
