<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\MedicalRelease;
use App\Http\Controllers\Controller;

class MedicalReleasesController extends Controller
{
    public function create()
    {
        $this->authorize('create', MedicalRelease::class);

        return view('admin.records.medical-releases.create');
    }

    public function show(MedicalRelease $release)
    {
        $this->authorize('view', $release);

        return view('admin.records.medical-releases.show', compact('release'));
    }

    public function edit(MedicalRelease $release)
    {
        $this->authorize('update', $release);

        return view('admin.records.medical-releases.edit')->with('id', $release->id);
    }
}
