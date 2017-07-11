<?php

namespace App\Http\Controllers\Api;

use App\CampaignTransport;
use App\Http\Requests\v1\CampaignTransportRequest;
use App\Http\Transformers\v1\CampaignTransportTransformer;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CampaignTransportsController extends Controller
{

    /**
     * @var CampaignTransport
     */
    private $transport;

    /**
     * CampaignTransportsController constructor.
     * @param CampaignTransport $transport
     */
    public function __construct(CampaignTransport $transport)
    {
        $this->transport = $transport;
    }

    public function index($campaignId)
    {
        $transports = $this->transport
            ->whereCampaignId($campaignId)
            ->filter(request()->all())
            ->withCount('passengers')
            ->paginate(request()->get('per_page', 25));

        return $this->response->paginator($transports, new CampaignTransportTransformer);
    }

    public function show($campaignId, $id)
    {
        $transport = $this->transport
            ->whereCampaignId($campaignId)
            ->withCount('passengers')
            ->findOrFail($id);

        return $this->response->item($transport, new CampaignTransportTransformer);
    }

    public function store($campaignId, CampaignTransportRequest $request)
    {
        $this->createOrRetrieveHubs($request, $campaignId);

        $vessel_no = $request->get('vessel_no') ?
            $this->formatVesselNo(
                $request->get('vessel_no'),
                $request->get('call_sign')
            ) :
            null;

        $transport = $this->transport->firstOrCreate([
            'campaign_id' => $campaignId,
            'type' => $request->get('type'),
            'name' => $request->get('name'),
            'vessel_no' => $vessel_no,
            'capacity' => $request->get('capacity'),
            'call_sign' => $request->get('call_sign'),
            'depart_at' => $request->get('depart_at'),
            'arrive_at' => $request->get('arrive_at'),
            'departure_hub_id' => $request->get('departure_hub_id'),
            'arrival_hub_id' => $request->get('arrival_hub_id')
        ]);

        return $this->response->item($transport, new CampaignTransportTransformer);
    }

    public function update($campaignId, $id, CampaignTransportRequest $request)
    {
        $this->createOrRetrieveHubs($request, $campaignId);

        $transport = $this->transport
            ->whereCampaignId($campaignId)
            ->findOrFail($id);

        $vessel_no = $this->formatVesselNo(
            $request->get('vessel_no', $transport->vessel_no),
            $request->get('call_sign', $transport->call_sign)
        );

        $transport->update([
            'campaign_id' => $campaignId,
            'type' => $request->get('type', $transport->type),
            'name' => $request->get('name', $transport->name),
            'vessel_no' => $vessel_no,
            'capacity' => $request->get('capacity'),
            'call_sign' => $request->get('call_sign', $transport->call_sign),
            'depart_at' => $request->get('depart_at', $transport->depart_at),
            'arrive_at' => $request->get('arrive_at', $transport->arrive_at),
            'departure_hub_id' => $request->get('departure_hub_id'),
            'arrival_hub_id' => $request->get('arrival_hub_id')
        ]);

        $transport = $transport->fresh()->withCount('passengers');

        return $this->response->item($transport, new CampaignTransportTransformer);
    }

    private function createOrRetrieveHubs($request, $campaignId)
    {
        if ($request->has('departure')) {
            $data = array_merge(
                $request->get('departure'),
                ['campaign_id' => $campaignId]
            );
            $request->request->add(['departure' => ['campaign_id' => $campaignId]]);

            $response = $this->api->json($data)->post('/hubs');

            $request->request->add(['departure_hub_id' => $response->id]);
        }

        if ($request->has('arrival')) {
            $data = array_merge(
                $request->get('arrival'),
                ['campaign_id' => $campaignId]
            );

            $response = $this->api->json($data)->post('/hubs');

            $request->request->add(['arrival_hub_id' => $response->id]);
        }
    }

    private function formatVesselNo($number, $callSign = null)
    {
        $number = addLeadingZeros( removeNonNumericCharacters($number) );

        return strtoupper($callSign) . $number;
    }

    public function destroy($campaignId, $id)
    {
        $transport = $this->transport
            ->whereCampaignId($campaignId)
            ->findOrFail($id);

        $transport->delete();

        return $this->response->noContent();
    }
}
