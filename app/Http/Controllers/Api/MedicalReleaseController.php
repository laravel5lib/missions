<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use App\Models\v1\MedicalRelease;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\MedicalReleaseResource;
use App\Http\Requests\CreateMedicalReleaseRequest;
use App\Http\Requests\UpdateMedicalReleaseRequest;

class MedicalReleaseController extends Controller
{
    /**
     * Show a list of medical releases.
     * 
     * @return Resource
     */
    public function index()
    {
        $releases = QueryBuilder::for(MedicalRelease::class)
            ->allowedFilters(
                'name', 'ins_provider', 'ins_policy_no',
                Filter::scope('managed_by'),
                Filter::exact('user_id')
            )
            ->allowedIncludes(
                'user', 'conditions', 'allergies', 'uploads'
            )
            ->withCount(['allergies', 'conditions'])
            ->paginate(request()->input('per_page', 10));

        return MedicalReleaseResource::collection($releases);
    }

    /**
     * Show a specific medical release.
     * 
     * @param  string $id
     * @return Resource
     */
    public function show($id)
    {
        $release = MedicalRelease::query()
            ->with(['conditions', 'allergies', 'user'])
            ->withCount(['allergies', 'conditions'])
            ->findOrFail($id);

        return new MedicalReleaseResource($release);
    }

    /**
     * Create a new medical release and save in storage.
     * 
     * @param  CreateMedicalReleaseRequest $request
     * @return Response                               
     */
    public function store(CreateMedicalReleaseRequest $request)
    {
        if (! empty($request->get('conditions'))) {
            $request->merge(['is_risk' => true]);
        }

        $release = MedicalRelease::create($request->all());

        $release->syncConditions($request->input('conditions'));
        $release->syncAllergies($request->input('allergies'));

        if ($request->has('upload_ids')) {
            $release->uploads()->sync($request->get('upload_ids'));
        }

        $release->attachToReservation($request->input(['reservation_id']));

        return response()->json(['message' => 'Medical release created'], 201);
    }

    /**
     * Update a medical release and save changes in storage.
     * 
     * @param  UpdateMedicalReleaseRequest $request 
     * @return Response                               
     */
    public function update(UpdateMedicalReleaseRequest $request, $id)
    {
        $release = MedicalRelease::findOrFail($id);

        $release->update($request->all());

        $release->syncConditions($request->input('conditions'));
        $release->syncAllergies($request->input('allergies'));

        if ($request->has('upload_ids')) {
            $release->uploads()->sync($request->get('upload_ids'));
        }

        $release->attachToReservation($request->input(['reservation_id']));

        $medicalRelease = $release->fresh(['conditions', 'allergies']);

        return new MedicalReleaseResource($medicalRelease);
    }

    /**
     * Delete the specified medical release and remove from storage.
     * 
     * @param  string $id
     * @return Response
     */
    public function destroy($id)
    {
        $release = MedicalRelease::findOrFail($id);

        $release->delete();

        return response()->json([], 204);
    }
}
