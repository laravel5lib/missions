<?php

namespace App\Http\Controllers\Web\Dashboard;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class EssaysController extends Controller
{
    public function create()
    {
        return view('dashboard.essays.create');
    }
    
    public function show($id)
    {
        $essay = $this->api->get('essays/' . $id);

        return view('dashboard.essays.show', compact('essay'));
    }

    public function edit($id)
    {
        return view('dashboard.essays.edit', compact('id'));
    }
}
