<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProjectCausesController extends Controller
{
    public function index($tab = null)
    {
        $this->authorize('manage-projects');

        return view('admin.causes.index', compact('tab'));
    }

    public function show($id)
    {
        $cause = $this->api->get('causes/' . $id);

        return view('admin.causes.show', compact('cause'));
    }
}
