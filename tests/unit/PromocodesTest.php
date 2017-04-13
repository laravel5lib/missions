<?php

use Carbon\Carbon;
use App\Facades\Promocodes;

class PromocodesTest extends TestCase
{   
    /** @test */
    public function output_codes()
    {
        $promocode = Promocodes::output($amount = 1);

        $this->assertNotEmpty($promocode);
    }

    /** @test */
    public function create_codes()
    {
        $promocode = Promocodes::create(
            $name = 'Recruit1', 
            $amount = 1, 
            $reward = 10000
        );

        $this->assertArrayHasKey('code', $promocode->first());
        $this->seeInDatabase('promocodes', ['name' => 'Recruit1']);
    }

    /** @test */
    public function expired_code_fails_check()
    {
        $promocode = Promocodes::create(
            $name = 'Recruit1', 
            $amount = 1, 
            $reward = 10000,
            $expires = Carbon::now()->subYear()
        );

        $code = $promocode->first()['code'];

        $this->assertFalse(Promocodes::check($code));
    }

    /** @test */
    public function not_expired_code_passes_check()
    {
        $promocode = Promocodes::create(
            $name = 'Recruit1', 
            $amount = 1, 
            $reward = 10000,
            $expires = Carbon::now()->addYear()
        );

        $code = $promocode->first()['code'];

        $this->assertTrue(Promocodes::check($code));
    }

    /** @test */
    public function applied_code_returns_reward()
    {
        $promocode = Promocodes::create(
            $name = 'Recruit1', 
            $amount = 1, 
            $reward = 10000
        );

        $code = $promocode->first()['code'];

        $this->assertEquals(10000, Promocodes::apply($code));
    }
}
