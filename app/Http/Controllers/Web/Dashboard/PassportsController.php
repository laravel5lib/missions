<?php

namespace App\Http\Controllers\Web\Dashboard;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PassportsController extends Controller
{
    public function create()
    {
        return view('dashboard.passports.create');
    }

    public function show($id)
    {
        $passport = $this->api->get('passports/' . $id);
        return view('dashboard.passports.show', compact('passport'));
    }

    public function edit($id)
    {
        return view('dashboard.passports.edit', compact('id'));
    }
}
