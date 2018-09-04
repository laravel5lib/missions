<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Passport;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;

class PassportsController extends Controller
{
    use SEOTools;

    public function create()
    {
        $this->authorize('create', Passport::class);

        $this->seo()->setTitle('Add Passport');

        return view('admin.records.passports.create');
    }

    public function edit(Passport $passport)
    {
        $this->authorize('update', $passport);

        $this->seo()->setTitle('Edit Passport');

        return view('admin.records.passports.edit')->with('id', $passport->id);
    }
}
