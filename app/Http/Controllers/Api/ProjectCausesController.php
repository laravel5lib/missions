<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\v1\ProjectCauseRequest;
use App\Http\Transformers\v1\ProjectCauseTransformer;
use App\Models\v1\ProjectCause;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectCausesController extends Controller
{

    /**
     * @var Cause
     */
    private $cause;

    /**
     * CausesController constructor.
     * @param ProjectCause $cause
     */
    public function __construct(ProjectCause $cause)
    {
        $this->cause = $cause;
    }

    /**
     * Get a list of causes.
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $causes = $this->cause->paginate($request->get('per_page', 10));

        return $this->response->paginator($causes, new ProjectCauseTransformer);
    }

    /**
     * Get a single cause by id.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $cause = $this->cause->findOrFail($id);

        return $this->response->item($cause, new ProjectCauseTransformer);
    }

    /**
     * Create a new cause.
     *
     * @param ProjectCauseRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(ProjectCauseRequest $request)
    {
        $cause = $this->cause->create($request->all());

        return $this->response->item($cause, new ProjectCauseTransformer);
    }

    /**
     * Update an existing cause by id.
     *
     * @param $id
     * @param ProjectCauseRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function update($id, ProjectCauseRequest $request)
    {
        $cause = $this->cause->findOrFail($id);

        $cause->update($request->all());

        return $this->response->item($cause, new ProjectCauseTransformer);
    }

    /**
     * Destroy an existing cause by id.
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $cause = $this->cause->findOrFail($id);

        $cause->delete();

        return $this->response->noContent();
    }
}
