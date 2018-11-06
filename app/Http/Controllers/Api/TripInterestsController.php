<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Models\v1\Todo;
use Illuminate\Http\Request;
use App\Models\v1\TripInterest;
use Spatie\QueryBuilder\Filter;
use App\Jobs\ExportTripInterests;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Events\TripInterestWasCreated;
use App\Http\Requests\v1\ExportRequest;
use App\Http\Resources\TripInterestResource;
use App\Http\Requests\v1\TripInterestRequest;
use App\Http\Transformers\v1\TripInterestTransformer;

class TripInterestsController extends Controller
{
    /**
     * Get all trip interests.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $interests = TripInterest::buildQuery()
            ->withCount([
                'todos as total_tasks_count',
                'todos as complete_tasks_count' => function ($query) {
                    return $query->whereNotNull('completed_at');
                }
            ])
            ->paginate($request->input('per_page', 25));

        return TripInterestResource::collection($interests);
    }

    /**
     * Get trip interest by id.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $interest = TripInterest::findOrFail($id);

        return new TripInterestResource($interest);
    }

    /**
     * Create a new trip interest.
     *
     * @param TripInterestRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(TripInterestRequest $request)
    {
        $interest = TripInterest::create($request->all());

        $interest->todos()->saveMany([
            new Todo(['task' => '1st Contact']),
            new Todo(['task' => '2nd Contact']),
            new Todo(['task' => '3rd Contact']),
            new Todo(['task' => '4th Contact'])
        ]);

        event(new TripInterestWasCreated($interest));

        return response()->json(['message' => 'Interest submitted.'], 201);
    }

    /**
     * Update a new trip interest.
     *
     * @param TripInterestRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function update(TripInterestRequest $request, $id)
    {
        $interest = TripInterest::findOrFail($id);

        $interest->update($request->all());

        return new TripInterestResource($interest);
    }

    /**
     * Delete a trip interest by id.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $interest = TripInterest::findOrFail($id);

        $interest->delete();

        return response()->json(['message' => 'Interest deleted.'], 204);
    }

    /**
     * Export trip interests.
     *
     * @param ExportRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function export(ExportRequest $request)
    {
        $this->dispatch(new ExportTripInterests($request->all()));

        return $this->response()->created(null, [
            'message' => 'Report is being generated and will be available shortly.'
        ]);
    }
}
