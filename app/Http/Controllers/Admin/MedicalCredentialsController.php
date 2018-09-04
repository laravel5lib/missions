<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Credential;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;

class MedicalCredentialsController extends Controller
{
    use SEOTools;

    public function create()
    {
        $this->authorize('create', Credential::class);

        $this->seo()->setTitle('Add Medical Credentials');

        return view('admin.records.medical-credentials.create');
    }

    public function edit(Credential $medicalCredential)
    {
        $this->authorize('update', $medicalCredential);

        $this->seo()->setTitle('Edit Medical Credentials');

        return view('admin.records.medical-credentials.edit')->with('id', $medicalCredential->id);
    }
}
