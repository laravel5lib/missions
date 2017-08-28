<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\v1\Project;
use App\Models\v1\ProjectCause;

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
     * Show all projects .
     */
    public function index()
    {
        return view('dashboard.projects.index');
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

        $project = $this->project->findOrFail($id);

        return view('dashboard.projects.' . $tab, compact('project', 'tab'));
    }
}
