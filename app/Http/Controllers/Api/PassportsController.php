<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Models\v1\Passport;
use App\Jobs\ExportPassports;
use Spatie\QueryBuilder\Filter;
use App\Http\Controllers\Controller;
use Dingo\Api\Contract\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Requests\v1\ExportRequest;
use App\Http\Requests\v1\ImportRequest;
use App\Http\Resources\PassportResource;
use App\Http\Requests\v1\PassportRequest;
use App\Services\Importers\PassportListImport;
use App\Http\Transformers\v1\PassportTransformer;

class PassportsController extends Controller
{
    /**
     * @var Passport
     */
    private $passport;

    /**
     * PassportsController constructor.
     *
     * @param Passport $passport
     */
    public function __construct(Passport $passport)
    {
        $this->passport = $passport;
    }

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
     * @return \Dingo\Api\Http\Response
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
     * @return \Dingo\Api\Http\Response
     */
    public function store(PassportRequest $request)
    {
        $passport = $this->passport->create($request->all());

        $passport->attachToReservation($request->input('reservation_id'));

        return $this->response->item($passport, new PassportTransformer);
    }

    /**
     * Update the specified passport in storage.
     *
     * @param PassportRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(PassportRequest $request, $id)
    {
        $passport = $this->passport->findOrFail($id);

        $passport->update($request->all());

        $passport->attachToReservation($request->input('reservation_id'));

        return $this->response->item($passport, new PassportTransformer);
    }

    /**
     * Delete the specified passport from storage.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $passport = $this->passport->findOrFail($id);

        $passport->delete();

        return $this->response->noContent();
    }

    /**
     * Export passports.
     *
     * @param ExportRequest $request
     * @return mixed
     */
    public function export(ExportRequest $request)
    {
        $this->dispatch(new ExportPassports($request->all()));

        return $this->response()->created(null, [
            'message' => 'Report is being generated and will be available shortly.'
        ]);
    }

    /**
     * Import a list of passports.
     *
     * @param  PassportListImport $import
     * @return response
     */
    public function import(ImportRequest $request, PassportListImport $import)
    {
        $response = $import->handleImport();

        return $this->response()->created(null, $response);
    }
}
