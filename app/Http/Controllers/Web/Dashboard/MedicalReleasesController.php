<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;

class MedicalReleasesController extends Controller
{
    use SEOTools;

    public function create()
    {
        $this->seo()->setTitle('Add Medical Release');

        return view('dashboard.medical-releases.create');
    }

    public function show($id)
    {
        $release = $this->api->get('medical/releases/' . $id);

        $this->seo()->setTitle($release->name . ' - Medical Release');

        return view('dashboard.medical-releases.show', compact('release'));
    }

    public function edit($id)
    {
        $this->seo()->setTitle('Edit Medical Release');

        return view('dashboard.medical-releases.edit', compact('id'));
    }
}
