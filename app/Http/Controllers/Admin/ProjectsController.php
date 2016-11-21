<?php

namespace App\Http\Controllers\admin;

use App\Models\v1\Project;
use App\Models\v1\ProjectCause;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProjectsController extends Controller
{

    /**
     * @var Project
     */
    private $project;
    /**
     * @var ProjectCause
     */
    private $cause;

    /**
     * ProjectsController constructor.
     * @param Project $project
     * @param ProjectCause $cause
     */
    public function __construct(Project $project, ProjectCause $cause)
    {
        $this->project = $project;
        $this->cause = $cause;
    }

    /**
     * Show a project.
     *
     * @param $id
     * @param string $tab
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id, $tab = 'details')
    {
        $this->authorize('manage-projects');

        $project = $this->project->findOrFail($id);

        return view('admin.causes.projects.' . $tab, compact('project', 'tab'));
    }

    /**
     * Create a new project.
     *
     * @param $cause_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($cause_id)
    {
        $this->authorize('manage-projects');

        $cause = $this->cause->findOrFail($cause_id);

        return view('admin.causes.projects.create', compact('cause'));
    }
}
