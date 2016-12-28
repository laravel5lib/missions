<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\ProjectCause;
use App\Models\v1\ProjectInitiative;
use App\Utilities\v1\Country;
use Dingo\Api\Contract\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class UtilitiesController extends Controller
{

    public function getCountries()
    {
        $countryList = Country::all();
        /*array_walk($countries, function (&$item, $key){
            $item = "['name' => $key, 'code' => $item]";
        });*/
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

    public function getTimezones()
    {
        $timezones = \DateTimeZone::listIdentifiers();
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
            'cause' => ProjectCause::find($request->get('causeId')),
            'initiative' => ProjectInitiative::find($request->get('initiativeId')),
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
}
