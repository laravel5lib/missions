<?php

namespace App\Http\Controllers\Api;

use App\Trip;
use Illuminate\Http\Request;
use App\Jobs\ApplyGroupPricing;
use App\Models\v1\TripTemplate;
use App\Http\Controllers\Controller;
use App\Jobs\ApplyGroupRequirements;

class TripTemplateTripController extends Controller
{
    /**
     * Create a new trip from a template.
     * 
     * @param  Request $request 
     * @return response
     */
    public function store(Request $request)
    {
        $template = TripTemplate::findOrFail($request->input('template_id'));

        $trip = Trip::create([
            'group_id' => $request->input('group_id'),
            'campaign_id' => $request->input('campaign_id'),
            'type' => $template->type,
            'country_code' => $template->country_code,
            'spots' => $template->spots,
            'companion_limit' => $template->companion_limit,
            'difficulty' => $template->difficulty,
            'started_at' => $template->started_at->toDateTimeString(),
            'ended_at' => $template->ended_at->toDateTimeString(),
            'todos' => $template->todos,
            'team_roles' => $template->team_roles,
            'prospects' => $template->prospects,
            'description' => $template->description,
            'closed_at' => $template->closed_at
        ]);

        if ($request->input('default_prices')) {
            ApplyGroupPricing::dispatch($trip);
        }

        if ($request->input('default_requirements')) {
            ApplyGroupRequirements::dispatch($trip);
        }

        if ($template->tags) {
            $trip->syncTagsWithType($template->tags->pluck('name')->toArray(), 'trip');
        }

        return response()->json(['message' => 'New trip added.'], 201);
    }
}
