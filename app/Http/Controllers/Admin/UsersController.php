<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }

    public function show($id)
    {
        $user = $this->api->get('users/'.$id, ['include' => '']);
        return view('admin.users.show')->with('user', $user);
    }

    public function edit($id)
    {

        return view('admin.users.edit', compact('id'));
    }

    public function create()
    {
         return view('admin.users.create');
    }
}
