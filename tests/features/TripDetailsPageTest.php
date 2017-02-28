<?php

use Illuminate\Support\Facades\Session;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TripDetailsPageTest extends TestCase
{   
    use DatabaseMigrations, DatabaseTransactions;

    /**
     * @test
     */
    public function see_public_trip_page()
    {
        $trip = factory(App\Models\v1\Trip::class)->create(['public' => true]);

         $this->get('trips/'.$trip->id)
              ->assertResponseOk()
              ->assertViewHas('trip');
    }

    /**
     * @test
     */
    public function do_not_see_private_trip_page()
    {
        $trip = factory(App\Models\v1\Trip::class)->create(['public' => false]);

         $this->get('trips/'.$trip->id)
              ->assertResponseStatus(403);
    }

    /**
     * @test
     */
    public function enable_register_button_for_open_trip()
    {
        $trip = factory(App\Models\v1\Trip::class)->create([
          'public' => true, 
          'spots' => 100
        ]);

        $this->visit('trips/'.$trip->id)
             ->click('Register Now')
             ->seePageIs('trips/'.$trip->id.'/register');
    }

    /**
     * @test
     */
    public function disable_register_button_for_closed_trip()
    {
        $tripNoSpots = factory(App\Models\v1\Trip::class)->create([
          'public' => true,
          'spots' => 0,
        ]);

        $tripClosed = factory(App\Models\v1\Trip::class)->create([
          'public' => true,
          'spots' => 10,
          'closed_at' => '2016-01-01 00:00:00'
        ]);

        $this->visit('trips/'.$tripNoSpots->id)
             ->see('Registration Closed');

        $this->visit('trips/'.$tripClosed->id)
             ->see('Registration Closed');
    }
}
