<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RequirementResource;
use App\Models\v1\Requirement;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;

class RequirementsController extends Controller
{
    /**
     * Get all requirements.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $requirements = QueryBuilder::for(Requirement::class)
            ->allowedFilters([
                Filter::scope('campaign_id')
            ])
            ->paginate($request->input('per_page', 25));

        return RequirementResource::collection($requirements);
    }

    /**
     * Get one requirement.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        // $requirement = $this->requirement->findOrFail($id);

        // return $this->response->item($requirement, new RequirementTransformer);
    }

    /**
     * Create a new requirement.
     *
     * @param RequirementRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(RequirementRequest $request)
    {
        // $requirement = $this->requirement->create([
        //     'requester_type' => $request->get('requester_type'),
        //     'requester_id' => $request->get('requester_id'),
        //     'name' => $request->get('name'),
        //     'document_type' => $request->get('document_type'),
        //     'short_desc' => $request->get('short_desc', null),
        //     'due_at' => $request->get('due_at'),
        //     'grace_period' => $request->get('grace_period', 0)
        // ]);

        // $this->dispatch(new UpdateReservationRequirements($requirement));

        // return $this->response->item($requirement, new RequirementTransformer);
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
        // $requirement = $this->requirement->findOrFail($id);

        // $requirement->update([
        //     'requester_type' => $request->get('requester_type', $requirement->requester_type),
        //     'requester_id' => $request->get('requester_id', $requirement->requester_id),
        //     'name' => $request->get('name'),
        //     'document_type' => $request->get('document_type'),
        //     'short_desc' => $request->get('short_desc', $requirement->short_desc),
        //     'due_at' => $request->get('due_at'),
        //     'grace_period' => $request->get('grace_period', $requirement->grace_period)
        // ]);

        // $this->dispatch(new UpdateReservationRequirements($requirement));

        // return $this->response->item($requirement, new RequirementTransformer);
    }

    /**
     * Delete a requirement.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        // $requirement = $this->requirement->findOrFail($id);

        // $requirement->delete();

        // return $this->response->noContent();
    }
}
