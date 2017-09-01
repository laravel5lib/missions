<?php

use App\Models\v1\Cost;
use App\Models\v1\Note;
use App\Models\v1\Slug;
use App\Models\v1\Trip;
use App\Models\v1\User;
use App\Models\v1\Donor;
use App\Models\v1\Group;
use App\Models\v1\Region;
use App\Models\v1\Upload;
use App\Models\v1\Payment;
use App\Models\v1\Campaign;
use App\Models\v1\Deadline;
use App\Models\v1\Companion;
use App\Models\v1\Requirement;
use App\Models\v1\Reservation;
use App\Models\v1\Transaction;
use App\Models\v1\TripInterest;
use Illuminate\Database\Seeder;

class TripTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new CurrentCampaignWithTripsAndInterests)->create(['name' => 'The New Honduras', 'country_code' => 'hn']);
        (new CurrentCampaignWithTripsAndInterests)->create(['name' => 'The Best Dominican', 'country_code' => 'do']);
        (new CurrentCampaignWithTripsAndInterests)->create(['name' => 'Nicaragua Shines', 'country_code' => 'ni']);
        // (new ArchivedCampaignWithTripsAndReservations)->times(3)->create();

        (new NewReservation)->times(20)->create();
        (new InProgressReservation)->times(70)->create();
        // (new TravelReadyReservation)->times(10)->create();
    }
}
