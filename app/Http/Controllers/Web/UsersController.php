<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests;
use App\Models\v1\User;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;

class UsersController extends Controller
{
    use SEOTools;

    public function show($id)
    {
        $user = User::findOrFail($id);

        if (!$user->public && (auth()->user() ? auth()->user()->id : null) <> $user->id) {
            abort(403);
        }

        $this->seo()->setTitle($user->name);

        return view('site.users.profile', compact('user'))->with('isProfile', true);
    }
}
