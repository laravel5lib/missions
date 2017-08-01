<?php

use Carbon\Carbon;
use App\Models\v1\Trip;
use App\Models\v1\Campaign;
use Illuminate\Support\Facades\Session;

class TripDetailsPageTest extends TestCase
{

    /**
     * @test
     */
    public function see_public_trip_page()
    {
        $campaign = factory(Campaign::class)->create();
        $campaign->slug()->create(['url' => 'fake-campaign']);

        $trip = factory(Trip::class)->create([
            'public' => true,
            'campaign_id' => $campaign->id
        ]);

         $this->get('trips/'.$trip->id)
              ->assertResponseOk()
              ->assertViewHas('trip');
    }

    /**
     * @test
     */
    public function do_not_see_private_trip_page()
    {
        $campaign = factory(Campaign::class)->create();
        $campaign->slug()->create(['url' => 'fake-campaign']);

        $trip = factory(Trip::class)->create([
            'public' => false,
            'campaign_id' => $campaign->id
        ]);

         $this->get('trips/'.$trip->id)
              ->assertResponseStatus(403);
    }

    /**
     * @test
     */
    public function enable_register_button_for_open_trip()
    {
        $campaign = factory(Campaign::class)->create();
        $campaign->slug()->create(['url' => 'fake-campaign']);

        $trip = factory(App\Models\v1\Trip::class)->create([
          'public' => true,
          'spots' => 100,
          'campaign_id' => $campaign->id,
          'published_at' => Carbon::yesterday()
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
        $campaign = factory(Campaign::class)->create();
        $campaign->slug()->create(['url' => 'fake-campaign']);

        $tripNoSpots = factory(App\Models\v1\Trip::class)->create([
          'public' => true,
          'spots' => 0,
          'campaign_id' => $campaign->id
        ]);

        $tripClosed = factory(App\Models\v1\Trip::class)->create([
          'public' => true,
          'spots' => 10,
          'closed_at' => '2016-01-01 00:00:00',
          'campaign_id' => $campaign->id
        ]);

        $this->visit('trips/'.$tripNoSpots->id)
             ->see('Registration Closed');

        $this->visit('trips/'.$tripClosed->id)
             ->see('Registration Closed');
    }
}
