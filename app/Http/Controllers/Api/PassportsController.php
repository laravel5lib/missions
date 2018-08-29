<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Models\v1\Passport;
use Spatie\QueryBuilder\Filter;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\PassportResource;
use App\Http\Requests\v1\CreatePassportRequest;
use App\Http\Requests\v1\UpdatePassportRequest;

class PassportsController extends Controller
{
    /**
     * View a list of passports.
     *
     * @return Resource
     */
    public function index()
    {
        $passports = QueryBuilder::for(Passport::class)
            ->allowedFilters(
                'given_names', 'surname', 'number',
                Filter::scope('managed_by'),
                Filter::exact('user_id')
            )
            ->allowedIncludes('user')
            ->paginate(request()->input('per_page', 10));

        return PassportResource::collection($passports);
    }

    /**
     * Get the specified passport.
     *
     * @param $id
     * @return Response
     */
    public function show($id)
    {
        $passport = Passport::findOrFail($id);

        return new PassportResource($passport);
    }

    /**
     * Create a new passport and save it in storage.
     *
     * @param PassportRequest $request
     * @return Response
     */
    public function store(CreatePassportRequest $request)
    {
        $passport = Passport::create($request->all());

        $passport->attachToReservation($request->input('reservation_id'));

        return response()->json(['message' => 'Passport created.'], 201);
    }

    /**
     * Update the specified passport in storage.
     *
     * @param PassportRequest $request
     * @param $id
     * @return Response
     */
    public function update(UpdatePassportRequest $request, $id)
    {
        $passport = Passport::findOrFail($id);

        $passport->update($request->all());

        $passport->attachToReservation($request->input('reservation_id'));

        return new PassportResource($passport);
    }

    /**
     * Delete the specified passport from storage.
     *
     * @param $id
     * @return Response
     */
    public function destroy($id)
    {
        $passport = Passport::findOrFail($id);

        $passport->delete();

        return response()->json([], 204);
    }
}
