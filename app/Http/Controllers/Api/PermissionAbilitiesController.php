<?php

namespace App\Http\Controllers\Api;

use App\Http\Transformers\v1\AbilityTransformer;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Silber\Bouncer\Database\Ability;

class PermissionAbilitiesController extends Controller
{

    /**
     * @var Ability
     */
    private $ability;

    /**
     * PermissionAbilitiesController constructor.
     * @param Ability $ability
     */
    public function __construct(Ability $ability)
    {
        $this->ability = $ability;
    }

    public function index()
    {
        $abilities = $this->ability->all();

        return $this->response->collection($abilities, new AbilityTransformer);
    }
}
