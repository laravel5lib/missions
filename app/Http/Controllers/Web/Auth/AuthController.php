<?php

namespace App\Http\Controllers\Web\Auth;

use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use ThrottlesLogins;

    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout']]);
    }

    public function login()
    {
        return view('site.login');
    }

    /**
     * Handle an authentication attempt.
     *
     * @return Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            
            try {
                // attempt to verify the credentials and create a token for the user
                if (! $token = JWTAuth::attempt($credentials)) {
                    return response()->json(['error' => 'invalid_credentials'], 401);
                }
            } catch (JWTException $e) {
                // something went wrong whilst attempting to encode the token
                return response()->json(['error' => 'could_not_create_token'], 500);
            }

            $cookie = cookie('api_token', sprintf('Bearer %s', $token), 60, '/', null, false, false);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['redirect_to' => '/dashboard'])
                                 ->withCookie($cookie);
            }

            return redirect()->intended('/dashboard')
                             ->withCookie($cookie);
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/')->withCookie(Cookie::forget('api_token'));
    }

}
