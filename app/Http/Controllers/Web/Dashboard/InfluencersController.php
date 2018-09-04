<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;

class InfluencersController extends Controller
{
    use SEOTools;

    public function create()
    {
        $this->seo()->setTitle('Add Influencer Application');

        return view('dashboard.influencer-applications.create');
    }

    public function edit($id)
    {
         $this->seo()->setTitle('Edit Influencer Application');

        return view('dashboard.influencer-applications.edit', compact('id'));
    }
}
