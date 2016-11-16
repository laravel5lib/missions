<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\v1\RequirementRequest;
use App\Http\Transformers\v1\RequirementTransformer;
use App\Models\v1\Requirement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RequirementsController extends Controller
{

    /**
     * @var Requirement
     */
    private $requirement;

    /**
     * RequirementsController constructor.
     * @param Requirement $requirement
     */
    public function __construct(Requirement $requirement)
    {
        $this->requirement = $requirement;
    }

    /**
     * Get all requirements.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $requirements = $this->requirement
                             ->filter($request->all())
                             ->paginate($request->get('per_page', 10));

        return $this->response->paginator($requirements, new RequirementTransformer);
    }

    /**
     * Get one requirement.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $requirement = $this->requirement->findOrFail($id);

        return $this->response->item($requirement, new RequirementTransformer);
    }

    /**
     * Create a new requirement.
     *
     * @param RequirementRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(RequirementRequest $request)
    {
        $requirement = $this->requirement->create([
            'requester_type' => $request->get('requester_type'),
            'requester_id' => $request->get('requester_id'),
            'name' => $request->get('name'),
            'document_type' => $request->get('document_type'),
            'short_desc' => $request->get('short_desc'),
            'due_at' => $request->get('due_at'),
            'grace_period' => $request->get('grace_period')
        ]);

        return $this->response->item($requirement, new RequirementTransformer);
    }

    /**
     * Update a Requirement
     *
     * @param RequirementRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(RequirementRequest $request, $id)
    {
        $requirement = $this->requirement->findOrFail($id);

        $requirement->update([
            'requester_type' => $request->get('requester_type', $requirement->requester_type),
            'requester_id' => $request->get('requester_id', $requirement->requester_id),
            'name' => $request->get('name'),
            'document_type' => $request->get('document_type'),
            'short_desc' => $request->get('short_desc'),
            'due_at' => $request->get('due_at'),
            'grace_period' => $request->get('grace_period')
        ]);

        return $this->response->item($requirement, new RequirementTransformer);
    }

    /**
     * Delete a requirement.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function delete($id)
    {
        $requirement = $this->requirement->findOrFail($id);

        $requirement->delete();

        return $this->response->noContent();
    }
}
