<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CostTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** 
     * @test
     */
    function cost_can_set_amount()
    {
        $cost = factory(App\Models\v1\Cost::class)->make(['amount' => 100.50]);

        $this->assertSame(10050, $cost->amount);
    }

    /** 
     * @test
     */
    function cost_can_get_amount_in_dollars()
    {
        $cost = factory(App\Models\v1\Cost::class)->make(['amount' => 100.50]);

        $this->assertSame('100.50', $cost->amountInDollars());
    }

}
