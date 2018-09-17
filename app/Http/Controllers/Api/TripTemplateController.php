<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\Campaign;
use Illuminate\Http\Request;
use App\Models\v1\TripTemplate;
use App\Http\Controllers\Controller;
use App\Http\Requests\TripTemplateRequest;
use App\Http\Resources\TripTemplateResource;

class TripTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($campaignId)
    {
        $this->authorize('view', TripTemplate::class);

        $templates = TripTemplate::whereCampaignId($campaignId)->with('tags')->paginate(25);

        return TripTemplateResource::collection($templates);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($campaignId, TripTemplateRequest $request)
    {
        $template = Campaign::findOrFail($campaignId)->tripTemplates()->create($request->all());

        if ($request->input('tags')) {
            $template->syncTagsWithType($request->input('tags'), 'trip');
        }

        return response()->json(['message' => 'Trip template created'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TripTemplate  $tripTemplate
     * @return \Illuminate\Http\Response
     */
    public function show($campaignId, $id)
    {
        $template = TripTemplate::whereCampaignId($campaignId)->findOrFail($id);

        $this->authorize('view', $template);

        return new TripTemplateResource($template);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TripTemplate  $tripTemplate
     * @return \Illuminate\Http\Response
     */
    public function update(TripTemplateRequest $request, $campaignId, $templateId)
    {
        $template = Campaign::findOrFail($campaignId)
            ->tripTemplates()
            ->findOrFail($templateId)
            ->update($request->all());

        if ($request->input('tags')) {
            $template->syncTagsWithType($request->input('tags'), 'trip');
        }

        return new TripTemplateResource($template);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TripTemplate  $tripTemplate
     * @return \Illuminate\Http\Response
     */
    public function destroy($campaignId, $templateId)
    {
        $template = Campaign::findOrFail($campaignId)
            ->tripTemplates()
            ->findOrFail($templateId);

        $template->delete();

        return response()->json([], 204);
    }
}
