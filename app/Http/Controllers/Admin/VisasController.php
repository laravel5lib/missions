<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Visa;
use App\Http\Controllers\Controller;

class VisasController extends Controller
{
    public function create()
    {
        $this->authorize('create', Visa::class);

        return view('admin.records.visas.create');
    }

    public function show(Visa $visa)
    {
        $this->authorize('view', $visa);

        return view('admin.records.visas.show', compact('visa'));
    }

    public function edit(Visa $visa)
    {
        $this->authorize('update', $visa);

        return view('admin.records.visas.edit')->with('id', $visa->id);
    }
}
