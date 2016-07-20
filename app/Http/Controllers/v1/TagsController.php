<?php

namespace App\Http\Controllers\v1;

use App\Models\v1\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\TagRequest;
use App\Http\Transformers\v1\TagTransformer;

class TagsController extends Controller
{

    /**
     * @var Tag
     */
    private $tag;

    /**
     * Instantiate a new UsersController instance.
     * @param Tag $tag
     */
    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }


    /**
     * Show all tags.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $tags = $this->tag
            ->filter($request->all())
            ->paginate($request->get('per_page', 10));

        return $this->response->paginator($tags, new TagTransformer);
    }

    /**
     * Show the specified tag.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $tag = $this->tag->findOrFail($id);

        return $this->response->item($tag, new TagTransformer);
    }

    /**
     * Create a new tag.
     *
     * @param TagRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(TagRequest $request)
    {
        $tag = $this->tag->create($request->all());

        return $this->response->item($tag, new TagTransformer);
    }

    /**
     * Update a tag.
     *
     * @param TagRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(TagRequest $request, $id)
    {
        $tag = $this->tag->findOrFail($id);

        $tag->update($request->all());

        return $this->response->item($tag, new TagTransformer);
    }

    /**
     * Delete a tag.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $tag = $this->tag->findOrFail($id);

        $tag->delete();

        return $this->response->noContent();
    }
}
