<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\v1\ExportRequest;
use App\Http\Requests\v1\ProjectRequest;
use App\Http\Transformers\v1\ProjectTransformer;
use App\Jobs\ExportProjects;
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
        $projects = $this->project
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
        $project = $this->project->create([
            'name' => $request->get('name'),
            'project_initiative_id' => $request->get('project_initiative_id'),
            'sponsor_id' => $request->get('sponsor_id'),
            'sponsor_type' => $request->get('sponsor_type'),
            'plaque_prefix' => $request->get('plaque_prefix'),
            'plaque_message' => $request->get('plaque_message')
        ]);

        return $this->response->item($project, new ProjectTransformer);
    }

    /**
     * Update a project.
     *
     * @param $id
     * @param ProjectRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function update($id, ProjectRequest $request)
    {
        $project = $this->project->findOrFail($id);

        $project->update([
            'name' => $request->get('name', $project->name),
            'project_initiative_id' => $request->get('project_initiative_id', $project->project_initiative_id),
            'sponsor_id' => $request->get('sponsor_id', $project->sponsor_id),
            'sponsor_type' => $request->get('sponsor_type', $project->sponsor_type),
            'plaque_prefix' => $request->get('plaque_prefix', $project->plaque_prefix),
            'plaque_message' => $request->get('plaque_message', $project->plaque_message),
        ]);

        return $this->response->item($project, new ProjectTransformer);
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

    /**
     * Export referrals.
     *
     * @param ExportRequest $request
     * @return mixed
     */
    public function export(ExportRequest $request)
    {
        $this->dispatch(new ExportProjects($request->all()));

        return $this->response()->created(null, [
            'message' => 'Report is being generated and will be available shortly.'
        ]);
    }

}
