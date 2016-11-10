<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\v1\EssayRequest;
use App\Http\Transformers\v1\EssayTransformer;
use App\models\v1\Essay;
use App\Http\Controllers\Controller;
use Dingo\Api\Contract\Http\Request;

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
}
