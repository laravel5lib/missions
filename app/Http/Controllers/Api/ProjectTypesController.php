<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\v1\ProjectTypeRequest;
use App\Http\Transformers\v1\ProjectTypeTransformer;
use App\Models\v1\ProjectType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectTypesController extends Controller
{

    /**
     * @var ProjectType
     */
    private $type;

    /**
     * ProjectTypesController constructor.
     * @param ProjectType $type
     */
    public function __construct(ProjectType $type)
    {
        $this->type = $type;
    }

    /**
     * Get a list of project types.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $types = $this->type->paginate($request->get('per_page'));

        return $this->response->paginator($types, new ProjectTypeTransformer);
    }

    /**
     * Get a project type by it's id.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $type = $this->type->findOrFail($id);

        return $this->response->item($type, new ProjectTypeTransformer);
    }

    /**
     * Create a new project type.
     *
     * @param ProjectTypeRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(ProjectTypeRequest $request)
    {
        $type = $this->type->create($request->all());

        return $this->response->item($type, new ProjectTypeTransformer);
    }

    /**
     * Update an existing project type by it's id.
     *
     * @param $id
     * @param ProjectTypeRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function update($id, ProjectTypeRequest $request)
    {
        $type = $this->type->findOrFail($id);

        $type->update($request->all());

        return $this->response->item($type, new ProjectTypeTransformer);
    }

    /**
     * Delete a project type by it's id.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $type = $this->type->findOrFail($id);

        $type->delete();

        return $this->response->noContent();
    }
}
