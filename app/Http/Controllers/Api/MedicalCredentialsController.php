<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\v1\Credential;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\MedicalCredentialRequest;
use App\Http\Transformers\v1\CredentialTransformer;

class MedicalCredentialsController extends Controller
{
    private $credential;

    public function __construct(Credential $credential)
    {
        $this->credential = $credential;
    }

    /**
     * Get a list of medical credentials
     * 
     * @return response
     */
    public function index(Request $request)
    {
       $credentials = $this->credential
                           ->medical()
                           ->filter($request->all())
                           ->paginate($request->get('per_page', 10));

       return $this->response->paginator($credentials, new CredentialTransformer);
    }

    /**
     * Get a medical credentials by it's id
     * 
     * @param  String $id
     * @return response
     */
    public function show($id)
    {
        $credential = $this->credential->medical()->findOrFail($id);

       return $this->response->item($credential, new CredentialTransformer);
    }

    /**
     * Create a new medical credential
     * 
     * @param  MedicalCredentialRequest $request
     * @return response
     */
    public function store(MedicalCredentialRequest $request)
    {
        $credential = $this->credential->create([
            'type' => 'medical',
            'holder_id' => $request->get('holder_id'),
            'holder_type' => $request->get('holder_type'),
            'applicant_name' => $request->get('applicant_name'),
            'content' => $request->get('content'),
            'expired_at' => $request->get('expired_at')
        ]);

        if ($request->has('upload_ids')) {
            $release->uploads()->sync($request->get('upload_ids'));
        }

        return $this->response->item($credential, new CredentialTransformer);
    }

    /**
     * Update a medical credential
     * 
     * @param  MedicalCredentialRequest $request
     * @param  String                   $id
     * @return response
     */
    public function update(MedicalCredentialRequest $request, $id)
    {
        $credential = $this->credential->medical()->findOrFail($id);

        $credential->update([
            'type' => 'medical',
            'holder_id' => $request->get('holder_id', $credential->holder_id),
            'holder_type' => $request->get('holder_type', $credential->holder_type),
            'applicant_name' => $request->get('applicant_name', $credential->applicant_name),
            'content' => $request->get('content', $credential->content),
            'expired_at' => $request->get('expired_at', $credential->expired_at)
        ]);

        if ($request->has('upload_ids')) {
            $release->uploads()->sync($request->get('upload_ids'));
        }

        return $this->response->item($credential, new CredentialTransformer);
    }

    /**
     * Soft delete a medical credential
     * 
     * @param  String $id
     * @return response
     */
    public function destroy($id)
    {
        $credential = $this->credential->medical()->findOrFail($id);

        $credential->delete();

        return $this->response->noContent();
    }
}
