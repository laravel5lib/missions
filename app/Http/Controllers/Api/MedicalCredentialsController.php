<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\v1\MedicalCredential as Credential;
use Spatie\QueryBuilder\Filter;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\CredentialResource;
use App\Http\Requests\v1\MedicalCredentialRequest;

class MedicalCredentialsController extends Controller
{
    /**
     * Get a list of medical credentials
     *
     * @return response
     */
    public function index(Request $request)
    {
        $credentials = QueryBuilder::for(Credential::medical())
            ->allowedFilters(['applicant_name', Filter::exact('user_id'), Filter::scope('managed_by')])
            ->allowedIncludes(['user'])
            ->paginate($request->get('per_page', 10));

        return CredentialResource::collection($credentials);
    }

    /**
     * Get a medical credentials by it's id
     *
     * @param  String $id
     * @return response
     */
    public function show($id)
    {
        $credential = Credential::medical()->with(['user'])->findOrFail($id);

        return new CredentialResource($credential);
    }

    /**
     * Create a new medical credential
     *
     * @param  MedicalCredentialRequest $request
     * @return response
     */
    public function store(MedicalCredentialRequest $request)
    {
        $credential = Credential::create(
            $request->merge(['type' => 'medical'])->all()
        );

        $credential->attachToReservation(
            $request->input('reservation_id')
        );

        if ($request->has('upload_ids')) {
            $credential->uploads()->sync($request->get('upload_ids'));
        }

        return new CredentialResource($credential);
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
        $credential = Credential::medical()->findOrFail($id);

        $credential->update($request->all()->merge(['type' => 'medical']));

        $credential->attachToReservation($request->input('reservation_id'));

        if ($request->has('upload_ids')) {
            $credential->uploads()->sync($request->get('upload_ids'));
        }

        return new CredentialResource($credential);
    }

    /**
     * Soft delete a medical credential
     *
     * @param  String $id
     * @return response
     */
    public function destroy($id)
    {
        $credential = Credential::medical()->findOrFail($id);

        $credential->delete();

        return response()->json([], 204);
    }
}
