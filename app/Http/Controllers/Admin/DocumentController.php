<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Visa;
use App\Models\v1\Essay;
use App\Models\v1\Passport;
use Illuminate\Http\Request;
use App\Models\v1\MinorRelease;
use App\Models\v1\MedicalRelease;
use App\Models\v1\MediaCredential;
use App\Http\Controllers\Controller;
use App\Models\v1\AirportPreference;
use App\Models\v1\MedicalCredential;
use App\Models\v1\ArrivalDesignation;
use Artesaos\SEOTools\Facades\SEOMeta;
use App\Models\v1\InfluencerApplication;

class DocumentController extends Controller
{
    public function index($tab = 'passports')
    {
        $title = title_case(str_replace("-", " ", $tab));
        
        SEOMeta::setTitle($title);

        return view('admin.records.index', compact('tab'));
    }

    public function show($tab, $id)
    {
        $this->authorize('view', $this->document($tab, $id));

        SEOMeta::setTitle(
            title_case(str_replace("-", " ", $tab))
        );

        return view('admin.records.show', compact('tab', 'id'));
    }

    private function document($type, $id)
    {
        $documents = [
            'passports'               => Passport::class,
            'visas'                   => Visa::class,
            'medical-releases'        => MedicalRelease::class,
            'essays'                  => Essay::class,
            'influencer-applications' => InfluencerApplication::class,
            'media-credentials'       => MediaCredential::class,
            'medical-credentials'     => MedicalCredential::class,
            'airport-preferences'     => AirportPreference::class,
            'arrival-designations'    => ArrivalDesignation::class,
            'minor-release'           => MinorRelease::class
        ];

        return $documents[$type]::findOrFail($id);
    }
}
