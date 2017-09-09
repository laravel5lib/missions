<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Essay;
use App\Http\Controllers\Controller;

class InfluencersController extends Controller
{
    public function create()
    {
        $this->authorize('create', Essay::class);

        return view('admin.records.influencers.create');
    }

    public function show(Essay $essay)
    {
        $this->authorize('view', $essay);

        return view('admin.records.influencers.show', compact('essay'));
    }

    public function edit(Essay $essay)
    {
        $this->authorize('update', $essay);

        return view('admin.records.influencers.edit')->with('id', $essay->id);
    }
}
