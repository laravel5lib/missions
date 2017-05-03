<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Utilities\v1\Airline;
use App\Utilities\v1\Country;
use App\Utilities\v1\TeamRole;
use App\Models\v1\ProjectCause;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\v1\ProjectInitiative;
use Dingo\Api\Contract\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class UtilitiesController extends Controller
{   
    public function getTeamRoles($type = null)
    {
        switch ($type) {
            case 'general':
                $roles = TeamRole::general();
                break;
            case 'medical':
                $roles = TeamRole::medical();
                break;
            case 'leadership':
                $roles = TeamRole::leadership();
                break;
            default:
                $roles = TeamRole::all();
        }

        return response()->json(compact('roles'));
    }

    public function getCountries()
    {
        $countryList = Country::all();

        $countries = [];
        foreach ($countryList as $key => $country) {
            $countries[] = ['name' => $key, 'code' => $country];
        }

        return response()->json(compact('countries'));
    }

    public function getCountry($code)
    {
        $country = (array)Country::get($code);
        return response()->json(compact('country'));
    }

    public function getAirlines()
    {
        $airlineList = Airline::all();

        $airlines = [];
        foreach ($airlineList as $key => $airline) {
            $airlines[] = ['name' => $airline, 'iata' => $key];
        }

        return response()->json(compact('airlines'));
    }

    public function getAirline($iata)
    {
        $airline = (array)Airline::get($iata);
        return response()->json(compact('airline'));
    }

    public function getTimezones($country_code = null)
    {
        $timezones = \DateTimeZone::listIdentifiers();
        
        if ($country_code)
            $timezones = \DateTimeZone::listIdentifiers(\
                DateTimeZone::PER_COUNTRY, strtoupper($country_code)
            );

        return response()->json(compact('timezones'));
    }

    public function  sendContactEmail(Request $request)
    {
        $data = [
            'email' => $request->get('email'),
            'name' => $request->get('name'),
            'organization' => $request->get('organization'),
            'phone_one' => $request->get('phone_one'),
            'comments' => $request->get('comments'),
        ];

        Mail::queue('emails.contact', ['data' => $data], function ($m) use ($data) {
            $m->to('go@missions.me', 'Missions.me')->subject('Contact from Missions.Me Visitor!');
        });

    }

    public function  sendSpeakerRequestEmail(Request $request)
    {
        $data = [
            'email' => $request->get('email'),
            'name' => $request->get('name'),
            'organization' => $request->get('organization'),
            'phone_one' => $request->get('phone_one'),
            'address_one' => $request->get('address_one'),
            'address_two' => $request->get('address_two'),
            'city' => $request->get('city'),
            'state' => $request->get('state'),
            'zip' => $request->get('zip'),
            'comments' => $request->get('comments'),
        ];

        Mail::queue('emails.speaker', ['data' => $data], function ($m) use ($data) {
            $m->to('go@missions.me', 'Missions.me')->subject('Speaker Request from Missions.Me Visitor!');
        });

    }

    public function  sendProjectSponsorEmail(Request $request)
    {
        $data = [
            'email' => $request->get('email'),
            'name' => $request->get('name'),
            'phone_one' => $request->get('phone_one'),
            'project_name' => $request->get('project_name'),
            'complete_at' => $request->get('complete_at'),
            'cause' => ProjectCause::find($request->get('causeId'))->name,
            'initiative' => ProjectInitiative::find($request->get('initiativeId'))->type,
            'total' => $request->get('total'),
        ];

        Mail::queue('emails.sponsor-request', ['data' => $data], function ($m) use ($data) {
            $m->to('katie@missions.me', 'Katie @ Missions.me')->subject('New Project Request!');
        });

    }

    public function getPastTrips()
    {
       return config('accolades.trips');
    }

    public function getActivityTypes()
    {
        return DB::table('activity_types')->get();
    }
}
