<?php

namespace App\Http\Controllers\admin;

use App\Models\v1\ProjectCause;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProjectCausesController extends Controller
{
    public function index($tab = null)
    {
        $this->authorize('view', ProjectCause::class);

        return view('admin.causes.index', compact('tab'));
    }

    public function show($id)
    {
        $this->authorize('view', ProjectCause::class);

        $cause = $this->api->get('causes/' . $id);

        return view('admin.causes.show', compact('cause'));
    }
}
