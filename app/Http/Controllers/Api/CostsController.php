<?php

namespace App\Http\Controllers\api;

use App\Models\v1\Cost;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CostResource;
use App\Http\Requests\v1\CostRequest;

class CostsController extends Controller
{
    public function index()
    {
        $costs = Cost::paginate();

        return CostResource::collection($costs);
    }
    
    public function store(CostRequest $request)
    {
        Cost::create($request->all());

        return response()->json(['message' => 'New cost created.'], 201);
    }
}
