<?php

namespace App\Http\Controllers\admin;

use App\Models\v1\Campaign;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;

class CampaignsController extends Controller
{
    use SEOTools;

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

        $this->seo()->setTitle('Campaigns');

        return view('admin.campaigns.index');
    }

    /**
     * Show the specified campaign.
     *
     * @param $id
     * @return $this
     */
    public function show($id, $tab = 'trips', $tabId = null)
    {
        $campaign = $this->campaign->findOrFail($id);

        $this->authorize('view', $campaign);

        if (!is_null($tabId)) {
            return view('admin.campaigns.tabs.'.$tab.'.details', compact('campaign', 'tab', 'tabId'));
        }

        $title = title_case(str_replace("-", " ", $tab)) . ' - ' . $campaign->name;
        $this->seo()->setTitle($title);

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
        $campaign = $this->campaign->findOrFail($id);

        $this->authorize('update', $campaign);

        $this->seo()->setTitle('Edit Campaign');

        return view('admin.campaigns.edit')->with(compact('campaign'));
    }

    /**
     * Create a new campaign.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('create', $this->campaign);

        $this->seo()->setTitle('Create Campaign');

        return view('admin.campaigns.create');
    }
}
