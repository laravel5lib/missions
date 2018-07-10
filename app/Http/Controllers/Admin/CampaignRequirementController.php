<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\v1\Campaign;
use Illuminate\Http\Request;

class CampaignRequirementController extends Controller
{   
    /**
     * Show form to create a new requirement
     * 
     * @param  string  $campaignId 
     * @param  Illuminate\Http\Request $request    
     * @return Illuminate\Http\Response              
     */
    public function create($campaignId, Request $request)
    {
        $campaign = Campaign::findOrFail($campaignId);

        $docTypes = [
            'airport_preferences'     => 'Airport Preference',
            'arrival_designations'    => 'Arrival Designation',
            'influencer_applications' => 'Influencer Application',
            'media_credentials'       => 'Media Credentials',
            'medical_credentials'     => 'Medical Credentials',
            'medical_releases'        => 'Medical Release',
            'minor_releases'          => 'Minor Release',
            'passports'               => 'Passport',
            'referrals'               => 'Referral',
            'essays'                  => 'Testimony',
            'travel_itineraries'      => 'Travel Itinerary',
            'visas'                   => 'Visa',
        ];

        return view('admin.campaigns.tabs.requirements.create', compact('campaign', 'docTypes'));
    }

    /**
     * Show a page for a specific requirement.
     * 
     * @param  string $campaignId 
     * @param  string $id         
     * @return Illuminate\Http\Response             
     */
    public function show($campaignId, $id)
    {
        $campaign = Campaign::findOrFail($campaignId);

        $requirement = $campaign->requirements()->findOrFail($id);

        return view('admin.campaigns.tabs.requirements.show', compact('campaign', 'requirement'));
    }
}
