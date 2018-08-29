<?php

namespace App;

use App\Models\v1\Trip;
use App\Models\v1\Reservation;
use Illuminate\Support\Facades\DB;

class TransferReservation
{
    protected $reservation;
    
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function toTrip($tripId, $options = ['desired_role' => 'MISS'])
    {
        $trip = Trip::findOrFail($tripId);

        DB::transaction(function () use ($trip, $options) {
            $this->swapPricing($trip);

            if (isset($options['room_price_id'])) 
                $this->addNewRoomPrice($options['room_price_id']);

            $this->swapRequirements($trip);
            
            $this->addNewTasks($trip);
            
            // remove all squad assignments
            $this->reservation->squadMemberships()->delete();
            
            // change trip and desired role
            $this->reservation->update([
                'desired_role' => $options['desired_role'],
                'trip_id' => $trip->id
            ]);
        });
    }

    public function swapPricing($trip)
    {
        $this->removeInheritedPrices();
        $this->addNewTripPrices($trip);
    }

    public function addNewRoomPrice($priceUuid)
    {
        $this->reservation->addPrice(['price_id' => $priceUuid]);
    }
    
    public function swapRequirements($trip)
    {
        // remove existing
        $this->reservation->requireables()->delete();
        // add new
        $this->reservation->syncRequirements($trip->requireables);
    }

    public function addNewTasks($trip)
    {
        $existingTasks = $this->reservation->todos()->pluck('task')->toArray();
        
        $tasks = array_diff($trip->todos ?? [], $existingTasks);
        foreach($tasks as $task)
        {
            $this->reservation->todos()->create(['task' => $task]);
        }
    }

    private function removeInheritedPrices()
    {
        $this->reservation->priceables()
            ->where('model_id', '<>', $this->reservation->id)
            ->where('model_type', '<>', 'reservations')
            ->detach();
    }

    private function addNewTripPrices(Trip $trip)
    {
        $costIds = $this->getCustomPricingCostIds();

        $this->addNewRegistrationRate($trip, $costIds);
        $this->addOtherPrices($trip, $costIds);
    }

    private function getCustomPricingCostIds()
    {
        return $this->reservation->prices()->with('cost')->get()->pluck('cost.id')->toArray();
    }

    private function addNewRegistrationRate(Trip $trip, $costIds = [])
    {
        $currentTripRate = $trip->getCurrentRate();
        
        if (! in_array($currentTripRate->cost_id, $costIds)) {
            $this->reservation->attachPriceToModel($currentTripRate->id);
        }
    }

    private function addOtherPrices(Trip $trip, $costIds = [])
    {
        // attach static and upfront trip prices that don't already exist on the reservation
        $trip->priceables()
             ->whereHas('cost', function ($q) use ($costIds) {
                return $q->whereNotIn('id', $costIds)
                         ->where('type', 'static')
                         ->orWhere('type', 'upfront');
            })
            ->get()
            ->each(function ($price) {
                $this->reservation->attachPriceToModel($price->id);
            });
    }
}