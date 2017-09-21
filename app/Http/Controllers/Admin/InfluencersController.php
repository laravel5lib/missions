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

    public function show(Essay $essay)
    {
        $this->authorize('view', $essay);

        $this->seo()->setTitle($essay->author_name . ' - Influencer Application');

        return view('admin.records.influencers.show', compact('essay'));
    }

    public function edit(Essay $essay)
    {
        $this->authorize('update', $essay);

        $this->seo()->setTitle('Edit Influencer Application');

        return view('admin.records.influencers.edit')->with('id', $essay->id);
    }
}
