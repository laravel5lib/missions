<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\TransportRequest;
use App\Http\Transformers\v1\TransportTransformer;
use App\Models\v1\Transport;
use Illuminate\Http\Request;

use App\Http\Requests;

class TransportsController extends Controller
{

    /**
     * @var Transport
     */
    private $transport;

    /**
     * TransportsController constructor.
     * @param Transport $transport
     */
    public function __construct(Transport $transport)
    {
        $this->transport = $transport;
    }

    /**
     * Get a list of transports
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $transports = $this->transport->filter($request->all())
                        ->withCount('passengers')
                        ->paginate($request->get('per_page', 10));

        return $this->response->paginator($transports, new TransportTransformer);
    }

    /**
     * Create a new transport
     *
     * @param TransportRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(TransportRequest $request)
    {
        $transport = $this->transport
            ->withCount('passengers')
            ->firstOrCreate([
                'type' => $request->json('type'),
                'vessel_no' => strtoupper(
                    trim(
                        $request->json('vessel_no', 'unassigned')
                    )
                ),
                'name' => $request->json('name'),
                'call_sign' => $request->json('call_sign', null),
                'domestic' => $request->json('domestic'),
                'capacity' => $request->json('capacity'),
                'campaign_id' => $request->json('campaign_id')
            ]);

        if ($request->json('activity_id')) {
            $transport->activities()->sync([$request->json('activity_id')], false);
        }

        return $this->response->item($transport, new TransportTransformer);
    }

    /**
     * Get the specified transport
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $transport = $this->transport
            ->withCount('passengers')
            ->findOrFail($id);

        return $this->response->item($transport, new TransportTransformer);
    }

    /**
     * Update the specified transport
     *
     * @param $id
     * @param TransportRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function update($id, TransportRequest $request)
    {
        $transport = $this->transport
            ->withCount('passengers')
            ->findOrFail($id);

        $transport->update([
            'type' => $request->json('type', $transport->type),
            'vessel_no' => $request->json('vessel_no', $transport->vessel_no),
            'name' => $request->json('name', $transport->name),
            'call_sign' => $request->json('call_sign', $transport->call_sign),
            'domestic' => $request->json('domestic', $transport->domestic),
            'capacity' => $request->json('capacity', $transport->capacity),
            'campaign_id' => $request->json('campaign_id', $transport->campaign_id)
        ]);

        if ($request->json('activity_id')) {
            $transport->activities()->sync([
                $request->json('activity_id')
            ], false);
        }

        return $this->response->item($transport, new TransportTransformer);
    }

    /**
     * Delete the specified transport
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $transport = $this->transport->findOrFail($id);

        $transport->delete();

        return $this->response->noContent();
    }
}
