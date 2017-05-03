<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Transport;
use League\Fractal\TransformerAbstract;

class TransportTransformer extends TransformerAbstract {

    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'campaign'
    ];

    /**
     * Transform the object into a basic array
     *
     * @param Transport $transport
     * @return array
     */
    public function transform(Transport $transport)
    {
        return [
            'id'          => $transport->id,
            'campaign_id' => $transport->campaign_id,
            'type'        => $transport->type,
            'vessel_no'   => $transport->vessel_no,
            'name'        => $transport->name,
            'domestic'    => (bool) $transport->domestic,
            'capacity'    => (int) $transport->capacity,
            'call_sign'   => $transport->call_sign,
            'created_at'  => $transport->created_at->toDateTimeString(),
            'updated_at'  => $transport->updated_at->toDateTimeString(),
            'links'       => [
                [
                    'rel' => 'self',
                    'uri' => '/api/transports/' . $transport->id,
                ]
            ]
        ];
    }

    /**
     * Include Campaign
     *
     * @param Transport $transport
     * @return \League\Fractal\Resource\Item
     */
    public function includeCampaign(Transport $transport)
    {
        $campaign = $transport->campaign;

        return $this->item($campaign, new CampaignTransformer);
    }
}