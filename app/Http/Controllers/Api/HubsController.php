<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Models\v1\Hub;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Transformers\v1\HubTransformer;

class HubsController extends Controller
{   
    private $hub;

    function __construct(Hub $hub)
    {
        $this->hub = $hub;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hubs = $this->hub->paginate($request->get('per_page', 10));

        return $this->response->paginator($hubs, new HubTransformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $hub = $this->hub->create($request->all());

        return $this->response->item($hub, new HubTransformer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
