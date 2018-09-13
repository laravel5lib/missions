<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\v1\TripTemplate;
use App\Http\Controllers\Controller;
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
        $templates = TripTemplate::whereCampaignId($campaignId)->paginate(25);

        return TripTemplateResource::collection($templates);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 
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

        return new TripTemplateResource($template);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TripTemplate  $tripTemplate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TripTemplate $tripTemplate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TripTemplate  $tripTemplate
     * @return \Illuminate\Http\Response
     */
    public function destroy(TripTemplate $tripTemplate)
    {
        //
    }
}
