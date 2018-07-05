<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\Squad;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use App\Http\Controllers\Controller;
use App\Http\Resources\SquadResource;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Requests\CreateSquadRequest;
use App\Http\Requests\UpdateSquadRequest;

class SquadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $squads = QueryBuilder::for(Squad::class)
            ->allowedFilters([
                'callsign',
                Filter::exact('published'),
                Filter::exact('region_id'),
                Filter::scope('campaign_id'),
            ])
            ->allowedIncludes(['region'])
            ->withCount('members')
            ->paginate(request()->input('per_page', 25));
        
        return SquadResource::collection($squads);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSquadRequest $request)
    {
        Squad::create(
            $request->only(['region_id', 'callsign'])
        );

        return response()->json(['message' => 'Squad created.'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $squad = Squad::whereUuid($uuid)->firstOrFail();
        
        return new SquadResource($squad);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $uuid
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSquadRequest $request, $uuid)
    {
        $squad = Squad::whereUuid($uuid)->firstOrFail();

        $squad->update(
            $request->only(['callsign', 'region_id', 'published'])
        );

        return new SquadResource($squad);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $uuid
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        $squad = Squad::whereUuid($uuid)->firstOrFail();

        $squad->delete();

        return response()->json([], 204);
    }
}
