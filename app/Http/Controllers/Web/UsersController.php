<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function profile($slug)
    {
        $slug = str_replace('@', '', $slug);

        $user = $this->api->get('/users?url='.$slug);

        $user = $user->shift();

        return view('site.users.profile', compact('user'));
    }
}
