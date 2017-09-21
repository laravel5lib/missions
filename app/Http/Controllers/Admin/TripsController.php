<?php

namespace App\Http\Controllers\admin;

use App\Models\v1\Cost;
use App\Models\v1\Trip;
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

        $trip = $this->api->get('trips/'.$id, ['include' => 'campaign,costs.payments,requirements,notes,deadlines']);

        $this->authorize('view', $trip);

        $this->seo()->setTitle(title_case($trip->group->name.'\'s ' . $trip->type . ' Trip - ' . $tab));

        return view('admin.trips.show', compact('trip', 'tab'));
    }

    public function edit($tripId)
    {
        $trip = $this->api->get('trips/'.$tripId, ['include' => 'campaign']);

        $this->authorize('update', $trip);

        $this->seo()->setTitle('Edit Trip');

        return view('admin.trips.edit', compact('tripId', 'trip'));
    }

    public function create($campaignId)
    {
        $this->authorize('create', Trip::class);

        $campaign = $this->api->get('campaigns/'.$campaignId);

        $this->seo()->setTitle('Create Trip');

        return view('admin.trips.create')->with('campaign', $campaign);
    }
}
