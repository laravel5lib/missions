<?php

class RewardableTraitTest extends TestCase
{
    /** @test */
    public function create_codes()
    {
        $mock = $this->getMockForTrait('App\Traits\Rewardable');

        $mock->expects($this->any())
             ->method('createCode')
             ->will($this->returnValue(TRUE));

        $this->assertTrue($mock->createCode());
    }
}
