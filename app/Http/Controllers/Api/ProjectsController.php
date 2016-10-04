<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\v1\ProjectRequest;
use App\Http\Transformers\v1\ProjectTransformer;
use App\Models\v1\Project;
use Illuminate\Http\Request;
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

    /**
     * Get all projects.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $projects = $this->project->paginate($request->get('per_page', 10));

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
