<?php

namespace Tests\Feature\Api\Reservation;

use App\Models\v1\Cost;
use App\Models\v1\Price;
use App\Models\v1\Requirement;
use App\Models\v1\Reservation;
use App\Models\v1\SquadMember;
use App\Models\v1\Trip;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransferReservationTest extends TestCase
{
    use RefreshDatabase;

   /** @test */
    public function transfers_reservation_to_different_trip()
    {
        $reservation = $this->setupReservation();
        $trip = $this->setupTrip();
        $room_price_uuid = $trip->priceables()->whereHas('cost', function ($q) {
            return $q->where('type', 'optional');
        })->first()->uuid;

        $this->assertEquals(2200, $reservation->getCurrentRate()->amount);
        $this->assertEquals(2, $reservation->todos()->count());
        $requirements = $reservation->requireables()->pluck('document_type')->toArray();
        $this->assertContains('passports', $requirements);
        $this->assertContains('medical_releases', $requirements);
        $this->assertContains('essays', $requirements); // custom requirement

        $this->json('POST', "/api/reservations/{$reservation->id}/transfer", [
            'trip_id' => $trip->id, 
            'desired_role' => 'MISS',
            'room_price_id' => $room_price_uuid
        ])
        ->assertStatus(201);

        // verify all prices
        $this->assertEquals(1800, $reservation->getCurrentRate()->amount);

        // verify all requirements
        $requirements = $reservation->fresh()->requireables()->pluck('document_type')->toArray();
        $this->assertContains('passports', $requirements);
        $this->assertContains('visas', $requirements);
        $this->assertContains('essays', $requirements); // custom requirement
        $this->assertNotContains('medical_releases', $requirements);
        
        // verify new tasks
        $this->assertEquals(4, $reservation->todos()->count());

        // verify removed from all squads
        $this->assertEmpty($reservation->squadMemberships->toArray());
        
    }

    private function setupReservation()
    {
        $trip = factory(Trip::class)->create([
            'todos' => ['send shirt', 'send wrist band']
        ]);
        
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
        $this->generatePrice($trip, $costs['flight'], 800);
        $this->generatePrice($trip, $costs['lateFee'], 200, today()->addYear()->toDateTimeString());

        $trip->addRequirement([
            'requirement_id' => factory(Requirement::class, 'passport')->create(['requester_id' => $trip->id])->id
        ]);
        $trip->addRequirement([
            'requirement_id' => factory(Requirement::class, 'medical')->create(['requester_id' => $trip->id])->id
        ]);

        $roomingPriceUuid = $this->generatePrice($trip, $costs['standardRoom'], 0)->uuid;

        $reservation = factory(Reservation::class)->create(['trip_id' => $trip->id]);
        $reservation->process($roomingPriceUuid);
        $reservation->addRequirement([
            'requirement_id' => factory(Requirement::class, 'testimony')->create(['requester_id' => $reservation->id])->id
        ]);

        factory(SquadMember::class)->create(['reservation_id' => $reservation->id]);

        return $reservation;
    }

    private function setupTrip()
    {
        $trip = factory(Trip::class)->create([
            'todos' => ['send shirt', 'send wrist band', 'send launch guide', 'send luggage tag'],
        ]);
        $costs = $this->costs();

        $this->generatePrice($trip, $costs['generalReg'], 2400, today()->addMonths(6)->toDateTimeString(), [
            ['percentage_due' => 50, 'due_at' => today()->addMonths(9)->toDateTimeString()],
            ['percentage_due' => 100, 'due_at' => today()->addYear()->toDateTimeString()],
        ]);
        $this->generatePrice($trip, $costs['earlyReg'], 1800, today()->startOfYear()->toDateTimeString(), [
            ['percentage_due' => 50, 'due_at' => today()->addMonth()->toDateTimeString()],
            ['percentage_due' => 100, 'due_at' => today()->addMonths(6)->toDateTimeString()],
        ]);
        $this->generatePrice($trip, $costs['deposit'], 100);
        $this->generatePrice($trip, $costs['flight'], 800);
        $this->generatePrice($trip, $costs['lateFee'], 200, today()->addYear()->toDateTimeString());
        $this->generatePrice($trip, $costs['standardRoom'], 0);

        $trip->addRequirement([
            'requirement_id' => factory(Requirement::class, 'passport')->create(['requester_id' => $trip->id])->id
        ]);
        $trip->addRequirement([
            'requirement_id' => factory(Requirement::class, 'visa')->create(['requester_id' => $trip->id])->id
        ]);

        return $trip;
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
