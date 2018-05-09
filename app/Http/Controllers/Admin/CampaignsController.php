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
    public function show($id, $tab = 'details', $tabId = null)
    {
        $campaign = $this->campaign->findOrFail($id);

        $this->authorize('view', $campaign);

        $this->seo()->setTitle(
            title_case(str_replace("-", " ", $tab)) . ' - ' . $campaign->name
        );

        $pageLinks = $this->getPageLinks($campaign);

        return view('admin.campaigns.tabs.'.$tab, compact('campaign', 'tab', 'pageLinks'));
    }

    private function getPageLinks($campaign)
    {
        $links = [
            'admin/campaigns/'.$campaign->id => 'Overview',
            'admin/campaigns/'.$campaign->id.'/costs' => 'Costs',
            'admin/campaigns/'.$campaign->id.'/groups' => 'Groups '.'<span class="badge badge-default">'.$campaign->groups()->count().'</span>',
        ];

        if (auth()->user()->can('view', \App\Models\v1\Team::class)) {
            $links['admin/campaigns/'.$campaign->id.'/squads'] = 'Squads';
        }

        if (auth()->user()->can('view', \App\Models\v1\TeamType::class)) {
            $links['admin/campaigns/'.$campaign->id.'/squad-types'] = 'Squad Types';
        }

        if (auth()->user()->can('view', \App\Models\v1\Region::class)) {
            $links['admin/campaigns/'.$campaign->id.'/regions'] = 'Squad Assignments';
        }

        if (auth()->user()->can('view', \App\Models\v1\Accommodation::class)) {
            $links['admin/campaigns/'.$campaign->id.'/region-accommodations'] = 'Accommodations';
        }

        return $links;
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
