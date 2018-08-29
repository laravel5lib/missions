<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\Trip;
use App\Models\v1\Campaign;
use Illuminate\Http\Request;
use App\Models\v1\Requirement;
use App\Models\v1\Reservation;
use Spatie\QueryBuilder\Filter;
use App\Models\v1\CampaignGroup;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\RequirementResource;
use App\Http\Requests\UpdateRequirementRequest;
use App\Http\Requests\v1\CreateRequirementRequest;

class RequirementsController extends Controller
{
    /**
     * Get all requirements.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index($requireableType, $requireableId)
    {
        $requireable = $this->requireable($requireableType, $requireableId);
        
        $requirements = $requireable->requireables()
            ->withCount([
                'groups' => function ($query) use ($requireable) {
                    $query->where('campaign_id', $requireable->id);
                }, 
                'trips' => function ($query) use ($requireable, $requireableType) {
                    $query->when($requireableType == 'campaigns', function ($query) use ($requireable) {
                        $query->where('campaign_id', $requireable->id);
                    })
                    ->when($requireableType == 'campaign-groups', function ($query) use ($requireable) {
                        $query->where('campaign_id', $requireable->campaign_id)
                              ->where('group_id', $requireable->group_id);
                    });
                }, 
                'reservations' => function ($query) use ($requireable, $requireableType) {
                    $query->when($requireableType == 'campaign-groups', function($query) use ($requireable) {
                        $query->whereHas('trip', function ($trip) use ($requireable) {
                            return $trip->where('group_id', $requireable->group_id)
                                        ->where('campaign_id', $requireable->campaign_id);
                        });
                    })
                    ->when($requireableType == 'trips', function($query) use ($requireable) {
                        $query->where('trip_id', $requireable->id);
                    });
                }
            ])
            ->paginate(request()->input('per_page', 25));

        return RequirementResource::collection($requirements);
    }

    /**
     * Get one requirement.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($requireableType, $requireableId, $id)
    {
        $requirement = $this->requireable($requireableType, $requireableId)->requireables()->findOrFail($id);

        return new RequirementResource($requirement);
    }

    /**
     * Create a new requirement.
     *
     * @param CreateRequirementRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store($requireableType, $requireableId, CreateRequirementRequest $request)
    {
        $this->requireable($requireableType, $requireableId)->addRequirement($request->all());

        return response()->json(['message' => 'Requirement created.'], 201);
    }

    /**
     * Update a Requirement
     *
     * @param RequirementRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(UpdateRequirementRequest $request, $requireableType, $requireableId, $id)
    {
        $requirement = $this->requireable($requireableType, $requireableId)
                            ->updateRequirement($id, $request->all());

        return new RequirementResource($requirement);
    }

    /**
     * Delete a requirement.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($requireableType, $requireableId, $id)
    {
        $requireable = $this->requireable($requireableType, $requireableId);
        $requirement = $requireable->requireables()->findOrFail($id);

        $requireable->requireables()->detach($id);

        if ($requirement->requester_id === $requireableId && $requirement->requester_type === $requireableType) {
            $requirement->delete();
        }

        return response()->json([], 204);
    }

    /**
     * Get the requireable model for the requirement.
     * 
     * @param  string $type
     * @param  string $id
     * @return Illuminate\Database\Eloquent\Collection
     */
    private function requireable($type, $id)
    {
        $requireables = [
            'campaigns' => Campaign::class,
            'campaign-groups' => CampaignGroup::class,
            'trips' => Trip::class,
            'reservations' => Reservation::class
        ];

        if ($type == 'campaign-groups') {
            return $requireables[$type]::whereUuid($id)->firstOrFail();
        }

        return $requireables[$type]::findOrFail($id);
    }
}
