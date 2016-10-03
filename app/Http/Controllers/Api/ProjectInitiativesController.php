<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\v1\ProjectInitiativeRequest;
use App\Http\Transformers\v1\ProjectInitiativeTransformer;
use App\Models\v1\Cause;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectInitiativesController extends Controller
{

    /**
     * @var Cause
     */
    private $cause;

    /**
     * ProjectInitiativesController constructor.
     * @param Cause $cause
     */
    public function __construct(Cause $cause)
    {
        $this->cause = $cause;
    }

    /**
     * Get the cause by it's id.
     *
     * @param $id
     * @return mixed
     */
    protected function cause($id)
    {
        return $this->cause->findOrFail($id);
    }

    /**
     * Get a list of project initiatives.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index($id, Request $request)
    {
        $initiatives = $this->cause($id)
                            ->initiatives()
                            ->paginate($request->get('per_page'));

        return $this->response->paginator($initiatives, new ProjectInitiativeTransformer);
    }

    /**
     * Get a project initiative by it's id.
     *
     * @param $cause_id
     * @param $initiative_id
     * @return \Dingo\Api\Http\Response
     */
    public function show($cause_id, $initiative_id)
    {
        $initiative = $this->cause($cause_id)
                           ->initiatives()
                           ->findOrFail($initiative_id);

        return $this->response->item($initiative, new ProjectInitiativeTransformer);
    }

    /**
     * Create a new project initiative.
     *
     * @param $id
     * @param ProjectInitiativeRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store($id, ProjectInitiativeRequest $request)
    {
        $initiative = $this->cause($id)
                           ->initiatives()
                           ->create($request->all());

        return $this->response->item($initiative, new ProjectInitiativeTransformer);
    }

    /**
     * Update a project initiative by it's id.
     *
     * @param $cause_id
     * @param $initiative_id
     * @param ProjectInitiativeRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function update($cause_id, $initiative_id, ProjectInitiativeRequest $request)
    {
        $initiative = $this->cause($cause_id)
                           ->initiatives()
                           ->findOrFail($initiative_id);

        $initiative->update($request->all());

        return $this->response->item($initiative, new ProjectInitiativeTransformer);
    }

    /**
     * Delete all initiatives or a specific one by it's id.
     *
     * @param $cause_id
     * @param null $initiative_id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($cause_id, $initiative_id = null)
    {
        $initiatives = $this->cause($cause_id)->initiatives();

        if ( ! is_null($initiative_id)) $initiatives->findOrFail($initiative_id);

        $initiatives->delete();

        return $this->response->noContent();
    }
}
