<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Dingo\Api\Contract\Http\Request;
use App\Http\Requests\v1\PassportRequest;
use App\Http\Transformers\v1\PassportTransformer;
use App\Models\v1\Passport;
use App\Http\Requests\v1\ExportRequest;
use App\Jobs\ExportPassports;
use App\Http\Requests\v1\ImportRequest;
use App\Services\Importers\PassportListImport;

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
     * Get all passports.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $passports = $this->passport
                        ->filter($request->all())
                        ->paginate($request->get('per_page', 10));

        return $this->response->paginator($passports, new PassportTransformer);
    }

    /**
     * Get the specified passport.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $passport = $this->passport->findOrFail($id);

        return $this->response->item($passport, new PassportTransformer);
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
