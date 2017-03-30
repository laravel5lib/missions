<?php

use App\Services\ReferralCode;

class ReferralCodeTest extends TestCase
{
    /** 
     * @test
     */
    function constructs_code_on_initialization()
    {
        $code = new ReferralCode;

        $this->assertNotEmpty($code);
    }

    /** 
     * @test
     */
    function prefixes_code_with_string()
    {
        $code = new ReferralCode('secret_');

        $this->assertContains('secret_', (string) $code);
    }

    /** 
     * @test
     */
    function creates_unique_codes()
    {
        $codeOne = (string) new ReferralCode;
        $codeTwo = (string) new ReferralCode;

        $this->assertNotEquals($codeOne, $codeTwo);
    }

    /** 
     * @test
     */
    function limits_length_of_code()
    {
        $code = new ReferralCode;

        $shortCode = (string) $code->limit(4);

        $this->assertEquals(strlen($shortCode), 4);
    }
}
