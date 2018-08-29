<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Models\v1\Campaign;
use Spatie\QueryBuilder\Filter;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Dingo\Api\Contract\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\CampaignResource;
use App\Http\Requests\v1\CampaignRequest;
use App\Http\Transformers\v1\CampaignTransformer;

class CampaignsController extends Controller
{
    /**
     * Show all campaigns.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $campaigns = QueryBuilder::for(Campaign::class)
            ->allowedFilters([
                'name',
                Filter::scope('active'),
                Filter::scope('inactive'),
                Filter::scope('organization')
            ])
            ->defaultSort('-started_at')
            ->allowedSorts('started_at', 'name')
            ->paginate($request->get('per_page', 25));

        return CampaignResource::collection($campaigns);
    }

    /**
     * Show the specified campaign.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($param)
    {
        $campaign = Campaign::byIdOrSlug($param)->firstOrFail();

        return $this->response->item($campaign, new CampaignTransformer);
    }

    /**
     * Store a campaign.
     *
     * @param CampaignRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(CampaignRequest $request)
    {
        $campaign = Campaign::create($request->all());

        return $this->response->item($campaign, new CampaignTransformer);
    }

    /**
     * Update the specified campaign.
     *
     * @param CampaignRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(CampaignRequest $request, $id)
    {
        $campaign = Campaign::findOrFail($id);

        $campaign->update($request->all());

        $campaign = $campaign->updateSlug($request->get('page_url'));

        return $this->response->item($campaign, new CampaignTransformer);
    }

    /**
     * Delete a campaign
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $campaign = Campaign::findOrFail($id);

        DB::transaction(function () use ($campaign) {
            $campaign->delete();
        });

        return $this->response->noContent();
    }
}
