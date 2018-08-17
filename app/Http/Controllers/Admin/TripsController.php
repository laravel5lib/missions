<?php

namespace App\Http\Controllers\admin;

use App\Models\v1\Cost;
use App\Models\v1\Trip;
use App\Models\v1\CampaignGroup;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;

class TripsController extends Controller
{
    use SEOTools;

    public function index()
    {
        $this->authorize('view', Trip::class);

        $trips = $this->api->get('trips');

        $this->seo()->setTitle('Trips');

        return view('admin.trips.index')->with('trips', $trips);
    }

    public function show($id, $tab = 'details')
    {
        if ($tab === 'edit') {
            return $this->edit($id);
        }

        $trip = Trip::findOrFail($id);
        $group = CampaignGroup::whereCampaignId($trip->campaign_id)
            ->whereGroupId($trip->group_id)
            ->firstOrFail();

        $this->authorize('view', $trip);

        $this->seo()->setTitle(title_case($trip->group->name.'\'s ' . $trip->type . ' Trip - ' . $tab));

        $pageLinks = $this->getPageLinks($trip);

        return view('admin.trips.show', compact('trip', 'tab', 'pageLinks', 'group'));
    }

    private function getPageLinks($trip)
    {
        $links = [
            'admin/trips/'.$trip->id => 'Overview'
        ];
        
        if (auth()->user()->can('view', \App\Models\v1\Cost::class)) {
            $links['admin/trips/'.$trip->id.'/prices'] = 'Pricing';
        }

        $links['admin/trips/'.$trip->id.'/description'] = 'Public Page';

        if (auth()->user()->can('view', \App\Models\v1\Requirement::class)) {
            $links['admin/trips/'.$trip->id.'/requirements'] = 'Requirements';
        }

        if (auth()->user()->can('view', \App\Models\v1\Reservation::class)) {
            $links['admin/trips/'.$trip->id.'/reservations'] = 'Reservations <span class="badge">'.$trip->reservations->count().'</span>';
        }

        return $links;
    }

    public function edit($tripId)
    {
        $trip = Trip::findOrFail($tripId);
        $group = CampaignGroup::whereCampaignId($trip->campaign_id)
            ->whereGroupId($trip->group_id)
            ->firstOrFail();

        $this->authorize('update', $trip);

        $this->seo()->setTitle('Edit Trip');

        $formData = json_encode([
            'type' => $trip->type,
            'difficulty' => $trip->difficulty,
            'started_at' => $trip->started_at->toDateString(),
            'ended_at' => $trip->ended_at->toDateString(),
            'rep_id' => $trip->rep_id,
            'team_roles' => $trip->team_roles,
            'prospects' => $trip->prospects,
            'published_at' => optional($trip->published_at)->toDateTimeString(),
            'spots' => $trip->spots,
            'companion_limit' => $trip->companion_limit,
            'closed_at' => optional($trip->closed_at)->toDateTimeString(),
            'tags' => $trip->tags->toArray()
        ]);

        return view('admin.trips.edit', compact('tripId', 'trip', 'group', 'formData'));
    }

    public function create($campaignId)
    {
        $this->authorize('create', Trip::class);

        $campaign = $this->api->get('campaigns/'.$campaignId);

        $this->seo()->setTitle('Create Trip');

        return view('admin.trips.create')->with('campaign', $campaign);
    }
}
