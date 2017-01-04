<?php

namespace App\Http\Controllers\Api;

use App\Http\Transformers\v1\UserPermissionTransformer;
use App\Models\v1\Reservation;
use App\Models\v1\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Silber\Bouncer\Database\Ability;
use Silber\Bouncer\Database\Role;

class UserPermissionsController extends Controller
{

    /**
     * @var Role
     */
    private $role;
    /**
     * @var Ability
     */
    private $ability;

    /**
     * UserPermissionsController constructor.
     * @param User $user
     * @param Role $role
     * @param Ability $ability
     */
    public function __construct(User $user, Role $role, Ability $ability)
    {
        $this->user = $user;
        $this->role = $role;
        $this->ability = $ability;
    }

    public function index($user_id)
    {
        $user = $this->user->findOrFail($user_id);

        $abilities = $user->getAbilities();

        return $this->response->collection($abilities, new UserPermissionTransformer);
    }

    public function store($user_id, Request $request)
    {
        $user = $this->user->findOrFail($user_id);

        $user->allow(
            $request->get('name'),
            $request->get('entity_type', null)
        );
    }

    public function destroy($user_id, Request $request)
    {
        $user = $this->user->findOrFail($user_id);

        $user->disallow($request->get('name'));
    }
}
