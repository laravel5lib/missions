<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Visa;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;

class VisasController extends Controller
{
    use SEOTools;

    public function create()
    {
        $this->authorize('create', Visa::class);

        $this->seo()->setTitle('Add Visa');

        return view('admin.records.visas.create');
    }

    public function show(Visa $visa)
    {
        $this->authorize('view', $visa);

        $this->seo()->setTitle($visa->given_names . ' ' . $visa->surname .' - Visa');

        return view('admin.records.visas.show', compact('visa'));
    }

    public function edit(Visa $visa)
    {
        $this->authorize('update', $visa);

        $this->seo()->setTitle('Edit Visa');

        return view('admin.records.visas.edit')->with('id', $visa->id);
    }
}
