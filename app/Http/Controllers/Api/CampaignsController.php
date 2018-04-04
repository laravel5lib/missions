<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Models\v1\Campaign;
use App\Jobs\ExportCampaigns;
use Dingo\Api\Contract\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\ImportRequest;
use App\Http\Requests\v1\ExportRequest;
use App\Http\Requests\v1\CampaignRequest;
use App\Services\Importers\CampaignListImport;
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
        $campaigns = Campaign::filter($request->all())
                          ->paginate($request->get('per_page', 25));

        return $this->response->paginator($campaigns, new CampaignTransformer);
    }

    /**
     * Show the specified campaign.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($param)
    {
        $campaign = Campaign::whereId($param)->orWhereHas('slug', function ($slug) use ($param) {
            return $slug->where('url', $param);
        })->first();

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

        if($request->has('page_url')) {
            $campaign->slug()->updateOrCreate([
                'url' => $request->get('page_url', ($campaign->slug ? $campaign->slug->url : null))
            ]);
            $campaign->load(['slug']);
        }

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

        $campaign->delete();

        return $this->response->noContent();
    }

    /**
     * Export Campaigns.
     *
     * @param ExportRequest $request
     * @return mixed
     */
    public function export(ExportRequest $request)
    {
        $this->dispatch(new ExportCampaigns($request->all()));

        return $this->response()->created(null, [
            'message' => 'Report is being generated and will be available shortly.'
        ]);
    }

    /**
     * Import a list of Campaigns.
     *
     * @param  CampaignListImport $import
     * @return response
     */
    public function import(ImportRequest $request, CampaignListImport $import)
    {
        $response = $import->handleImport();

        return $this->response()->created(null, $response);
    }
}
