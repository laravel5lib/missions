<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\v1\ExportRequest;
use App\Http\Requests\v1\ProjectInitiativeRequest;
use App\Http\Transformers\v1\ProjectInitiativeTransformer;
use App\Jobs\ExportInitiatives;
use App\Models\v1\ProjectCause;
use App\Models\v1\ProjectInitiative;
use Illuminate\Http\Request;
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

    /**
     * Get a list of project types.
     *
     * @param $id
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index($id, Request $request)
    {
        $types = $this->cause
                      ->findOrFail($id)
                      ->initiatives()
                      ->filter($request->all())
                      ->withCount('projects')
                      ->paginate($request->get('per_page'));

        return $this->response->paginator($types, new ProjectInitiativeTransformer);
    }

    /**
     * Get a project type by it's id.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $initiative = $this->initiative->findOrFail($id);

        return $this->response->item($initiative, new ProjectInitiativeTransformer);
    }

    /**
     * Create a new project type.
     *
     * @param ProjectInitiativeRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(ProjectInitiativeRequest $request)
    {
        $initiative = $this->initiative->create([
            'type' => $request->get('type'),
            'short_desc' => $request->get('short_desc'),
            'country_code' => $request->get('country_code'),
            'upload_id' => $request->get('upload_id', null),
            'started_at' => $request->get('started_at'),
            'ended_at' => $request->get('ended_at'),
            'project_cause_id' => $request->get('project_cause_id')
        ]);

        return $this->response->item($initiative, new ProjectInitiativeTransformer);
    }

    /**
     * Update an existing project type by it's id.
     *
     * @param $id
     * @param ProjectInitiativeRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function update($id, ProjectInitiativeRequest $request)
    {
        $initiative = $this->initiative->findOrFail($id);

        $initiative->update([
            'type' => $request->get('type'),
            'short_desc' => $request->get('short_desc'),
            'country_code' => $request->get('country_code'),
            'upload_id' => $request->get('upload_id', $initiative->upload_id),
            'started_at' => $request->get('started_at'),
            'ended_at' => $request->get('ended_at'),
            'project_cause_id' => $request->get('project_cause_id', $initiative->project_cause_id)
        ]);

        return $this->response->item($initiative, new ProjectInitiativeTransformer);
    }

    /**
     * Delete a project type by it's id.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $initiative = $this->initiative->findOrFail($id);

        $initiative->delete();

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
        $this->dispatch(new ExportInitiatives($request->all()));

        return $this->response()->created(null, [
            'message' => 'Report is being generated and will be available shortly.'
        ]);
    }

}
