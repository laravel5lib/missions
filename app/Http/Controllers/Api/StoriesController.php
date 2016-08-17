<?php

namespace App\Http\Controllers\Api;

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

    public function index(Request $request)
    {
        $stories = $this->story
            ->filter($request->all())
            ->paginate($request->get('per_page', 10));

        return $this->response->paginator($stories, new StoryTransformer);
    }
}
