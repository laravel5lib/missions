<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests;
use App\Models\v1\Group;
use App\Models\v1\Campaign;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;

class CampaignsController extends Controller
{
    use SEOTools;

    public function index()
    {
        $campaigns = Campaign::public()->active()->get();

        $this->seo()->setTitle('Mission Trips');

        return view('site.campaigns.index', compact('campaigns'));
    }

    public function show($id)
    {
        $campaign = Campaign::findOrFail($id);

        $this->seo()->setTitle($campaign->name);

        $defaultGroup = Group::whereName('Missions. Me')->firstOrFail();

        return view('site.campaigns.show', compact('campaign', 'defaultGroup'));
    }

    public function teams($slug)
    {
        $campaign = Campaign::whereHas('slug', function ($query) use ($slug) {
            return $query->where('url', $slug);
        })->firstOrFail();

        $this->seo()->setTitle($campaign->name . ' Teams');

        $defaultGroup = Group::whereName('Missions. Me')->firstOrFail();

        return view('site.campaigns.teams.index', compact('campaign', 'defaultGroup'));
    }

    public function trips($slug, $teamId)
    {
        $campaign = Campaign::whereHas('slug', function ($query) use ($slug) {
            return $query->where('url', $slug);
        })->firstOrFail();

        $group = Group::findOrFail($teamId);

        $this->seo()->setTitle($campaign->name . ' Trip Options');

        return view('site.campaigns.trips.index', compact('campaign', 'group'));
    }
}
