<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use App\Models\v1\MedicalRelease;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\MedicalReleaseResource;

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
        $release = MedicalRelease::findOrFail($id);

        return new MedicalReleaseResource($release);
    }
}
