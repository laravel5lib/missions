<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class GroupsController extends Controller
{
    public function profile($slug)
    {
//        $response = $this->api->raw()->get('/groups', [
//            'include' => 'social',
//            'url' => $slug
//        ]);
//
//        $group = collect(json_decode($response->getContent())->data)->shift();

        $response = $this->api->get('/groups', [
            'include' => 'social',
            'url' => $slug
        ]);

        $group = $response->shift();

        return view('site.groups.profile', compact('group'));
    }
}
