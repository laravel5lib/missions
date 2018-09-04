<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;

class MediaCredentialsController extends Controller
{
    use SEOTools;

    public function create()
    {
        $this->seo()->setTitle('Add Media Credentials');

        return view('dashboard.media-credentials.create');
    }

    public function edit($id)
    {
        $this->seo()->setTitle('Edit Media Credentials');

        return view('dashboard.media-credentials.edit', compact('id'));
    }
}
