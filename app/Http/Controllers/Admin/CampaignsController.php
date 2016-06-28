<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CampaignsController extends Controller
{
    public function index()
    {
        $campaigns = $this->api->get('campaigns');

        return view('admin.campaigns.index')->with('campaigns', $campaigns);
    }

    public function show($id)
    {
        $campaign = $this->api->get('campaigns/'.$id);

        return view('admin.campaigns.show')->with('campaign', $campaign);
    }

    public function create()
    {
        return view('admin.campaigns.create');
    }
}
