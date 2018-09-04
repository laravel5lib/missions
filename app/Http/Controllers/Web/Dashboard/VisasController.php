<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Requests;
use App\Models\v1\Visa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;

class VisasController extends Controller
{
    use SEOTools;

    public function create()
    {
        $this->authorize('create', Visa::class);

        $this->seo()->setTitle('Add Visa');

        return view('dashboard.visas.create');
    }

    public function edit($id)
    {
        $this->authorize('delete', Visa::class);

        $this->seo()->setTitle('Edit Visa');

        return view('dashboard.visas.edit', compact('id'));
    }
}
