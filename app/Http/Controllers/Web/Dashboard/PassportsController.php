<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Requests;
use App\Models\v1\Passport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PassportsController extends Controller
{
    public function create()
    {
        $this->authorize('create', Passport::class);

        return view('dashboard.passports.create');
    }

    public function show($id)
    {
        $passport = Passport::findOrFail($id);

        $this->authorize('view', $passport);

        return view('dashboard.passports.show', compact('passport'));
    }

    public function edit($id)
    {
        $passport = Passport::findOrFail($id);

        $this->authorize('update', $passport);

        return view('dashboard.passports.edit', compact('id'));
    }
}
