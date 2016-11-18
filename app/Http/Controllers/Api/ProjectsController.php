<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\v1\ProjectRequest;
use App\Http\Transformers\v1\ProjectTransformer;
use App\Models\v1\Project;
use App\Models\v1\ProjectCause;
use Illuminate\Http\Request;
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
     * Get all projects.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index($causeId, Request $request)
    {
        $projects = $this->cause
                         ->findOrFail($causeId)
                         ->projects()
                         ->filter($request->all())
                         ->paginate($request->get('per_page', 10));

        return $this->response->paginator($projects, new ProjectTransformer);
    }

    /**
     * Get a project by it's id.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $project = $this->project->findOrFail($id);

        return $this->response->item($project, new ProjectTransformer);
    }

    /**
     * Create a new project.
     *
     * @param ProjectRequest $request
     * @return \Dingo\Api\Http\Response\Factory
     */
    public function store(ProjectRequest $request)
    {
        $project = $this->project->create($request->all());

        if ($request->has('costs')) {
            $project->syncCosts($request->get('costs'));
        }

        return $this->response($project, new ProjectTransformer);
    }

    /**
     * Update a project.
     *
     * @param $id
     * @param ProjectRequest $request
     * @return \Dingo\Api\Http\Response\Factory
     */
    public function update($id, ProjectRequest $request)
    {
        $project = $this->project->findOrFail($id);

        $project->update($request->all());

        if ($request->has('costs')) {
            $project->syncCosts($request->get('costs'));
        }

        return $this->response($project, new ProjectTransformer);
    }

    /**
     * Delete a project.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $project = $this->project->findOrFail($id);

        $project->delete($id);

        return $this->response->noContent();
    }
}
