<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\Lead;
use Illuminate\Http\Request;
use App\Http\Requests\LeadRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\LeadResource;
use Spatie\QueryBuilder\QueryBuilder;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leads = QueryBuilder::for(Lead::class)
            ->allowedFilters('category_id')
            ->latest()
            ->paginate(25);
        
        return LeadResource::collection($leads);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LeadRequest $request)
    {
        Lead::create($request->all());
        
        return response()->json(['message' => 'Lead created'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lead = Lead::whereUuid($id)->firstOrFail();

        return new LeadResource($lead);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lead = Lead::whereUuid($id)->firstOrFail();

        $lead->delete();

        return response()->json(['message' => 'Lead deleted'], 204);
    }
}
