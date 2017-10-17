<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\v1\Credential;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\MediaCredentialRequest;
use App\Http\Transformers\v1\CredentialTransformer;

class MediaCredentialsController extends Controller
{
    private $credential;

    public function __construct(Credential $credential)
    {
        $this->credential = $credential;
    }

    /**
     * Get a list of media credentials
     *
     * @return response
     */
    public function index(Request $request)
    {
        $credentials = $this->credential
                           ->media()
                           ->filter($request->all())
                           ->paginate($request->get('per_page', 10));

        return $this->response->paginator($credentials, new CredentialTransformer);
    }

    /**
     * Get a media credential by it's id
     *
     * @param  String $id
     * @return response
     */
    public function show($id)
    {
        $credential = $this->credential->media()->findOrFail($id);

        return $this->response->item($credential, new CredentialTransformer);
    }

    /**
     * Create a new media credential
     *
     * @param  MediaCredentialRequest $request
     * @return response
     */
    public function store(MediaCredentialRequest $request)
    {
        $credential = $this->credential->create([
            'type' => 'media',
            'holder_id' => $request->get('holder_id'),
            'holder_type' => $request->get('holder_type'),
            'applicant_name' => $request->get('applicant_name'),
            'content' => $request->get('content'),
            'expired_at' => $request->get('expired_at')
        ]);

        return $this->response->item($credential, new CredentialTransformer);
    }

    /**
     * Update a media credential
     *
     * @param  mediaCredentialRequest $request
     * @param  String                   $id
     * @return response
     */
    public function update(MediaCredentialRequest $request, $id)
    {
        $credential = $this->credential->media()->findOrFail($id);

        $credential->update([
            'type' => 'media',
            'holder_id' => $request->get('holder_id', $credential->holder_id),
            'holder_type' => $request->get('holder_type', $credential->holder_type),
            'applicant_name' => $request->get('applicant_name', $credential->applicant_name),
            'content' => $request->get('content', $credential->content),
            'expired_at' => $request->get('expired_at', $credential->expired_at)
        ]);

        return $this->response->item($credential, new CredentialTransformer);
    }

    /**
     * Soft delete a media credential
     *
     * @param  String $id
     * @return response
     */
    public function destroy($id)
    {
        $credential = $this->credential->media()->findOrFail($id);

        $credential->delete();

        return $this->response->noContent();
    }
}
