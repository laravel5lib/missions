<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\v1\MediaCredential as Credential;
use Spatie\QueryBuilder\Filter;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\CredentialResource;
use App\Http\Requests\v1\MediaCredentialRequest;

class MediaCredentialsController extends Controller
{
    /**
     * Get a list of media credentials
     *
     * @return response
     */
    public function index(Request $request)
    {
        $credentials = QueryBuilder::for(Credential::media())
            ->allowedFilters(['applicant_name', Filter::exact('user_id'), Filter::scope('managed_by')])
            ->allowedIncludes(['user'])
            ->paginate($request->get('per_page', 10));

        return CredentialResource::collection($credentials);
    }

    /**
     * Get a media credential by it's id
     *
     * @param  String $id
     * @return response
     */
    public function show($id)
    {
        $credential = Credential::media()->with(['user'])->findOrFail($id);

        return new CredentialResource($credential);
    }

    /**
     * Create a new media credential
     *
     * @param  MediaCredentialRequest $request
     * @return response
     */
    public function store(MediaCredentialRequest $request)
    {
        $credential = Credential::create(
            $request->merge(['type' => 'media'])->all()
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
     * Update a media credential
     *
     * @param  mediaCredentialRequest $request
     * @param  String                   $id
     * @return response
     */
    public function update(MediaCredentialRequest $request, $id)
    {
        $credential = Credential::media()->findOrFail($id);

        $credential->update($request->merge(['type' => 'media'])->all());

        $credential->attachToReservation($request->input('reservation_id'));

        if ($request->has('upload_ids')) {
            $credential->uploads()->sync($request->get('upload_ids'));
        }

        return new CredentialResource($credential);
    }

    /**
     * Soft delete a media credential
     *
     * @param  String $id
     * @return response
     */
    public function destroy($id)
    {
        $credential = Credential::media()->findOrFail($id);

        $credential->delete();

        return response()->json([], 204);
    }
}
