<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UploadsController extends Controller
{
    public function index()
    {
        return view('admin.uploads.index');
    }

    public function show($id)
    {
        $user = $this->api->get('uploads/'.$id, ['include' => '']);
        return view('admin.uploads.show')->with('user', $user);
    }

    public function edit($id)
    {

        return view('admin.uploads.edit', compact('id'));
    }

    public function create()
    {
        return view('admin.uploads.create');
    }
}
