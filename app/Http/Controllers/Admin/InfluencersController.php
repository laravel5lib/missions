<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Essay;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;
use App\Models\v1\InfluencerApplication;

class InfluencersController extends Controller
{
    use SEOTools;

    public function create()
    {
        $this->authorize('create', Essay::class);

        $this->seo()->setTitle('Add Influencer Application');

        return view('admin.records.influencer-applications.create');
    }

    public function edit(InfluencerApplication $influencer)
    {
        $this->authorize('update', $influencer);

        $this->seo()->setTitle('Edit Influencer Application');

        return view('admin.records.influencer-applications.edit')->with('id', $influencer->id);
    }
}
