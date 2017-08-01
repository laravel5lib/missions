<?php
namespace App;

use App\Models\v1\Campaign;
use Dingo\Api\Contract\Http\Request;

class CampaignPromoHandler
{
    protected $campaign;

    function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * @param Request $request
     * @return object
     */
    public function create($request)
    {
        $campaign = $this->campaign->findOrFail($request->get('promoteable_id'));

        $promos = $campaign->promote(
            $request->get('name'),
            $request->get('qty', 1),
            $request->get('reward'),
            $request->get('expires_at'),
            $request->get('affiliates')
        );

        return $promos;
    }
}
