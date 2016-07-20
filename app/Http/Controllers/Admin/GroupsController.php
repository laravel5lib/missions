<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class GroupsController extends Controller
{
    public function index()
    {
//        $groups = $this->api->get('groups');
        return view('admin.groups.index');
    }

    public function show($id)
    {
        $group = $this->api->get('groups/'.$id, ['include' => 'trips']);
        return view('admin.groups.show')->with('group', $group);
    }

    public function edit($id)
    {
        return view('admin.groups.edit')->with('id', $id);
    }

    public function create()
    {
        return view('admin.groups.create');
    }
}
