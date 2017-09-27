<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;

class EssaysController extends Controller
{
    use SEOTools;

    public function create()
    {
        $this->seo()->setTitle('Add Testimony');

        return view('dashboard.essays.create');
    }

    public function show($id)
    {
        $essay = $this->api->get('essays/' . $id.'?include=uploads,user');

        $this->seo()->setTitle($essay->author_name . ' - Testimony');

        return view('dashboard.essays.show', compact('essay'));
    }

    public function edit($id)
    {
        $this->seo()->setTitle('Edit Testimony');

        return view('dashboard.essays.edit', compact('id'));
    }
}
