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

    public function index($tab = null)
    {
        $this->authorize('view', $this->project);

        return view('admin.projects.index', compact('tab'));
    }
}
