<?php

namespace App\Http\Middleware;

use Closure;
// use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Dingo\Api\Http\InternalRequest;

class InjectJwtToken
{

    public function handle($request, Closure $next)
    {
      if ($request instanceof InternalRequest) {
        if($request->user()) {
          $token = JWTAuth::fromUser($request->user());
          $request->headers->set('authorization', sprintf('Bearer %s', $token));
          response('Cookie set!')->withCookie(cookie('jwt-token', $token, 60));
        }
      }

        // if ($request instanceof Dingo\Api\Http\InternalRequest) {
        //   if ($request->cookie('jwt-token')) {
        //         $request->headers->set('authorization', sprintf('Bearer %s', $request->cookie('jwt-token')));
        //     }
        // }

        return $next($request);
    }
}
