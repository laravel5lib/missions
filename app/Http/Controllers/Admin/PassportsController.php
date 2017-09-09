<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Passport;
use App\Http\Controllers\Controller;

class PassportsController extends Controller
{
    public function create()
    {
        $this->authorize('create', Passport::class);

        return view('admin.records.passports.create');
    }

    public function show(Passport $passport)
    {
        $this->authorize('create', $passport);

        return view('admin.records.passports.show', compact('passport'));
    }

    public function edit(Passport $passport)
    {
        $this->authorize('update', $passport);

        return view('admin.records.passports.edit')->with('id', $passport->id);
    }
}
