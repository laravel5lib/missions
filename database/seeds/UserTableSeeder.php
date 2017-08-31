<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new AdminWithEverything)->create(['name' => 'Neil Keena', 'email' => 'neil@missions.me']);

        // interns
        (new InternWithEverything)->create([
            'name' => 'Taylor Jensen', 'email' => 'tjensen@missions.me'
        ]);
        (new InternWithEverything)->create([
            'name' => 'Esther Gualtieri', 'email' => 'egualtieri@missions.me'
        ]);
        (new InternWithEverything)->create([
            'name' => 'Cooper Moore', 'email' => 'cmoore@missions.me'
        ]);
        (new InternWithEverything)->create([
            'name' => 'Ethan Erway', 'email' => 'eerway@missions.me'
        ]);
        (new InternWithEverything)->create([
            'name' => 'Joshua Hughes', 'email' => 'jhughes@missions.me'
        ]);
        (new InternWithEverything)->create([
            'name' => 'Sergio Fielder', 'email' => 'sfielder@missions.me'
        ]);
        (new InternWithEverything)->create([
            'name' => 'Julia Muccini', 'email' => 'jmuccini@missions.me'
        ]);
        (new InternWithEverything)->create([
            'name' => 'Brittany Chamberlain', 'email' => 'bchamberlain@missions.me'
        ]);
        (new InternWithEverything)->create([
            'name' => 'Abigail Davenport', 'email' => 'adavenport@missions.me'
        ]);
        (new InternWithEverything)->create([
            'name' => 'Gloria Rodgers', 'email' => 'grodgers@missions.me'
        ]);
        (new InternWithEverything)->create([
            'name' => 'Victoria Keller', 'email' => 'vkeller@missions.me'
        ]);
        (new InternWithEverything)->create([
            'name' => 'Taja Titus', 'email' => 'ttitus@missions.me'
        ]);
        (new InternWithEverything)->create([
            'name' => 'Jackie Rios', 'email' => 'jrios@missions.me'
        ]);
        (new InternWithEverything)->create([
            'name' => 'Sara Kate Warner', 'email' => 'swarner@missions.me'
        ]);

        (new UserWithEverything)->times(10)->create();
        (new NewUser)->times(10)->create();

        (new CurrentCampaignWithTripsAndInterests)->create(['name' => 'The New Honduras', 'country_code' => 'hn']);
        (new CurrentCampaignWithTripsAndInterests)->create(['name' => 'The Best Dominican', 'country_code' => 'do']);
        (new CurrentCampaignWithTripsAndInterests)->create(['name' => 'Nicaragua Shines', 'country_code' => 'ni']);
        // (new ArchivedCampaignWithTripsAndReservations)->times(3)->create();

        (new NewReservation)->times(20)->create();
        (new InProgressReservation)->times(70)->create();
        // (new TravelReadyReservation)->times(10)->create();
    }
}
