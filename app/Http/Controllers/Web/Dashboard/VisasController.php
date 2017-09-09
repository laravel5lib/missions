<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Requests;
use App\Models\v1\Visa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VisasController extends Controller
{
    public function create()
    {
        $this->authorize('create', Visa::class);

        return view('dashboard.visas.create');
    }

    public function show($id)
    {
        $visa = Visa::findOrFail($id);

        $this->authorize('view', $visa);

        return view('dashboard.visas.show', compact('visa'));
    }

    public function edit($id)
    {
        $this->authorize('delete', Visa::class);

        return view('dashboard.visas.edit', compact('id'));
    }
}
