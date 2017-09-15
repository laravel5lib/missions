<?php

use App\Models\v1\Trip;

class FamilyTrip extends BaseTrip
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
        $trip = factory(Trip::class, 'family')->create($params);

        $this->add_facilitators($trip);

        // add travel costs
        $late = $this->add_incremental_costs($trip);
        $this->add_static_costs($trip);
        $this->add_optional_costs($trip, $late);

        $this->add_trip_requirements($trip);
    }
}