<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRequirementRequest;
use App\Http\Requests\v1\CreateRequirementRequest;
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
        $requirement = Requirement::findOrFail($id);

        return new RequirementResource($requirement);
    }

    /**
     * Create a new requirement.
     *
     * @param CreateRequirementRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(CreateRequirementRequest $request)
    {
        Requirement::create($request->all());

        return response()->json(['message' => 'Requirement created.'], 201);
    }

    /**
     * Update a Requirement
     *
     * @param RequirementRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(UpdateRequirementRequest $request, $id)
    {
        $requirement = Requirement::findOrFail($id);

        $requirement->update($request->all());

        return new RequirementResource($requirement);
    }

    /**
     * Delete a requirement.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $requirement = Requirement::findOrFail($id);

        $requirement->delete();

        return response()->json([], 204);
    }
}
