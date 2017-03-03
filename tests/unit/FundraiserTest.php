<?php

use App\Models\v1\Fund;
use App\Models\v1\Fundraiser;
use App\Models\v1\Transaction;

class FundraiserTest extends TestCase
{
    /** 
     * @test
     */
    function can_get_amount_raised()
    {
        $fund = factory(Fund::class)->make();

        $transactions = factory(Transaction::class, 10)->make(['amount' => 10]);
        $fund->setRelation('transactions', $transactions);

        $fundraiser = factory(Fundraiser::class)->make();
        $fundraiser->setRelation('fund', $fund);

        $this->assertSame(10000, $fundraiser->raised());
        $this->assertSame('100.00', $fundraiser->raisedAsDollars());
    }

    /**
     * @test
     */
    function can_get_percent_raised()
    {
        $fund = factory(Fund::class)->make();
        
        $transactions = factory(Transaction::class, 10)->make(['amount' => 10]);
        $fund->setRelation('transactions', $transactions);

        $fundraiser = factory(Fundraiser::class)->make(['goal_amount' => 2500]);
        $fundraiser->setRelation('fund', $fund);

        $this->assertSame(4, $fundraiser->getPercentRaised());
    }

    /**
     * @test
     */
    function can_get_goal_amount_as_dollars()
    {
        $fundraiser = factory(Fundraiser::class)->make(['goal_amount' => 500]);

        $this->assertSame('500.00', $fundraiser->goalAmountAsDollars());
    }

}
