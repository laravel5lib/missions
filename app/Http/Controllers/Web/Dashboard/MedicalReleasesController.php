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

    public function edit($id)
    {
        $this->seo()->setTitle('Edit Medical Release');

        return view('dashboard.medical-releases.edit', compact('id'));
    }
}
