<?php
namespace App;

use App\Models\v1\Trip;
use Dingo\Api\Contract\Http\Request;

class TripPromoHandler
{
    protected $trip;

    function __construct(Trip $trip)
    {
        $this->trip = $trip;
    }

    /**
     * @param Request $request
     * @return object
     */
    public function create($request)
    {
        $trip = $this->trip->findOrFail($request->get('promoteable_id'));

        $promos = $trip->promote(
            $request->get('name'),
            $request->get('qty', 1),
            $request->get('reward'),
            $request->get('expires_at'),
            $request->get('affiliates')
        );

        return $promos;
    }
}
