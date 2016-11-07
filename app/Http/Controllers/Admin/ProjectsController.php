<?php

namespace App\Http\Controllers\admin;

use App\Models\v1\Project;
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
     * ProjectsController constructor.
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function show($id)
    {
        $this->authorize('manage-projects');

        $project = $this->project->findOrFail($id);

        return view('admin.causes.projects.show', compact('project'));
    }
}
