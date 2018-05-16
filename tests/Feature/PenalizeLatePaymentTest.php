<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\v1\Cost;
use App\Models\v1\Trip;
use App\Models\v1\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PenalizeLatePaymentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function reservation_with_late_payment_defaults_to_latest_trip_rate()
    {
        $reservation = $this->setupReservation();
        
        $this->assertEquals(1800, $reservation->getCurrentRate()->amount);

        $reservation->processLatePayment();
        
        $this->assertEquals(2000, $reservation->getCurrentRate()->amount);
    }

    /** @test */
    public function reservation_locked_into_current_rate_does_not_change()
    {
        $reservation = $this->setupReservation();
        
        $reservation->lockCurrentRate();

        $this->assertEquals(1800, $reservation->getCurrentRate()->amount);

        $reservation->processLatePayment();
        
        $this->assertEquals(1800, $reservation->getCurrentRate()->amount);
    }

    private function setupReservation()
    {
        // create costs
        $genRegCost = factory(Cost::class)->create(['name' => 'General Reg', 'type' => 'incremental']);
        $earRegCost = factory(Cost::class)->create(['name' => 'Early Reg', 'type' => 'incremental']);

        // create trip
        $trip = factory(Trip::class)->create();

        // add prices to trip
        $genRegPrice = $trip->addPrice([
            'cost_id' => $genRegCost->id,
            'amount' => 2000,
            'active_at' => today()->subDay(1)->toDateTimeString(),
            'payments' => [
                [
                    'percentage_due' => 50, 
                    'due_at' => today()->addMonths(6)->endOfDay()->toDateTimeString(), 
                    'grace_days' => 3
                ],
                [
                    'percentage_due' => 100, 
                    'due_at' => today()->addYear()->endOfDay()->toDateTimeString(),
                    'grace_days' => 3 
                ]
            ]
        ]);
        $earRegPrice = $trip->addPrice([
            'cost_id' => $earRegCost->id,
            'amount' => 1800,
            'active_at' => today()->subMonths(6)->endOfDay()->toDateTimeString(),
            'payments' => [
                [
                    'percentage_due' => 50, 
                    'due_at' => today()->subMonths(3)->toDateTimeString(), 
                    'grace_days' => 3
                ],
                [
                    'percentage_due' => 100, 
                    'due_at' => today()->subDays(4)->toDateTimeString(), 
                    'grace_days' => 3
                ]
            ]
        ]);

        // create reservation for trip
        $reservation = factory(Reservation::class)->create(['trip_id' => $trip->id]);

        // add early reg to reservation
        $reservation->attachPriceToModel($earRegPrice->id);

        return $reservation;
    }
}
