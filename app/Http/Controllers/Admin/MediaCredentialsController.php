<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Credential;
use App\Http\Controllers\Controller;

class MediaCredentialsController extends Controller
{
    public function create()
    {
        $this->authorize('create', Credential::class);

        return view('admin.records.media-credentials.create');
    }

    public function show($id)
    {
        $credential = Credential::media()->findOrFail($id);

        $this->authorize('view', $credential);

        // maybe there is a better way to filter conditional content
        $new_content = [];
        foreach ($credential->content as $index => $content) {
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

        return view('admin.records.media-credentials.show', compact('credential'));
    }

    public function edit(Credential $credential)
    {
        $this->authorize('update', $credential);

        return view('admin.records.media-credentials.edit')->with('id', $credential->id);
    }
}
