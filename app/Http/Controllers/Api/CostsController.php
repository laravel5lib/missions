<?php

namespace App\Http\Controllers\api;

use App\Models\v1\Cost;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CostResource;
use App\Http\Requests\v1\CostRequest;

class CostsController extends Controller
{
    /**
     * Get all costs
     *
     * @return App\Http\Resources\CostResource
     */
    public function index()
    {
        $costs = Cost::paginate();

        return CostResource::collection($costs);
    }
    
    /**
     * Create a new cost in storage
     *
     * @param CostRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CostRequest $request)
    {
        Cost::create($request->all());

        return response()->json(['message' => 'New cost created.'], 201);
    }

    /**
     * Get a specific cost
     *
     * @param string $id
     * @return App\Http\Resources\CostResource
     */
    public function show($id)
    {
        $cost = Cost::findOrFail($id);

        return new CostResource($cost);
    }

    /**
     * Update a specific cost in storage
     *
     * @param CostRequest $request
     * @param string $id
     * @return App\Http\Resources\CostResource
     */
    public function update(CostRequest $request, $id)
    {
        $cost = Cost::findOrFail($id);

        $cost->update($request->all());

        return new CostResource($cost);
    }

    /**
     * Delete a cost and remove from storage
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cost = Cost::findOrFail($id);

        $cost->delete();

        return response()->json([], 204);
    }
}
