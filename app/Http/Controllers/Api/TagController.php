<?php

namespace App\Http\Controllers\Api;

use Spatie\Tags\Tag;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use App\Http\Resources\TagResource;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Requests\UpdateTagRequest;

class TagController extends Controller
{
    public function index($type = null)
    {
        $tags = QueryBuilder::for(Tag::class)
            ->allowedFilters([
                'name',
                Filter::exact('type')
            ])
            ->paginate(request()->input('per_page', 25));

        return TagResource::collection($tags);
    }

    public function update(UpdateTagRequest $request, $type, $id)
    {
        $tag = Tag::whereType($type)->findOrFail($id);

        $tag->update($request->all());

        return new TagResource($tag);
    }
}
