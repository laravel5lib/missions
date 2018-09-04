<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;

class MedicalCredentialsController extends Controller
{
    use SEOTools;

    public function create()
    {
        $this->seo()->setTitle('Add Medical Credentials');

        return view('dashboard.medical-credentials.create');
    }

    public function edit($id)
    {
        $this->seo()->setTitle('Edit Medical Credentials');

        return view('dashboard.medical-credentials.edit', compact('id'));
    }
}
