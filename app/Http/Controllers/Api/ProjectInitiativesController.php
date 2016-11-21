<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\v1\ProjectInitiativeRequest;
use App\Http\Transformers\v1\ProjectInitiativeTransformer;
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
        $type = $this->initiative->findOrFail($id);

        return $this->response->item($type, new ProjectInitiativeTransformer);
    }

    /**
     * Create a new project type.
     *
     * @param ProjectInitiativeRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(ProjectInitiativeRequest $request)
    {
        $type = $this->initiative->create($request->all());

        return $this->response->item($type, new ProjectInitiativeTransformer);
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
        $type = $this->initiative->findOrFail($id);

        $type->update($request->all());

        return $this->response->item($type, new ProjectInitiativeTransformer);
    }

    /**
     * Delete a project type by it's id.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $type = $this->initiative->findOrFail($id);

        $type->delete();

        return $this->response->noContent();
    }
}
