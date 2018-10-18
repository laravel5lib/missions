<?php

namespace Tests\Feature\Api\Interests;

use Tests\TestCase;
use App\Models\v1\Trip;
use App\Models\v1\User;
use Laravel\Passport\Passport;
use App\Models\v1\TripInterest;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConversionScoreboardTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function gets_top_ten_users_for_the_current_month_with_a_minimum_of_ten_interest_conversions()
    {
        $trip = factory(Trip::class)->create();

        $userIds = factory(User::class, 10)->create()->pluck('id')->toArray();

        factory(Activity::class, 100)->create([
            'description' => 'updated',
            'subject_type' => 'trip_interests',
            'subject_id' => factory(TripInterest::class)->create(['trip_id' => $trip->id])->id,
            'causer_type' => 'users',
            'causer_id' => $this->faker->randomElement($userIds),
            'created_at' => $this->faker->dateTimeThisMonth(),
            'properties' => ['attributes' => ['status' => 'converted']]
        ]);

        $startDate = today()->startOfMonth()->toDateString();
        $endDate = today()->endOfMonth()->toDateString();

        $response = $this->getJson("/api/metrics/interests/conversions-by-user?start=$startDate&end=$endDate&min=10&limit=10");

        $response->assertOk()->assertJsonStructure([
            'data' => [
                [
                    'user', 'conversion_count'
                ]
            ]
        ]);
    }
}
