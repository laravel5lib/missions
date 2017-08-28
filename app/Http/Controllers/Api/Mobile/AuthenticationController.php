<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Transformers\v1\Mobile\UserTransformer;
use Tymon\JWTAuth\Facades\JWTAuth;
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

        // all good so return the authenticated user with authorization header.
        $response = $this->response->item(JWTAuth::user(), new UserTransformer);
        $response->headers->set('Authorization', 'Bearer '.$token);

        return $response;
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


    public function refresh()
    {
        $token = JWTAuth::parseToken()->refresh();

        return response()->json(compact('token'));
    }
}
