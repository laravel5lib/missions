<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\v1\Cost;
use App\Models\v1\Trip;
use App\Models\v1\Price;
use App\Models\v1\Payment;
use App\Models\v1\Requirement;
use App\Models\v1\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function add_requirements_based_on_rules()
    {
        $reservation = factory(Reservation::class)->create([
            'birthday' => today()->subYears(50)->toDateString(),
            'desired_role' => 'MEDI'
        ]);
        $mediaCredential = factory(Requirement::class, 'media-credentials')->create([
            'rules' => ['roles' => ['MEDI']],
            'requester_id' => $reservation->trip->id
        ]);
        $minorRelease = factory(Requirement::class)->create([
            'rules' => ['age' => 18],
            'requester_id' => $reservation->trip->id
        ]);

        $reservation->addRequirementToReservation($mediaCredential);
        $reservation->addRequirementToReservation($minorRelease);

        $ids = $reservation->requireables->pluck('id')->toArray();
        $this->assertContains($mediaCredential->id, $ids);
        $this->assertNotContains($minorRelease->id, $ids);
    }

    /** @test */
    public function adds_trip_prices_to_reservation()
    {
        $trip = $this->setupTripWithPricing();
        $reservation = factory(Reservation::class)->create(['trip_id' => $trip->id]);
        $roomingPriceUuid = $this->generatePrice($trip, $this->costs()['standardRoom'], 0)->uuid;
        $this->assertEquals(7, $trip->priceables()->count());
    
        $reservation->process($roomingPriceUuid);

        $costs = $reservation->priceables()
            ->with('cost')
            ->get()
            ->pluck('cost.name')
            ->toArray();

        $this->assertEquals(4, count($costs));
        $this->assertContains('Early Reg', $costs);
        $this->assertContains('Standard Room', $costs);
        $this->assertContains('Deposit', $costs);
        $this->assertContains('Flight', $costs);
    }

    private function setupTripWithPricing()
    {
        $trip = factory(Trip::class)->create();

        $this->setupTripPricing($trip);

        return $trip;
    }

    private function setupTripPricing($trip)
    {
        $costs = $this->costs();

        $this->generatePrice($trip, $costs['generalReg'], 2400, today()->addMonths(6)->toDateTimeString(), [
            ['percentage_due' => 50, 'due_at' => today()->addMonths(9)->toDateTimeString()],
            ['percentage_due' => 100, 'due_at' => today()->addYear()->toDateTimeString()],
        ]);

        $this->generatePrice($trip, $costs['earlyReg'], 2200, today()->startOfYear()->toDateTimeString(), [
            ['percentage_due' => 50, 'due_at' => today()->addMonth()->toDateTimeString()],
            ['percentage_due' => 100, 'due_at' => today()->addMonths(6)->toDateTimeString()],
        ]);

        $this->generatePrice($trip, $costs['deposit'], 100);
        $this->generatePrice($trip, $costs['standardRoom'], 0);
        $this->generatePrice($trip, $costs['flight'], 800);
        $this->generatePrice($trip, $costs['lateFee'], 200, today()->addYear()->toDateTimeString());
    }

    private function generatePrice($trip, $cost, $amount, $date = null, $payments = [])
    {
        $price = factory(Price::class)->create([
            'cost_id' => $cost->id,
            'model_id' => $trip->id, 
            'model_type' => 'trips', 
            'amount' => $amount,
            'active_at' => $date
        ]);
        
        if ($payments <> []) {

            foreach($payments as $payment) 
            {
                $price->payments()->create([
                    'price_id' => $price, 
                    'percentage_due' => $payment['percentage_due'],
                    'due_at' => $payment['due_at']
                ]);
            }

        }

        $trip->attachPriceToModel($price->id);

        return $price;
    }

    private function costs()
    {
        return [
            'generalReg'   => factory(Cost::class)->create(['name' => 'General Reg', 'type' => 'incremental']),
            'earlyReg'     => factory(Cost::class)->create(['name' => 'Early Reg', 'type' => 'incremental']),
            'standardRoom' => factory(Cost::class)->create(['name' => 'Standard Room', 'type' => 'optional']),
            'doubleRoom'   => factory(Cost::class)->create(['name' => 'Double Room', 'type' => 'optional']),
            'deposit'      => factory(Cost::class)->create(['name' => 'Deposit', 'type' => 'upfront']),
            'flight'       => factory(Cost::class)->create(['name' => 'Flight', 'type' => 'static']),
            'lateFee'      => factory(Cost::class)->create(['name' => 'Late Fee', 'type' => 'fee'])
        ];
    }
}
