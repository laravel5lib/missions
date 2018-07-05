<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Models\v1\Region;
use App\Models\v1\Campaign;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\RegionResource;
use App\Http\Requests\v1\RegionRequest;

class RegionController extends Controller
{
    /**
     * Return a paginated list of regions
     *
     * @return response
     */
    public function index()
    {
        $regions = QueryBuilder::for(Region::class)
            ->allowedFilters([
                'name',
                Filter::exact('campaign_id')
            ])
            ->allowedIncludes(['campaign'])
            ->withCount(['squads', 'members'])
            ->paginate(request()->input('per_page', 25));

        return RegionResource::collection($regions);
    }

    /**
     * Create a new region
     *
     * @param RegionRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(RegionRequest $request)
    {
        $campaign = Campaign::findOrFail($request->input('campaign_id'));
        
        Region::create([
            'name' => $request->input('name'),
            'campaign_id' => $campaign->id,
            'country_code' => $campaign->country_code
        ]);

        return response()->json(['message' => 'New region created.'], 201);
    }

    /**
     * Return a specific region by id
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $region = Region::findOrFail($id);

        return new RegionResource($region);
    }

    /**
     * Update a specific region by id
     *
     * @param RegionRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(RegionRequest $request, $id)
    {
        $region = Region::findOrFail($id);

        $region->update([
            'name' => $request->input('name', $region->name)
        ]);

        return new RegionResource($region);
    }

    /**
     * Delete a specific region by id
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $region = Region::findOrFail($id);

        $region->delete();

        return response()->json(['message' => 'Region deleted.'], 204);
    }
}
