<?php

namespace App\Http\Controllers\Web\Dashboard;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MedicalReleasesController extends Controller
{
    public function create()
    {
        return view('dashboard.medical-releases.create');
    }
    
    public function show($id)
    {
        $release = $this->api->get('medical/releases/' . $id);

        return view('dashboard.medical-releases.show', compact('release'));
    }

    public function edit($id)
    {
        return view('dashboard.medical-releases.edit', compact('id'));
    }
}
