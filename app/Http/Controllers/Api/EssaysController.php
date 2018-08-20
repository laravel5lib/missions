<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\Essay;
use Spatie\QueryBuilder\Filter;
use App\Http\Controllers\Controller;
use Dingo\Api\Contract\Http\Request;
use App\Http\Resources\EssayResource;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Requests\v1\EssayRequest;

class EssaysController extends Controller
{

    /**
     * Get all essays.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $essays = QueryBuilder::for(Essay::class)
            ->when($request->segment(2) === 'essays', function ($query) {
                return $query->where('subject', 'Testimony');
            })
            ->when($request->segment(2) === 'influencer-applications', function ($query) {
                return $query->where('subject', 'Influencer');
            })
            ->allowedFilters(['author_name', Filter::exact('user_id'), Filter::scope('managed_by'),])
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
        $essay = Essay::with(['user'])->findOrFail($id);

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
        $request->merge(['subject' => $this->getSubject($request)]);

        $essay = Essay::create($request->all());

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
        $essay = Essay::findOrFail($id);

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
        $essay = Essay::findOrFail($id);

        $essay->delete();

        return response()->json([], 204);
    }

    private function getSubject($request)
    {
        if ($request->segment(2) === 'essays') {
            return 'Testimony';
        }

        if ($request->segment(2) === 'influencer-applications') {
            return 'Influencer';
        }

        return 'Essay';
    }
}
