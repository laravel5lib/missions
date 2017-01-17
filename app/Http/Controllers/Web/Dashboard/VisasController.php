<?php

namespace App\Http\Controllers\Web\Dashboard;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class VisasController extends Controller
{
    public function create()
    {
        return view('dashboard.visas.create');
    }

    public function show($id)
    {
        $visa = $this->api->get('visas/' . $id);

        return view('dashboard.visas.show', compact('visa'));
    }

    public function edit($id)
    {
        return view('dashboard.visas.edit', compact('id'));
    }
}
