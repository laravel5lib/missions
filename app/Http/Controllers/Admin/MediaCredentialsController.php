<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Credential;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;

class MediaCredentialsController extends Controller
{
    use SEOTools;

    public function create()
    {
        $this->authorize('create', Credential::class);

        $this->seo()->setTitle('Add Media Credentials');

        return view('admin.records.media-credentials.create');
    }

    public function edit(Credential $mediaCredential)
    {
        $this->authorize('update', $mediaCredential);

        $this->seo()->setTitle('Edit Media Credentials');

        return view('admin.records.media-credentials.edit')->with('id', $mediaCredential->id);
    }
}
