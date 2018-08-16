<?php

namespace App\Http\Controllers\Api;

use Spatie\Tags\Tag;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use App\Http\Resources\TagResource;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;

class TagController extends Controller
{
    public function index()
    {
        $tags = QueryBuilder::for(Tag::class)
            ->allowedFilters([
                'name',
                Filter::exact('type')
            ])
            ->paginate(request()->input('per_page', 25));

        return TagResource::collection($tags);
    }
}
