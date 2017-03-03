<?php

use App\Models\v1\Cost;
use App\Models\v1\Fund;
use App\Models\v1\Payment;
use App\Models\v1\Fundraiser;
use App\Models\v1\Reservation;
use App\Models\v1\Transaction;

class ReservationTest extends TestCase
{
    function setup_reservation()
    {
        $reservation = factory(Reservation::class)->create();
        $fund = factory(Fund::class)->create([
            'fundable_type' => 'reservations', 'fundable_id' => $reservation->id,
            'balance' => 200
        ]);
        factory(Fundraiser::class)->create([
            'fund_id' => $fund->id, 'sponsor_id' => $reservation->user->id,
            'goal_amount' => 0
        ]);
        $general = factory(Cost::class, 'general')->create([
            'cost_assignable_id' => $reservation->trip->id,
            'amount' => 2700
        ]);
        $general->payments()->create([
            'cost_id' => $general->id,
            'amount_owed' => ($general->amount/100)/2,
            'percent_owed' => 50,
            'upfront' => false,
        ]);
        $general->payments()->create([
            'cost_id' => $general->id,
            'amount_owed' => ($general->amount/100)/2,
            'percent_owed' => 50,
            'upfront' => false,
        ]);

        $deposit = factory(Cost::class, 'deposit')->create([
            'cost_assignable_id' => $reservation->trip->id,
            'amount' => 100
        ]);
        $deposit->payments()->create([
            'amount_owed' => $deposit->amount/100,
            'percent_owed' => 100,
            'upfront' => true
        ]);

        $costs = collect([$general, $deposit]);

        $reservation->syncCosts($costs);

        // factory(Transaction::class, 2)->create([
        //     'fund_id' => $reservation->fund->id,
        //     'amount' => 100,
        //     'donor_id' => $reservation->user->id
        // ]);

        // $reservation->fund->reconcile();

        return $reservation;
    }

    /** 
     * @test
     */
    function can_sync_costs_and_return_totals()
    {
        $reservation = $this->setup_reservation();

        $this->assertSame(280000, $reservation->getTotalCost());
        $this->assertSame('2800.00', $reservation->totalCostInDollars());
        $this->assertSame(280000, $reservation->fund->fundraisers()->first()->goal_amount);
        $this->assertSame(20000, $reservation->getTotalRaised());
        $this->assertSame('200.00', $reservation->totalRaisedInDollars());
        $this->assertSame(7, $reservation->getPercentRaised());
        $this->assertSame(260000, $reservation->getTotalOwed());
        $this->assertSame('2600.00', $reservation->totalOwedInDollars());
    }

}
