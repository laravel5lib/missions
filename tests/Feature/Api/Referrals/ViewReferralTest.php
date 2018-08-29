<?php

namespace Tests\Feature\Api\Referrals;

use Tests\TestCase;
use App\Models\v1\Trip;
use App\Models\v1\User;
use App\Models\v1\Group;
use App\Models\v1\Referral;
use App\Models\v1\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewReferralTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function view_all_referrals()
    {
        factory(Referral::class, 2)->create();

        $response = $this->getJson('/api/referrals');

        $response->assertOk()
                 ->assertJsonStructure([
                    'data' => [
                        [
                            'id',
                            'user_id',
                            'applicant_name',
                            'type',
                            'attention_to',
                            'recipient_email',
                            'referrer',
                            'response',
                            'status',
                            'sent_at',
                            'responded_at',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                 ])
                 ->assertJson(['meta' => ['total' => 2]]);
    }

    /** @test */
    public function view_referral_by_id()
    {
        $referral = factory(Referral::class)->create();

        $response = $this->getJson("/api/referrals/{$referral->id}");

        $response->assertOk()
                 ->assertJson([
                    'data' => [
                        'id' => $referral->id
                    ]
                 ]);
    }

    /** @test */
    public function filter_referrals_by_applicant_name()
    {
        factory(Referral::class)->create(['applicant_name' => 'Han Solo']);
        factory(Referral::class)->create(['applicant_name' => 'Luke Skywalker']);

        $response = $this->getJson('/api/referrals?filter[applicant_name]=solo');

        $response->assertOk()
                 ->assertJson(['data' => [['applicant_name' => 'Han Solo']]])
                 ->assertJsonMissing(['data' => [['applicant_name' => 'Luke Skywalker']]]);
    }

    /** @test */
    public function filter_referrals_by_attention_to()
    {
        factory(Referral::class)->create(['attention_to' => 'Darth Vadar']);
        factory(Referral::class)->create(['attention_to' => 'Ben Kenobi']);

        $response = $this->getJson('/api/referrals?filter[attention_to]=darth');

        $response->assertOk()
                 ->assertJson(['data' => [['attention_to' => 'Darth Vadar']]])
                 ->assertJsonMissing(['data' => [['attention_to' => 'Ben Kenobi']]]);
    }

    /** @test */
    public function filter_referrals_by_recipient_email()
    {
        factory(Referral::class)->create(['recipient_email' => 'vadar@hotmail.com']);
        factory(Referral::class)->create(['recipient_email' => 'ben@gmail.com']);

        $response = $this->getJson('/api/referrals?filter[recipient_email]=vadar@hotmail.com');

        $response->assertOk()
                 ->assertJson(['data' => [['recipient_email' => 'vadar@hotmail.com']]])
                 ->assertJsonMissing(['data' => [['recipient_email' => 'ben@gmail.com']]]);
    }

    /** @test */
    public function filter_referrals_by_status()
    {
        factory(Referral::class)->create(['sent_at' => null, 'responded_at' => null]);
        factory(Referral::class)->create(['sent_at' => now()->subDay()->toDateTimeString(), 'responded_at' => null]);

        $response = $this->getJson('/api/referrals?filter[status]=sent');

        $response->assertOk()
                 ->assertJson(['data' => [['status' => 'sent']]])
                 ->assertJsonMissing(['data' => [['status' => 'draft']]]);
    }

    /** @test */
    public function filter_referrals_by_type()
    {
        factory(Referral::class)->create(['type' => 'pastoral']);
        factory(Referral::class)->create(['type' => 'employer']);

        $response = $this->getJson('/api/referrals?filter[type]=pastoral');

        $response->assertOk()
                 ->assertJson(['data' => [['type' => 'pastoral']]])
                 ->assertJsonMissing(['data' => [['type' => 'employer']]]);
    }

    /** @test */
    public function filter_referrals_by_user_id()
    {
        $user = factory(User::class)->create();

        factory(Referral::class)->create(['user_id' => $user->id]);
        $referral = factory(Referral::class)->create();

        $response = $this->getJson("/api/referrals?filter[user_id]={$user->id}");

        $response->assertOk()
                 ->assertJson(['data' => [['user_id' => $user->id]]])
                 ->assertJsonMissing(['data' => [['user_id' => $referral->user_id]]]);
    }

    /** @test */
    public function get_refferals_only_for_users_with_reservations_managed_by_team_coordinator()
    {
        $coordinator = factory(User::class)->create();
        $group = factory(Group::class)->create();
        $group->managers()->attach($coordinator->id);
        $trip = factory(Trip::class)->create(['group_id' => $group->id]);
        $reservation = factory(Reservation::class)->create(['trip_id' => $trip->id]);
        $referral = factory(Referral::class)->create(['user_id' => $reservation->user_id, 'applicant_name' => 'John Doe']);
        factory(Referral::class)->create(['applicant_name' => 'Jane Doe']);

        $response = $this->getJson("/api/referrals?filter[managed_by]={$coordinator->id}");

        $response->assertOk()
                 ->assertJson([
                    'data' => [
                        [
                            'applicant_name' => 'John Doe',
                            'user_id' => $reservation->user_id
                        ]
                    ]
                 ])
                 ->assertJsonMissing([
                    'data' => [
                        ['applicant_name' => 'Jane Doe']
                    ]
                 ]);
    }
}
