<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\v1\ProjectCause;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;

class ProjectCausesController extends Controller
{
    use SEOTools;

    public function index($tab = null)
    {
        $this->authorize('view', ProjectCause::class);

        $this->seo()->setTitle('Causes');

        return view('admin.causes.index', compact('tab'));
    }

    public function show($id)
    {
        $this->authorize('view', ProjectCause::class);

        $cause = $this->api->get('causes/' . $id);

        $this->seo()->setTitle($cause->name . ' Cause');

        return view('admin.causes.show', compact('cause'));
    }
}
