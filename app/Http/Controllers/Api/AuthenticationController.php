<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\v1\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\v1\UserRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\v1\AuthenticationRequest;

class AuthenticationController extends Controller
{
    /**
     * Authenticate a user.
     *
     * @param AuthenticationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(AuthenticationRequest $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token'));
    }

    /**
     * De-authenticate (logout) a user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deauthenticate()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Logged out!'], 200);
    }

    /**
     * Register a new user.
     *
     * @param UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(UserRequest $request)
    {
        $user = new User($request->all());
        $user->url = $request->get('url', str_random(10));
        $user->save();

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('token'));
    }
}