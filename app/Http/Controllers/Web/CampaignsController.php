<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CampaignsController extends Controller
{
    public function index()
    {
        return view('site.campaigns.index');
    }

    public function show($id)
    {
        try {
            $campaign = $this->api->get('campaigns/' . $id);
        } catch (Dingo\Api\Exception\InternalHttpException $e) {
            $response = $e->getResponse();

            return $response;
        }

        return view('site.campaigns.show', compact('campaign'));
    }

    public function trips($slug)
    {
        try {
            $campaign = $this->api->get('campaigns/' . $slug);
        } catch (Dingo\Api\Exception\InternalHttpException $e) {
            $response = $e->getResponse();

            return $response;
        }

        return view('site.campaigns.trips.index', compact('campaign'));
    }
}
