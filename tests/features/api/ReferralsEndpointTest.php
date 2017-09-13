<?php

use App\Models\v1\User;
use App\Models\v1\Referral;

class ReferralsEndpointTest extends BrowserKitTestCase
{
    use AuthenticatedUserSetup;

    /**
     * @test
     */
    public function returns_referrals()
    {
        $referrals = factory(Referral::class, 2)->create([
                'user_id' => function () {
                    return factory(User::class)->create()->id;
                }
            ]);

        $this->get('/api/referrals')
            ->assertResponseOk()
            ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id', 'user_id', 'applicant_name', 'type', 'attention_to',
                        'recipient_email', 'referrer', 'response', 'status', 'sent_at',
                        'responded_at', 'created_at', 'updated_at'
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function creates_referral_and_sends()
    {
        $referral = factory(Referral::class)->make([
            'user_id' => function () {
                return factory(User::class)->create()->id;
            }
        ])->toArray();

        $this->post('/api/referrals', $referral)
            ->assertResponseOk()
            ->seeJson([
                'status' => 'sent'
            ]);
    }

    /**
     * @test
     */
    public function updates_referral()
    {
        $referral = factory(Referral::class)->create([
            'applicant_name' => 'joe',
            'user_id' => function () {
                return factory(User::class)->create()->id;
            }
        ]);

        $referral['applicant_name'] = 'joe';

        $this->put('/api/referrals/'.$referral->id, $referral->toArray())
            ->assertResponseOk()
            ->seeJson([
                'applicant_name' => 'joe',
                'status' => 'sent'
            ]);
    }
}
