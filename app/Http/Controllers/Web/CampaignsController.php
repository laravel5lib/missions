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
        $this->seo()->setTitle('Mission Trips');

        return view('site.campaigns.index');
    }

    public function show($id)
    {
        try {
            $campaign = $this->api->get('campaigns/' . $id);
        } catch (Dingo\Api\Exception\InternalHttpException $e) {
            $response = $e->getResponse();

            return $response;
        }

        $this->seo()->setTitle($campaign->name);

        return view('site.campaigns.show', compact('campaign'));
    }

    public function teams($slug)
    {
        $campaign = Campaign::whereHas('slug', function ($query) use ($slug) {
            return $query->where('url', $slug);
        })->firstOrFail();

         $this->seo()->setTitle($campaign->name . ' Teams');

        return view('site.campaigns.teams.index', compact('campaign'));
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
