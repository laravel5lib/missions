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

    public function show($id)
    {
        // maybe there is a better way to filter conditional content
        $credential = $this->api->get('credentials/media/' . $id);
        $new_content = [];
        foreach ($credential['content'] as $index => $content) {
            if (isset($content['conditions'])) {
                if (isset($content['conditions']['role'])) {
                    if ($credential['content'][0]['a'] === $content['conditions']['role']) {
                        $new_content[] = $content;
                    }
                }
            } else {
                $new_content[] = $content;
            }
        }

        $credential['content'] = $new_content;

        $this->seo()->setTitle($credential->applicant_name . ' - Media Credentials');

        return view('dashboard.media-credentials.show', compact('credential'));
    }

    public function edit($id)
    {
        $this->seo()->setTitle('Edit Media Credentials');

        return view('dashboard.media-credentials.edit', compact('id'));
    }
}
