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

        if( !$user->public && (auth()->user() ? auth()->user()->id : null) <> $user->id) abort(403);

        return view('site.users.profile', compact('user'));
    }
}
