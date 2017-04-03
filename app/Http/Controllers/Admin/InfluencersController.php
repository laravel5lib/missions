<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class InfluencersController extends Controller
{
    public function create()
    {
        return view('admin.records.influencers.create');
    }
    
    public function show($id)
    {
        $essay = $this->api->get('essays/' . $id);

        return view('admin.records.influencers.show', compact('essay'));
    }

    public function edit($id)
    {
        return view('admin.records.influencers.edit', compact('id'));
    }
}
