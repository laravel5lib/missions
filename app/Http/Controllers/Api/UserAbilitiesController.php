<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Silber\Bouncer\Database\Ability;

class UserAbilitiesController extends Controller
{

    /**
     * UserAbilitiesController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function store($id, Request $request)
    {
        $user = $this->user->findOrFail($id);

        $map = collect(Relation::morphMap());

        $class = $map->get($request->get('entity_type'));

        if ($user->allow($request->get('name'), $class)) return ['message' => 'Ability allowed.'];

        return ['message' => 'Unable to allow ability.'];
    }

    public function destroy($id, Request $request)
    {
        $user = $this->user->findOrFail($id);

        $map = collect(Relation::morphMap());

        $class = $map->get($request->get('entity_type'));

        if ($user->disallow($request->get('name'), $class)) return ['message' => 'Ability denied.'];

        return ['message' => 'Unable to deny the ability.'];
    }
}
