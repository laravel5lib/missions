<?php

namespace App\Http\Controllers\Web\Dashboard;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class GroupsController extends Controller
{
    public function index()
    {
        if( ! auth()->user()->managing()->count()) abort(403);
        return view('dashboard.groups.index');
    }

    public function show($id)
    {
        if( ! auth()->user()->managing()->count()) abort(403);
        $group = $this->api->get('groups/' . $id);
        return view('dashboard.groups.show', compact('group', 'id'));
    }
    
    public function edit($id)
    {
        if( ! auth()->user()->managing()->count()) abort(403);
        $group = $this->api->get('groups/' . $id);
        return view('dashboard.groups.edit', compact('group', 'id'));
    }

    public function trips($groupId, $id, $tab = 'details')
    {
        if( ! auth()->user()->managing()->count()) abort(403);
        $group = $this->api->get('groups/' . $groupId);
        $trip = $this->api->get('trips/' . $id, ['include' => 'campaign,costs.payments,requirements,notes,deadlines']);
        return view('dashboard.groups.trips.show', compact('group', 'groupId', 'trip', 'id', 'tab'));
    }
    public function teams($groupId)
    {
        if( ! auth()->user()->managing()->count()) abort(403);
        $group = $this->api->get('groups/' . $groupId);
        return view('dashboard.groups.teams.index', compact('group', 'groupId'));
    }
}
