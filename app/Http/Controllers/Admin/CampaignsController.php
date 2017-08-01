<?php

namespace App\Http\Controllers\admin;

use App\Models\v1\Campaign;
use App\Http\Controllers\Controller;

class CampaignsController extends Controller
{

    /**
     * @var Campaign
     */
    private $campaign;

    /**
     * CampaignsController constructor.
     * @param Campaign $campaign
     */
    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * Get list of campaigns.
     *
     * @return $this
     */
    public function index()
    {
        $this->authorize('view', $this->campaign);

        $campaigns = $this->api->get('campaigns');

        return view('admin.campaigns.index')->with('campaigns', $campaigns);
    }

    /**
     * Show the specified campaign.
     *
     * @param $id
     * @return $this
     */
    public function show($id, $tab = 'trips', $tabId = null)
    {
        $this->authorize('view', $this->campaign);

        $campaign = $this->api->get('campaigns/'.$id);

        if (!is_null($tabId)) {
            return view('admin.campaigns.tabs.'.$tab.'.details', compact('campaign', 'tab', 'tabId'));
        }

        return view('admin.campaigns.tabs.'.$tab, compact('campaign', 'tab'));
    }

    /**
     * Edit the specified campaign.
     *
     * @param $id
     * @return $this
     */
    public function edit($id)
    {
        $this->authorize('edit', $this->campaign);

        return view('admin.campaigns.edit')->with('campaignId', $id);
    }

    /**
     * Create a new campaign.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('create', $this->campaign);

        return view('admin.campaigns.create');
    }
}
