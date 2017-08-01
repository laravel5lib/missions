<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\v1\Visa;
use Dingo\Api\Contract\Http\Request;
use App\Http\Requests\v1\VisaRequest;
use App\Http\Transformers\v1\VisaTransformer;
use App\Http\Requests\v1\ExportRequest;
use App\Jobs\ExportVisas;
use App\Http\Requests\v1\ImportRequest;
use App\Services\Importers\VisaListImport;

class VisasController extends Controller
{
    /**
     * @var Visa
     */
    private $visa;

    /**
     * VisasController constructor.
     *
     * @param Visa $visa
     */
    public function __construct(Visa $visa)
    {
        $this->visa = $visa;
    }

    /**
     * Get all visas.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $visas = $this->visa
                    ->filter($request->all())
                    ->paginate($request->get('per_page', 10));

        return $this->response->paginator($visas, new VisaTransformer);
    }

    /**
     * Get the specified visa.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $visa = $this->visa->findOrFail($id);

        return $this->response->item($visa, new VisaTransformer);
    }

    /**
     * Create a new visa and save in storage.
     *
     * @param VisaRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(VisaRequest $request)
    {
        $visa = $this->visa->create($request->all());

        return $this->response->item($visa, new VisaTransformer);
    }

    /**
     * Update the specified visa in storage.
     *
     * @param VisaRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(VisaRequest $request, $id)
    {
        $visa = $this->visa->findOrFail($id);

        $visa->update($request->all());

        return $this->response->item($visa, new VisaTransformer);
    }

    /**
     * Remove the specified visa from storage.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $visa = $this->visa->findOrFail($id);

        $visa->delete();

        return $this->response->noContent();
    }

    /**
     * Export Visas.
     *
     * @param ExportRequest $request
     * @return mixed
     */
    public function export(ExportRequest $request)
    {
        $this->dispatch(new ExportVisas($request->all()));

        return $this->response()->created(null, [
            'message' => 'Report is being generated and will be available shortly.'
        ]);
    }

    /**
     * Import a list of Visas.
     *
     * @param  VisaListImport $import
     * @return response
     */
    public function import(ImportRequest $request, VisaListImport $import)
    {
        $response = $import->handleImport();

        return $this->response()->created(null, $response);
    }
}
