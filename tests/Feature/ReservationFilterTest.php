<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\v1\Todo;
use App\Models\v1\Trip;
use App\Models\v1\User;
use App\Models\v1\Reservation;
use Laravel\Passport\Passport;
use App\Models\v1\FlightItinerary;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReservationFilterTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        Passport::actingAs(factory(User::class, 'admin')->create());
    }

    /** @test */
    public function filter_reservations_by_surname()
    {
        $reservation = factory(Reservation::class)->create(['surname' => 'Smith']);
        factory(Reservation::class)->create();

        $this->json('GET', '/api/reservations?filter[surname]=smith')
             ->assertStatus(200)
             ->assertJson([
                    'data' => [
                        ['surname' => 'Smith']
                    ],
                    'meta' => [
                        'total' => 1
                    ]
                ]);
    }

    /** @test */
    public function filter_reservations_by_given_names()
    {
        $reservation = factory(Reservation::class)->create(['given_names' => 'John Doe']);
        factory(Reservation::class)->create();

        $this->json('GET', '/api/reservations?filter[given_names]=john+doe')
             ->assertStatus(200)
             ->assertJson([
                    'data' => [
                        ['given_names' => 'John Doe']
                    ],
                    'meta' => [
                        'total' => 1
                    ]
                ]);
    }

    /** @test */
    public function filter_reservations_by_email()
    {
        $reservation = factory(Reservation::class)->create(['email' => 'john@example.com']);
        factory(Reservation::class)->create();

        $this->json('GET', '/api/reservations?filter[email]=john@example.com')
             ->assertStatus(200)
             ->assertJson([
                    'data' => [
                        ['email' => 'john@example.com']
                    ],
                    'meta' => [
                        'total' => 1
                    ]
                ]);
    }

    /** @test */
    public function filter_reservations_by_phone()
    {
        $reservation = factory(Reservation::class)->create([
            'phone_one' => '1234567890', 'phone_two' => '9876543211'
        ]);
        factory(Reservation::class)->create();

        $this->json('GET', '/api/reservations?filter[phone_one]=1234567890&filter[phone_two]=9876543211')
             ->assertStatus(200)
             ->assertJson([
                    'data' => [
                        ['phone_one' => '1234567890', 'phone_two' => '9876543211']
                    ],
                    'meta' => [
                        'total' => 1
                    ]
                ]);
    }

    /** @test */
    public function filter_reservations_by_gender()
    {
        $reservation = factory(Reservation::class)->create(['gender' => 'male']);
        factory(Reservation::class)->create(['gender' => 'female']);

        $this->json('GET', '/api/reservations?filter[gender]=male')
             ->assertStatus(200)
             ->assertJson([
                    'data' => [
                        ['gender' => 'male']
                    ],
                    'meta' => [
                        'total' => 1
                    ]
                ]);
    }

    /** @test */
    public function filter_reservations_by_marital_status()
    {
        $reservation = factory(Reservation::class)->create(['status' => 'married']);
        factory(Reservation::class)->create(['status' => 'single']);

        $this->json('GET', '/api/reservations?filter[status]=married')
             ->assertStatus(200)
             ->assertJson([
                    'data' => [
                        ['status' => 'married']
                    ],
                    'meta' => [
                        'total' => 1
                    ]
                ]);
    }

    /** @test */
    public function filter_reservations_by_shirt_size()
    {
        $reservation = factory(Reservation::class)->create(['shirt_size' => 'xxl']);
        factory(Reservation::class)->create(['shirt_size' => 's']);

        $this->json('GET', '/api/reservations?filter[shirt_size]=xxl')
             ->assertStatus(200)
             ->assertJson([
                    'data' => [
                        ['shirt_size' => 'XXL']
                    ],
                    'meta' => [
                        'total' => 1
                    ]
                ]);
    }
    

    /** @test */
    public function filter_reservations_by_group()
    {
        $trip = factory(Trip::class)->create();
        $reservation = factory(Reservation::class)->create(['trip_id' => $trip->id]);
        factory(Reservation::class)->create();

        $this->json('GET', "/api/reservations?filter[group]={$trip->group_id}")
             ->assertStatus(200)
             ->assertJson([
                    'data' => [
                        ['id' => $reservation->id]
                    ],
                    'meta' => [
                        'total' => 1
                    ]
                ]);
    }

    /** @test */
    public function filter_reservations_by_trip_type()
    {
        $ministryTrip = factory(Trip::class)->create(['type' => 'ministry']);
        factory(Reservation::class)->create(['trip_id' => $ministryTrip->id]);

        $medicalTrip = factory(Trip::class)->create(['type' => 'medical']);
        $reservation = factory(Reservation::class)->create(['trip_id' => $medicalTrip->id]);

        $this->json('GET', "/api/reservations?filter[trip_type]=medical")
             ->assertStatus(200)
             ->assertJson([
                    'data' => [
                        ['id' => $reservation->id]
                    ],
                    'meta' => [
                        'total' => 1
                    ]
                ]);
    }

    /** @test */
    public function filter_reservations_by_desired_role()
    {
        factory(Reservation::class)->create(['desired_role' => 'BUSP']);
        $reservation = factory(Reservation::class)->create(['desired_role' => 'MISS']);

        $this->json('GET', "/api/reservations?filter[desired_role]=miss")
             ->assertStatus(200)
             ->assertJson([
                    'data' => [
                        ['desired_role' => ['code' => 'MISS']]
                    ],
                    'meta' => [
                        'total' => 1
                    ]
                ]);
    }

    /** @test */
    public function filter_reservations_by_campaign()
    {
        factory(Reservation::class)->create();
        $trip = factory(Trip::class)->create();
        $reservation = factory(Reservation::class)->create(['trip_id' => $trip->id]);

        $this->json('GET', "/api/reservations?filter[campaign]={$trip->campaign_id}")
             ->assertStatus(200)
             ->assertJson([
                    'data' => [
                        ['id' => $reservation->id]
                    ],
                    'meta' => [
                        'total' => 1
                    ]
                ]);
    }

    /** @test */
    public function filter_reservations_by_flight_not_booked()
    {
        factory(Reservation::class)->create([
            'flight_itinerary_id' => factory(FlightItinerary::class)->create()->id
        ]);
        $reservation = factory(Reservation::class)->create(['flight_itinerary_id' => null]);

        $this->json('GET', "/api/reservations?filter[has_flight]=false")
             ->assertStatus(200)
             ->assertJson([
                    'data' => [
                        ['id' => $reservation->id]
                    ],
                    'meta' => [
                        'total' => 1
                    ]
                ]);
    }

    /** @test */
    public function filter_reservations_by_flight_is_booked()
    {
        $reservation = factory(Reservation::class)->create([
            'flight_itinerary_id' => factory(FlightItinerary::class)->create()->id
        ]);
        factory(Reservation::class)->create(['flight_itinerary_id' => null]);

        $this->json('GET', "/api/reservations?filter[has_flight]=true")
             ->assertStatus(200)
             ->assertJson([
                    'data' => [
                        ['id' => $reservation->id]
                    ],
                    'meta' => [
                        'total' => 1
                    ]
                ]);
    }

    /** @test */
    public function filter_reservations_by_incomplete_task()
    {
        factory(Todo::class, 2)->create(['task' => 'sent t-shirt', 'completed_at' => null]);
        factory(Todo::class, 3)->create(['task' => 'sent t-shirt', 'completed_at' => now()->subDay()]);
        factory(Todo::class, 4)->create(['task' => 'welcome email', 'completed_at' => null]);

        $this->json('GET', "/api/reservations?filter[incomplete_task]=sent+t-shirt")
             ->assertStatus(200)
             ->assertJson([
                    'meta' => [
                        'total' => 2
                    ]
                ]);
    }

    /** @test */
    public function filter_reservations_by_complete_task()
    {
        factory(Todo::class, 2)->create(['task' => 'sent t-shirt', 'completed_at' => null]);
        factory(Todo::class, 3)->create(['task' => 'sent t-shirt', 'completed_at' => now()->subDay()]);
        factory(Todo::class, 4)->create(['task' => 'welcome email', 'completed_at' => null]);

        $this->json('GET', "/api/reservations?filter[complete_task]=sent+t-shirt")
             ->assertStatus(200)
             ->assertJson([
                    'meta' => [
                        'total' => 3
                    ]
                ]);
    }
}
