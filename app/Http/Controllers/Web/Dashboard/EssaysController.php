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

    public function edit($id)
    {
        $this->seo()->setTitle('Edit Testimony');

        return view('dashboard.essays.edit', compact('id'));
    }
}
