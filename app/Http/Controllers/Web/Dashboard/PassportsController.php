<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Requests;
use App\Models\v1\Passport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;

class PassportsController extends Controller
{
    use SEOTools;

    public function create()
    {
        $this->authorize('create', Passport::class);

        $this->seo()->setTitle('Add Passport');

        return view('dashboard.passports.create');
    }

    public function edit($id)
    {
        $passport = Passport::findOrFail($id);

        $this->authorize('update', $passport);

        $this->seo()->setTitle('Edit Passport');

        return view('dashboard.passports.edit', compact('id'));
    }
}
