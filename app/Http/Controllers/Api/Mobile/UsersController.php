<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Transformers\v1\Mobile\UserTransformer;
use App\Models\v1\User;

class UsersController extends Controller
{

    /**
     * Instantiate a new UsersController instance.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->middleware('api.auth');
//        $this->middleware('jwt.refresh');
        $this->user = $user;
    }

    /**
     * Get a single user
     *
     * @return \Dingo\Api\Http\Response
     */
    public function show()
    {
        $user = $this->auth->user();

        if(! $user) return $this->response->errorUnauthorized();

        return $this->response->item($user, new UserTransformer);
    }
}
