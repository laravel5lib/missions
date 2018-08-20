<?php

namespace App\Http\Controllers\Api;

use Spatie\Tags\Tag;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use App\Http\Resources\TagResource;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Requests\CreateTagRequest;
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
            ->when($type, function ($query) use ($type) {
                return $query->where('type', $type);
            })
            ->paginate(request()->input('per_page', 25));

        return TagResource::collection($tags);
    }

    public function store(CreateTagRequest $request, $type)
    {
        $tag = Tag::create(['name' => $request->input('name'), 'type' => $type]);

        return response()->json(['message' => 'New tag created.'], 201);
    }

    public function update(UpdateTagRequest $request, $type, $id)
    {
        $tag = Tag::whereType($type)->findOrFail($id);

        $tag->update($request->all());

        return new TagResource($tag);
    }

    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);

        $tag->delete();

        return response()->json([], 204);
    }
}
