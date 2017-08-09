<?php

use App\Models\v1\Cost;

class CostTest extends BrowserKitTestCase
{
    /**
     * @test
     */
    function cost_can_set_amount()
    {
        $cost = factory(Cost::class)->make(['amount' => 100.50]);

        $this->assertSame(10050, $cost->amount);
    }

    /**
     * @test
     */
    function cost_can_get_amount_in_dollars()
    {
        $cost = factory(Cost::class)->make(['amount' => 100.50]);

        $this->assertSame('100.50', $cost->amountInDollars());
    }
}
