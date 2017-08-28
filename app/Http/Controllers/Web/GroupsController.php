<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests;
use App\Models\v1\Slug;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GroupsController extends Controller
{
    public function index()
    {
        return view('site.groups.index');
    }

    public function show($id)
    {
        $group = $this->api->get('/groups/'.$id, [
            'include' => 'social,managers'
        ]);

        $authId = auth()->user() ? auth()->user()->id : null;

        if (!$group->public && ! $group->managers->pluck('id')->contains($authId)) {
            abort(403);
        }

        return view('site.groups.profile', compact('group'));
    }

    public function signup($slug)
    {
        $id = Slug::where('url', $slug)
               ->where('slugable_type', 'groups')
               ->pluck('slugable_id')
               ->first();

        $group = $this->api->get('/groups/'.$id);

        return view('site.groups.signup', compact('group'));
    }
}
