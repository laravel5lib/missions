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

    public function show($id)
    {
        $cost = Cost::findOrFail($id);

        return new CostResource($cost);
    }

    public function update(CostRequest $request, $id)
    {
        $cost = Cost::findOrFail($id);

        $cost->update($request->all());

        return new CostResource($cost);
    }

    public function destroy($id)
    {
        $cost = Cost::findOrFail($id);

        $cost->delete();

        return response()->json([], 204);
    }
}
