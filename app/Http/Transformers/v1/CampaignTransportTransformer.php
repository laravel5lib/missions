<?php

namespace App\Http\Transformers\v1;

use App\CampaignTransport;
use League\Fractal\TransformerAbstract;

class CampaignTransportTransformer extends TransformerAbstract
{
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'departureHub', 'arrivalHub'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param CampaignTransport $transport
     * @return array
     * @internal param Campaign $campaign
     */
    public function transform(CampaignTransport $transport)
    {
        return [
            'id'          => $transport->id,
            'type'        => $transport->type,
            'vessel_no'   => $transport->vessel_no,
            'name'        => ucwords($transport->name),
            'domestic'    => (bool) $transport->domestic,
            'capacity'    => (int) $transport->capacity,
            'passengers'  => (int) $transport->passengers_count,
            'call_sign'   => strtoupper($transport->call_sign),
            'depart_at'   => $transport->depart_at ? $transport->depart_at->toDateTimeString() : null,
            'arrive_at'   => $transport->arrive_at ? $transport->arrive_at->toDateTimeString() : null,
            'created_at'  => $transport->created_at->toDateTimeString(),
            'updated_at'  => $transport->updated_at->toDateTimeString(),
            'deleted_at'  => $transport->deleted_at ? $transport->deleted_at->toDateTimeString() : null,
            'links'       => [
                [
                    'rel' => 'self',
                    'uri' => '/api/campaigns/' . $transport->campaign_id . 'transports/' . $transport->id,
                ]
            ]
        ];
    }

    /**
     * Include Departure Hub
     *
     * @param CampaignTransport $transport
     * @return \League\Fractal\Resource\Item
     * @internal param Campaign $campaign
     */
    public function includeDepartureHub(CampaignTransport $transport)
    {
        $hub = $transport->departureHub;

        if (! $hub) return null;

        return $this->item($hub, new HubTransformer);
    }

    /**
     * Include Arrival Hub
     *
     * @param CampaignTransport $transport
     * @return \League\Fractal\Resource\Item
     * @internal param Campaign $campaign
     */
    public function includeArrivalHub(CampaignTransport $transport)
    {
        $hub = $transport->arrivalHub;

        if (! $hub) return null;

        return $this->item($hub, new HubTransformer);
    }
}