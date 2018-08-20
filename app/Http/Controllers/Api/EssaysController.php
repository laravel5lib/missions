<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\Essay;
use App\Jobs\ExportEssays;
use App\Http\Controllers\Controller;
use Dingo\Api\Contract\Http\Request;
use App\Http\Requests\v1\EssayRequest;
use App\Http\Requests\v1\ExportRequest;
use App\Http\Requests\v1\ImportRequest;
use App\Services\Importers\EssayListImport;
use App\Http\Transformers\v1\EssayTransformer;

class EssaysController extends Controller
{

    /**
     * @var Essay
     */
    private $essay;

    /**
     * EssaysController constructor.
     * @param Essay $essay
     */
    public function __construct(Essay $essay)
    {
        $this->essay = $essay;
    }

    /**
     * Get all essays.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $essays = $this->essay
                       ->when($request->segment(2) === 'essays', function ($query) {
                            return $query->where('subject', 'Testimony');
                       })
                       ->when($request->segment(2) === 'influencer-applications', function ($query) {
                            return $query->where('subject', 'Influencer');
                       })
                       ->filter($request->all())
                       ->paginate($request->get('per_page', 10));

        return $this->response->paginator($essays, new EssayTransformer);
    }

    /**
     * Get an essay by id.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $essay = $this->essay->findOrFail($id);

        return $this->response->item($essay, new EssayTransformer);
    }

    /**
     * Create a new essay.
     *
     * @param EssayRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(EssayRequest $request)
    {
        $essay = $this->essay->create([
            'author_name' => $request->get('author_name'),
            'subject'     => $request->get('subject'),
            'content'     => $request->get('content'),
            'user_id'     => $request->get('user_id')
        ]);

        if ($request->has('upload_ids')) {
            $essay->uploads()->sync($request->get('upload_ids'));
        }

        return $this->response->item($essay, new EssayTransformer);
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
        $essay = $this->essay->findOrFail($id);

        $essay->update([
            'author_name' => $request->get('author_name'),
            'subject'     => $request->get('subject'),
            'content'     => $request->get('content'),
            'user_id'     => $request->get('user_id')
        ]);

        if ($request->has('upload_ids')) {
            $essay->uploads()->sync($request->get('upload_ids'));
        }

        return $this->response->item($essay, new EssayTransformer);
    }

    /**
     * Delete an essay.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $essay = $this->essay->findOrFail($id);

        $essay->delete();

        return $this->response->noContent();
    }

    /**
     * Export essays.
     *
     * @param ExportRequest $request
     * @return mixed
     */
    public function export(ExportRequest $request)
    {
        $this->dispatch(new ExportEssays($request->all()));

        return $this->response()->created(null, [
            'message' => 'Report is being generated and will be available shortly.'
        ]);
    }

    /**
     * Import essays.
     *
     * @param  ImportRequest   $request
     * @param  EssayListImport $import
     * @return response
     */
    public function import(ImportRequest $request, EssayListImport $import)
    {
        $response = $import->handleImport();

        return $this->response()->created(null, $response);
    }
}
