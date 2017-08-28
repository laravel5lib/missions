<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests;
use App\Models\v1\User;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function show($id)
    {
        $user = User::findOrFail($id);

        if (!$user->public && (auth()->user() ? auth()->user()->id : null) <> $user->id) {
            abort(403);
        }

        return view('site.users.profile', compact('user'));
    }
}
