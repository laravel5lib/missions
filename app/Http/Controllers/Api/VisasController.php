<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Models\v1\Visa;
use Spatie\QueryBuilder\Filter;
use App\Http\Controllers\Controller;
use App\Http\Resources\VisaResource;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Requests\UpdateVisaRequest;
use App\Http\Requests\v1\CreateVisaRequest;

class VisasController extends Controller
{
    /**
     * Get a list of visas.
     *
     * @param Request $request
     * @return Response
     */
    public function index()
    {
        $visas = QueryBuilder::for(Visa::class)
            ->allowedFilters([
                'given_names', 
                'surname',
                Filter::exact('number'),
                Filter::exact('country_code'),
                Filter::exact('user_id'),
                Filter::scope('managed_by')
            ])
            ->allowedIncludes(['user'])
            ->paginate(
                request()->input('per_page', 25)
            );

        return VisaResource::collection($visas);
    }

    /**
     * Get the specified visa.
     *
     * @param $id
     * @return Response
     */
    public function show($id)
    {
        $visa = Visa::with('user')->findOrFail($id);

        return new VisaResource($visa);
    }

    /**
     * Create a new visa and save in storage.
     *
     * @param CreateVisaRequest $request
     * @return Response
     */
    public function store(CreateVisaRequest $request)
    {
        $visa = Visa::create($request->all());

        $visa->attachToReservation($request->input('reservation_id'));

        return response()->json(['message' => 'New visa created.'], 201);
    }

    /**
     * Update the specified visa in storage.
     *
     * @param UpdateVisaRequest $request
     * @param $id
     * @return Response
     */
    public function update(UpdateVisaRequest $request, $id)
    {
        $visa = Visa::findOrFail($id);

        $visa->update($request->all());

        $visa->attachToReservation($request->input('reservation_id'));

        return new VisaResource($visa);
    }

    /**
     * Remove the specified visa from storage.
     *
     * @param $id
     * @return Response
     */
    public function destroy($id)
    {
        $visa = Visa::findOrFail($id);

        $visa->delete();

        return response()->json([], 204);
    }
}
