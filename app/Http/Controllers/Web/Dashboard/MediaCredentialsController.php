<?php

namespace App\Http\Controllers\Web\Dashboard;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MediaCredentialsController extends Controller
{
    public function create()
    {
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

        return view('dashboard.media-credentials.show', compact('credential'));
    }

    public function edit($id)
    {
        return view('dashboard.media-credentials.edit', compact('id'));
    }
}
