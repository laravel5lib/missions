<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests;
use App\Models\v1\Project;
use Illuminate\Http\Request;
use App\Models\v1\ProjectCause;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;

class ProjectsController extends Controller
{
    use SEOTools;

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
        $this->authorize('view', $this->project);

        $project = $this->project->findOrFail($id);

        $title = $project->name . ' ' . title_case(str_replace("-", " ", $tab));
        $this->seo()->setTitle($title);

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
        $this->authorize('create', $this->project);

        $cause = $this->cause->findOrFail($cause_id);

        $this->seo()->setTitle('Create Project');

        return view('admin.causes.projects.create', compact('cause'));
    }
}
