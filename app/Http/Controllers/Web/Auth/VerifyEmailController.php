<?php

namespace App\Http\Controllers\Web\Auth;

use App\Models\v1\User;
use Illuminate\Http\Request;
use App\Jobs\SendWelcomeEmail;
use App\Jobs\SendVerificationEmail;
use App\Http\Controllers\Controller;

class VerifyEmailController extends Controller
{
     /**
    * Handle a email verification request for the application.
    *
    * @param $token
    * @return \Illuminate\Http\Response
    */
    public function confirm($token)
    {
        $user = User::where('email_token', $token)->firstOrFail();

        $user->verified = 1;

        if ($user->save()) {

            dispatch(new SendWelcomeEmail($user));

            return view('auth.confirmed')->with('userId', $user->id);
        }
    }

    public function continue($userId)
    {
        if (auth()->guest()) {
            auth()->guard()->loginUsingId($userId);
        }

        return redirect()->intended('/dashboard');
    }

    public function verify()
    {
        $user = auth()->user();

        if ( ! $user->email_token) {
            if ($this->generateToken($user)) {
                dispatch(new SendVerificationEmail($user));
            }
        }

        return view('auth.verify')->with('user', $user);
    }

    public function sendEmail()
    {
        $user = User::findOrFail(request()->get('user_id'));

        if ($user->email_token) {
            dispatch(new SendVerificationEmail($user));
        }

        return redirect()->back()->with('user', $user);
    }

    private function generateToken($user)
    {
        $user->email_token = base64_encode($user->email);

        return $user->save();
    }
}
