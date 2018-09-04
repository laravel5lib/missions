<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\MedicalRelease;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;

class MedicalReleasesController extends Controller
{
    use SEOTools;

    public function create()
    {
        $this->authorize('create', MedicalRelease::class);

        $this->seo()->setTitle('Add Medical Release');

        return view('admin.records.medical-releases.create');
    }

    public function edit(MedicalRelease $medicalRelease)
    {
        $this->authorize('update', $medicalRelease);

        $this->seo()->setTitle('Edit Medical Release');

        return view('admin.records.medical-releases.edit')->with('id', $medicalRelease->id);
    }
}
