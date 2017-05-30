<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Models\v1\Region;
use App\Models\v1\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\RegionRequest;
use App\Http\Transformers\v1\RegionTransformer;

class RegionsController extends Controller
{

    /**
     * @var Region
     */
    private $region;

    /**
     * RegionsController constructor.
     * @param Region $region
     */
    public function __construct(Region $region)
    {
        $this->region = $region;
    }

    /**
     * Return a paginated list of regions
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index($campaignId, Request $request)
    {
        $regions = $this->region
            ->whereCampaignId($campaignId)
            ->filter($request->all())
            ->paginate($request->get('per_page', 10));

        return $this->response->paginator($regions, new RegionTransformer);
    }

    /**
     * Create a new region
     *
     * @param RegionRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store($campaignId, RegionRequest $request, Campaign $campaign)
    {
        $campaign = $campaign->findOrFail($campaignId);

        $region = $campaign->regions()->create([
            'name' => $request->get('name'),
            'callsign' => $request->get('callsign'),
            'country_code' => $request->get('country_code', $campaign->country_code)
        ]);

        return $this->response->item($region, new RegionTransformer);
    }

    /**
     * Return a specific region by id
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($campaignId, $id)
    {
        $region = $this->region
             ->whereCampaignId($campaignId)
             ->withTrashed()
             ->findOrFail($id);

        return $this->response->item($region, new RegionTransformer);
    }

    /**
     * Update a specific region by id
     *
     * @param RegionRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update($campaignId, $id, RegionRequest $request)
    {
        $region = $this->region
            ->whereCampaignId($campaignId)
            ->findOrFail($id);

        $region->update([
            'name' => $request->get('name', $region->name),
            'callsign' => $request->get('callsign', $region->callsign),
            'country_code' => $request->get('country_code', $region->country_code)
        ]);

        return $this->response->item($region, new RegionTransformer);
    }

    /**
     * Delete a specific region by id
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($campaignId, $id)
    {
        $region = $this->region
             ->whereCampaignId($campaignId)
             ->findOrFail($id);

        DB::transaction(function() use($region) {
            $region->teams()->detach();
            $region->delete();
        });

        return $this->response->noContent();
    }
}
