<?php

namespace App\Http\Controllers\Web\Auth;

use App\Models\v1\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\v1\UserRequest;

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

    protected $user;

    public function __construct(User $user)
    {
        $this->middleware('guest', ['except' => ['logout', 'loginAsUser']]);
        $this->user = $user;
    }

    /**
     * Show the login screen.
     * 
     * @return Response
     */
    public function login()
    {
        return view('site.login');
    }

    /**
     * Show the registration form.
     * 
     * @return Response
     */
    public function create()
    {
        return view('site.register');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param Request $request
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

            $cookie = $this->makeApiTokenCookie($token);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['redirect_to' => '/dashboard', 'token' => sprintf('Bearer %s', $token)])
                                 ->withCookie($cookie);
            }

            return redirect()->intended('/dashboard')
                             ->withCookie($cookie);
        }
        return response()->json(['error' => 'invalid_credentials'], 401);
    }

    /**
     * Handle user registration.
     * 
     * @param  Request $request
     * @return Response
     */
    public function register(UserRequest $request)
    {
        $user = $this->user->create($request->all());

        Auth::login($user, true);

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::fromUser($user)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        $cookie = $this->makeApiTokenCookie($token);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['redirect_to' => '/dashboard', 'token' => sprintf('Bearer %s', $token)])
                             ->withCookie($cookie);
        }

        return redirect()->intended('/dashboard')
                         ->withCookie($cookie);
    }

    /**
     * Check if user is authenticated.
     * 
     * @return Response
     */
    public function check()
    {
        if (Auth::check()) {
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'User is logged in.', 'authenticated' => true]);
            }

            return response('User is Logged in!');
        }

        abort(403, 'Unauthorized action.');
    }

    /**
     * Log the user out.
     * 
     * @return Response
     */
    public function logout()
    {
        Auth::logout();

        return redirect('/login')->withCookie(Cookie::forget('api_token'));
    }

    /**
     * Make the API token cookie.
     */
    protected function makeApiTokenCookie($token)
    {
        return cookie('api_token', sprintf('Bearer %s', $token), 60, '/', null, false, false);
    }

}
