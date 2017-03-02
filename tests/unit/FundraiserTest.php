<?php

use App\Models\v1\Fund;
use App\Models\v1\Fundraiser;
use App\Models\v1\Transaction;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FundraiserTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** 
     * @test
     */
    function can_get_amount_raised()
    {
        $fund = factory(Fund::class)->create();
        $fundraiser = factory(Fundraiser::class)->create(['fund_id' => $fund->id]);
        $transactions = factory(Transaction::class, 10)->create(
            ['amount' => 10, 'fund_id' => $fund->id]
        );

        $this->assertSame(10000, $fundraiser->raised());
        $this->assertSame('100.00', $fundraiser->raisedAsDollars());
    }

    /**
     * @test
     */
    function can_get_percent_raised()
    {
        $fund = factory(Fund::class)->create();
        $fundraiser = factory(Fundraiser::class)->create([
            'fund_id' => $fund->id,
            'goal_amount' => 250000
        ]);
        $transactions = factory(Transaction::class, 10)->create(
            ['amount' => 10, 'fund_id' => $fund->id]
        );

        $this->assertSame(4, $fundraiser->getPercentRaised());
    }

    /**
     * @test
     */
    function can_get_goal_amount_as_dollars()
    {
        $fundraiser = factory(Fundraiser::class)->create([
            'goal_amount' => 500
        ]);

        $this->assertSame('500.00', $fundraiser->goalAmountAsDollars());
    }

}
