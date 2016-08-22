<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\v1\Campaign;
use Dingo\Api\Contract\Http\Request;
use App\Http\Requests\v1\CampaignRequest;
use App\Http\Transformers\v1\CampaignTransformer;

class CampaignsController extends Controller
{

    /**
     * @var Campaign
     */
    private $campaign;

    /**
     * Instantiate a new CampaignsController instance.
     * @param Campaign $campaign
     */
    public function __construct(Campaign $campaign)
    {
        $this->middleware('internal', ['only' => ['store', 'update', 'destroy']]);
        $this->middleware('api.auth', ['only' => ['store', 'update', 'destroy']]);
//        $this->middleware('jwt.refresh', ['only' => ['store', 'update', 'destroy']]);
        $this->campaign = $campaign;
    }

    /**
     * Show all campaigns.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $campaigns = $this->campaign
                          ->filter($request->all())
                          ->paginate($request->get('per_page', 25));

        return $this->response->paginator($campaigns, new CampaignTransformer);
    }

    /**
     * Show the specified campaign.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $campaign = $this->campaign->whereId($id)->orWhere('page_url', $id)->firstOrFail();

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
        $campaign = $this->campaign->findOrFail($id);

        $campaign->update($request->all());

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
        $campaign = $this->campaign->findOrFail($id);

        $campaign->delete();

        return $this->response->noContent();
    }
}
