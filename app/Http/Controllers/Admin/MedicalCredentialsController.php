<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Credential;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;

class MedicalCredentialsController extends Controller
{
    use SEOTools;

    public function create()
    {
        $this->authorize('create', Credential::class);

        $this->seo()->setTitle('Add Medical Credentials');

        return view('admin.records.medical-credentials.create');
    }

    public function show($id)
    {
        $credential = Credential::medical()->findOrFail($id);

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

        $this->seo()->setTitle($credential->applicant_name . ' - Medical Credentials');

        return view('admin.records.medical-credentials.show', compact('credential'));
    }

    public function edit(Credential $credential)
    {
        $this->authorize('update', $credential);

        $this->seo()->setTitle('Edit Medical Credentials');

        return view('admin.records.medical-credentials.edit')->with('id', $credential->id);
    }
}
