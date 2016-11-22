<?php

namespace App\Http\Controllers\admin;

use App\Models\v1\ProjectCause;
use App\Models\v1\ProjectInitiative;
use App\Http\Controllers\Controller;

class ProjectInitiativesController extends Controller
{

    /**
     * @var ProjectInitiative
     */
    private $initiative;
    /**
     * @var ProjectCause
     */
    private $cause;

    /**
     * ProjectInitiativesController constructor.
     * @param ProjectInitiative $initiative
     * @param ProjectCause $cause
     */
    public function __construct(ProjectInitiative $initiative, ProjectCause $cause)
    {
        $this->initiative = $initiative;
        $this->cause = $cause;
    }

    public function show($id)
    {
        $initiative = $this->initiative->findOrFail($id);

        return view('admin.causes.initiatives.show', compact('initiative'));
    }

    public function create($cause_id)
    {
        $cause = $this->cause->findOrFail($cause_id);

        return view('admin.causes.initiatives.create', compact('cause'));
    }
}
