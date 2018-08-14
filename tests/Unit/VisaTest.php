<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\v1\Visa;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VisaTest extends TestCase
{
    /** @test */
    public function visa_number_attribute_is_mutated_to_all_caps_when_set()
    {
        $number = 'Abc123Def';

        $visa = new Visa;
        $visa->number = $number;

        $this->assertEquals($visa->number, 'ABC123DEF');
    }
}
