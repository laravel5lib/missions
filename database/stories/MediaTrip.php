<?php

use App\Models\v1\Trip;
use App\Models\v1\Requirement;

class MediaTrip extends BaseTrip
{
    /**
     * Here you can create your complex model factory
     * logic
     *
     * @param array $params Array of params
     *
     * @return Mixed
     */
    public function build($params = [])
    {
        $trip = factory(Trip::class, 'media')->create($params);

        $this->add_facilitators($trip);

        // add travel costs
        $late = $this->add_incremental_costs($trip);
        $this->add_static_costs($trip);
        $this->add_optional_costs($trip, $late);

        $this->add_trip_requirements($trip);

        return $trip;
    }

    protected function add_trip_requirements($trip)
    {
        $requirements = collect([
            factory(Requirement::class, 'passport')->make(['requester_id' => $trip->id]),
            factory(Requirement::class, 'medical')->make(['requester_id' => $trip->id]),
            factory(Requirement::class, 'referral')->make(['requester_id' => $trip->id]),
            factory(Requirement::class, 'testimony')->make(['requester_id' => $trip->id]),
            factory(Requirement::class, 'arrival')->make(['requester_id' => $trip->id]),
            factory(Requirement::class, 'travel-itinerary')->make(['requester_id' => $trip->id]),
            factory(Requirement::class, 'media-credentials')->make(['requester_id' => $trip->id]),
        ])->toArray();

        Requirement::insert($requirements);
    }
}
