<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Trip;
use App\Http\Requests;
use App\Models\v1\Group;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;

class GroupsController extends Controller
{
    use SEOTools;

    public function index()
    {
        if (! auth()->user()->managing()->count()) {
            abort(403);
        }

        $this->seo()->setTitle('Groups Managed');

        return view('dashboard.groups.index');
    }

    public function show($id)
    {
        if (! auth()->user()->managing()->count()) {
            abort(403);
        }

        $group = Group::findOrFail($id);

        $this->seo()->setTitle($group->name . ' Group');

        return view('dashboard.groups.show', compact('group', 'id'));
    }

    public function edit($id)
    {
        if (! auth()->user()->managing()->count()) {
            abort(403);
        }

        $group = Group::findOrFail($id);

        $this->seo()->setTitle('Edit Group');

        return view('dashboard.groups.edit', compact('group', 'id'));
    }

    public function trips($groupId, $id, $tab = 'details')
    {
        if (! auth()->user()->managing()->count()) {
            abort(403);
        }

        $group = Group::findOrFail($groupId);

        $trip = Trip::getById($id);

        $this->seo()->setTitle(title_case($trip->type . ' Trip ' . $tab) . ' - ' . $group->name);

        return view('dashboard.groups.trips.show', compact('group', 'groupId', 'trip', 'id', 'tab'));
    }
    public function teams($groupId)
    {
        if (! auth()->user()->managing()->count()) {
            abort(403);
        }

        $group = Group::findOrFail($groupId);

        $this->seo()->setTitle('Squads - ' . $group->name);

        return view('dashboard.groups.teams.index', compact('group', 'groupId'));
    }

    public function rooms($groupId)
    {
        if (! auth()->user()->managing()->count()) {
            abort(403);
        }

        $group = Group::findOrFail($groupId);

        $this->seo()->setTitle('Rooming - ' . $group->name);

        return view('dashboard.groups.rooms.index', compact('group', 'groupId'));
    }
}
