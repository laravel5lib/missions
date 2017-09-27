<?php

namespace App\Http\Controllers\Api\Medical;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Medical\ReleaseRequest;
use App\Http\Transformers\v1\MedicalReleaseTransformer;
use App\Models\v1\MedicalRelease;
use Dingo\Api\Contract\Http\Request;
use App\Http\Requests\v1\ExportRequest;
use App\Jobs\ExportMedicalReleases;
use App\Http\Requests\v1\ImportRequest;
use App\Services\Importers\MedicalReleaseListImport;

class ReleasesController extends Controller
{

    /**
     * @var MedicalRelease
     */
    private $release;

    /**
     * ReleasesController constructor.
     * @param MedicalRelease $release
     */
    public function __construct(MedicalRelease $release)
    {
        $this->release = $release;
    }

    /**
     * Get all medical releases.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $releases = $this->release
                        ->filter($request->all())
                        ->paginate($request->get('per_page', 10));

        return $this->response->paginator($releases, new MedicalReleaseTransformer);
    }

    /**
     * Get the specified medical release.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $release = $this->release->findOrFail($id);

        return $this->response->item($release, new MedicalReleaseTransformer);
    }

    /**
     * Create a new medical release and save in storage.
     *
     * @param ReleaseRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(ReleaseRequest $request)
    {
        if (! empty($request->get('conditions'))) {
            $request->merge(['is_risk' => true]);
        }

        $release = $this->release->create($request->all());

        $release->syncConditions($request->get('conditions'));

        $release->syncAllergies($request->get('allergies'));

        if ($request->has('upload_ids')) {
            $release->uploads()->sync($request->get('upload_ids'));
        }

        return $this->response->item($release, new MedicalReleaseTransformer);
    }

    /**
     * Update the specified medical release in storage.
     *
     * @param ReleaseRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(ReleaseRequest $request, $id)
    {
        if (! empty($request->get('conditions'))) {
            $request->merge(['is_risk' => true]);
        }

        $release = $this->release->findOrFail($id);

        $release->update($request->all());

        $release->syncConditions($request->get('conditions'));

        $release->syncAllergies($request->get('allergies'));

        if ($request->has('upload_ids')) {
            $release->uploads()->sync($request->get('upload_ids'));
        }

        return $this->response->item($release, new MedicalReleaseTransformer);
    }

    /**
     * Remove the specified medical release from storage.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $release = $this->release->findOrFail($id);

        $release->delete();

        return $this->response->noContent();
    }

    /**
     * Export MedicalReleases.
     *
     * @param ExportRequest $request
     * @return mixed
     */
    public function export(ExportRequest $request)
    {
        $this->dispatch(new ExportMedicalReleases($request->all()));

        return $this->response()->created(null, [
            'message' => 'Report is being generated and will be available shortly.'
        ]);
    }

    /**
     * Import a list of MedicalReleases.
     *
     * @param  MedicalReleaseListImport $import
     * @return response
     */
    public function import(ImportRequest $request, MedicalReleaseListImport $import)
    {
        $response = $import->handleImport();

        return $this->response()->created(null, $response);
    }
}
