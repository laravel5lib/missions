<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\v1\CauseRequest;
use App\Http\Transformers\v1\CauseTransformer;
use App\Models\v1\Cause;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CausesController extends Controller
{

    /**
     * @var Cause
     */
    private $cause;

    /**
     * CausesController constructor.
     * @param Cause $cause
     */
    public function __construct(Cause $cause)
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

        return $this->response->paginator($causes, new CauseTransformer);
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

        return $this->response->item($cause, new CauseTransformer);
    }

    /**
     * Create a new cause.
     *
     * @param CauseRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(CauseRequest $request)
    {
        $cause = $this->cause->create($request->all());

        return $this->response->item($cause, new CauseTransformer);
    }

    /**
     * Update an existing cause by id.
     *
     * @param $id
     * @param CauseRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function update($id, CauseRequest $request)
    {
        $cause = $this->cause->findOrFail($id);

        $cause->update($request->all());

        return $this->response->item($cause, new CauseTransformer);
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
