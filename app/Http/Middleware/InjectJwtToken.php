<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Dingo\Api\Http\InternalRequest;
use Dingo\Api\Http\Response;
use Illuminate\Support\Facades\Cookie;

class InjectJwtToken
{

    public function handle($request, Closure $next)
    {
      if ($request instanceof InternalRequest) {
        if($request->user()) {
          
          $token = JWTAuth::fromUser($request->user());
          $request->headers->set('authorization', sprintf('Bearer %s', $token));

          Cookie::queue('api_token', sprintf('Bearer %s', $token), 60, '/', null, false, false);

          return $next($request);
        }
      }

      return$next($request);
    }
}
