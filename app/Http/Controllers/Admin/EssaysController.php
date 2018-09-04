<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Essay;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;

class EssaysController extends Controller
{
    use SEOTools;

    public function create()
    {
        $this->authorize('create', Essay::class);

        $this->seo()->setTitle('Add Testimony');

        return view('admin.records.essays.create');
    }

    public function edit(Essay $essay)
    {
        $this->authorize('update', $essay);

        $this->seo()->setTitle('Edit Testimony');

        return view('admin.records.essays.edit')->with('id', $essay->id);
    }
}
