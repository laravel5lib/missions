<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Essay;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;

class InfluencersController extends Controller
{
    use SEOTools;

    public function create()
    {
        $this->authorize('create', Essay::class);

        $this->seo()->setTitle('Add Influencer Application');

        return view('admin.records.influencers.create');
    }

    public function show(Essay $influencer)
    {
        $this->authorize('view', $influencer);

        $this->seo()->setTitle($influencer->author_name . ' - Influencer Application');

        return view('admin.records.influencers.show')->with('essay', $influencer);
    }

    public function edit(Essay $influencer)
    {
        $this->authorize('update', $influencer);

        $this->seo()->setTitle('Edit Influencer Application');

        return view('admin.records.influencers.edit')->with('id', $influencer->id);
    }
}
