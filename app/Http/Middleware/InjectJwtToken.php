<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Dingo\Api\Http\InternalRequest;
use Dingo\Api\Http\Response;
use Symfony\Component\HttpFoundation\Cookie;

class InjectJwtToken
{

    public function handle($request, Closure $next)
    {
      if ($request instanceof InternalRequest) {
        if($request->user()) {
          $token = JWTAuth::fromUser($request->user());
          $request->headers->set('authorization', sprintf('Bearer %s', $token));

          $response = $next($request);
          // Won't work here. Move to Authentication Process.
          // $response->withCookie(cookie('jwt-token', sprintf('Bearer %s', $token), 60));

          return $response;
        }
      }

      return$next($request);
    }
}
