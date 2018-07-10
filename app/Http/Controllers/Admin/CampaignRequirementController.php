<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\v1\Campaign;
use Illuminate\Http\Request;

class CampaignRequirementController extends Controller
{
    public function create($id, Request $request)
    {
        $campaign = Campaign::findOrFail($id);

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
}
