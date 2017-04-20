<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Models\v1\Hub;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\HubRequest;
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
    public function index(Request $request)
    {
        $hubs = $this->hub
            ->filter($request->all())
            ->paginate($request->get('per_page', 10));

        return $this->response->paginator($hubs, new HubTransformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HubRequest $request)
    {
        $hub = $this->hub->create([
            'campaign_id' => $request->json('campaign_id'),
            'name' => $request->json('name'),
            'call_sign' => $request->json('call_sign'),
            'address' => $request->json('address'),
            'city' => $request->json('city'),
            'state' => $request->json('state'),
            'zip' => $request->json('zip'),
            'country_code' => $request->json('country_code')
        ]);

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
        $hub = $this->hub->findOrFail($id);

        return $this->response->item($hub, new HubTransformer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HubRequest $request, $id)
    {
        $hub = $this->hub->findOrFail($id);

        $hub->update([
            'campaign_id' => $request->json('campaign_id', $hub->campaign_id),
            'name' => $request->json('name', $hub->name),
            'call_sign' => $request->json('call_sign', $hub->call_sign),
            'address' => $request->json('address', $hub->address),
            'city' => $request->json('city', $hub->city),
            'state' => $request->json('state', $hub->state),
            'zip' => $request->json('zip', $hub->zip),
            'country_code' => $request->json('country_code', $hub->country_code)
        ]);

        return $this->response->item($hub, new HubTransformer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $hub = $this->hub->findOrFail($id);

         $hub->delete();

         return $this->response->noContent();
    }
}
