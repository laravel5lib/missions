<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\Essay;
use Spatie\QueryBuilder\Filter;
use App\Http\Controllers\Controller;
use Dingo\Api\Contract\Http\Request;
use App\Http\Resources\EssayResource;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Requests\v1\EssayRequest;
use App\Models\v1\InfluencerApplication;

class EssaysController extends Controller
{
    public $types = [
        'essays' => Essay::class,
        'influencer-applications' => InfluencerApplication::class,
    ];

    /**
     * Get all essays.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $essays = QueryBuilder::for($this->types[$request->segment(2)])
            ->when($request->segment(2) === 'essays', function ($query) {
                return $query->where('subject', 'Testimony');
            })
            ->allowedFilters(['author_name', Filter::exact('user_id'), Filter::scope('managed_by'), Filter::scope('name')])
            ->allowedIncludes(['user'])
            ->paginate($request->get('per_page', 25));

        return EssayResource::collection($essays);
    }

    /**
     * Get an essay by id.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $essay = (new $this->types[request()->segment(2)])->with(['user'])->findOrFail($id);

        return new EssayResource($essay);
    }

    /**
     * Create a new essay.
     *
     * @param EssayRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(EssayRequest $request)
    {
        $essay = (new $this->types[$request->segment(2)])->create($request->all());

        $essay->attachToReservation($request->input(['reservation_id']));

        if ($request->has('upload_ids')) {
            $essay->uploads()->sync($request->get('upload_ids'));
        }

        return response()->json(['message' => 'New essay created.'], 201);
    }

    /**
     * Update an essay.
     *
     * @param $id
     * @param EssayRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function update($id, EssayRequest $request)
    {
        $essay = (new $this->types[$request->segment(2)])->findOrFail($id);

        $essay->update($request->all());

        $essay->attachToReservation($request->input(['reservation_id']));

        if ($request->has('upload_ids')) {
            $essay->uploads()->sync($request->get('upload_ids'));
        }

        return new EssayResource($essay);
    }

    /**
     * Delete an essay.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $essay = (new $this->types[request()->segment(2)])->findOrFail($id);

        $essay->delete();

        return response()->json([], 204);
    }
}
