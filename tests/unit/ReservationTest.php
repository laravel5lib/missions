<?php

use Carbon\Carbon;
use App\Models\v1\Cost;
use App\Models\v1\Fund;
use App\Models\v1\Team;
use App\Models\v1\Trip;
use App\Models\v1\Payment;
use App\Models\v1\Campaign;
use App\Models\v1\Deadline;
use App\Models\v1\TeamSquad;
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

        return $reservation;
    }

    /** @test */
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

    /** @test */
    function adds_todos()
    {
        $reservation = factory(Reservation::class)->create();
        $tasks = ['task 1', 'task 2'];

        $reservation->addTodos($tasks);
        $todos = $reservation->todos()->pluck('task')->toArray();

        $this->assertContains('Task 1', $todos);
        $this->assertContains('Task 2', $todos);
    }

    /** @test */
    function remove_todos()
    {
        $reservation = factory(Reservation::class)->create();
        $tasks = ['task 1', 'task 2'];

        $reservation->addTodos($tasks);
        $reservation->removeTodos($tasks);
        $todos = $reservation->todos()->pluck('task')->toArray();

        $this->assertEmpty($todos);
    }

    /** @test */
    function syncs_todos()
    {
        $reservation = factory(Reservation::class)->create();
        $tasks = ['task 1', 'task 2'];
        $reservation->addTodos($tasks);

        $reservation->syncTodos(['task 1', 'task 3']);
        $todos = $reservation->todos()->pluck('task')->toArray();

        $this->assertContains('Task 1', $todos);
        $this->assertContains('Task 3', $todos);
        $this->assertFalse(in_array('Task 2', $todos));
    }

    /** @test */
    function syncs_deadlines()
    {
        $reservation = factory(Reservation::class)->create();
        $deadline = factory(Deadline::class)->create();

        $reservation->syncDeadlines([$deadline]);

        $this->assertEquals($reservation->deadlines()->first()->id, $deadline->id);
    }

    /** @test */
    function returns_promotional_ids_for_rewardable_reservation()
    {
        $campaign = factory(Campaign::class)->create();
        $campaign->promote('Test Promo', 1, 100, null, 'reservations');
        $trip = factory(Trip::class)->create(['campaign_id' => $campaign->id]);
        $reservation = factory(Reservation::class)->create(['trip_id' => $trip->id]);

        $promo = $reservation->canBeRewarded()->first();

        $this->assertContains($promo, $campaign->promotionals()->pluck('id'));
    }

    /** @test */
    function removes_reservation_from_squads_when_dropped()
    {
        $reservation = factory(Reservation::class)->create();
        $team = factory(Team::class)->create();
        $squad = $team->squads(TeamSquad::class)->create(['team_id' => $team->id]);
        $squad->members()->attach($reservation->id);

        $reservation->drop();

        $this->assertFalse(in_array($reservation->id, $squad->members->toArray()));
    }

}
