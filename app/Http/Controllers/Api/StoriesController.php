<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\v1\StoryRequest;
use App\Http\Transformers\v1\StoryTransformer;
use App\Models\v1\Story;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StoriesController extends Controller
{

    /**
     * @var Story
     */
    private $story;

    /**
     * StoriesController constructor.
     * @param Story $story
     */
    public function __construct(Story $story)
    {
        $this->story = $story;
    }

    /**
     * Get a list of stories.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $stories = $this->story
            ->filter($request->all())
            ->paginate($request->get('per_page', 10));

        return $this->response->paginator($stories, new StoryTransformer);
    }

    /**
     * Get the specified story.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $story = $this->story->findOrFail($id);

        return $this->response->item($story, new StoryTransformer);
    }

    /**
     * Create a new story.
     *
     * @param StoryRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(StoryRequest $request)
    {
        $story = $this->story->create($request->all());

        return $this->response->item($story, new StoryTransformer);
    }

    /**
     * Update the specified story.
     *
     * @param StoryRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(StoryRequest $request, $id)
    {
        $story = $this->story->findOrFail($id);

        $story->update($request->all());

        return $this->response->item($story, new StoryTransformer);
    }

    /**
     * Delete the specified story.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $story = $this->story->findOrFail($id);

        $story->delete();

        return $this->response->noContent();
    }
}
