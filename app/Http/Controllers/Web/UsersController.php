<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function show($id)
    {
        $user = $this->api->get('/users/'.$id);

        return view('site.users.profile', compact('user'));
    }
}
