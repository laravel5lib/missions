<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests;
use App\Models\v1\Slug;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;

class GroupsController extends Controller
{
    use SEOTools;

    public function index()
    {
        $this->seo()->setTitle('Custom Group Mission Trips');

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

        $this->seo()->setTitle($group->name);

        return view('site.groups.profile', compact('group'))->with('isProfile', true);
    }

    public function signup($slug)
    {
        $id = Slug::where('url', $slug)
               ->where('slugable_type', 'groups')
               ->pluck('slugable_id')
               ->first();

        $group = $this->api->get('/groups/'.$id);

        $this->seo()->setTitle('Sign up with ' . $group->name);

        return view('site.groups.signup', compact('group'));
    }
}
