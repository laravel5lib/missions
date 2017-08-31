<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Silber\Bouncer\BouncerFacade as Bouncer;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        config(['mail.driver' => 'log']);
        config(['queue.default' => 'sync']);

        $this->call(DefaultRolesAndPermissionsSeeder::class);
        $this->call(AirlinesTableSeeder::class);
        $this->call(AirportsTableSeeder::class);
        $this->call(TeamTypeTableSeeder::class);
        $this->call(AccountingTablesSeeder::class);
        $this->call(UploadSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(TripTableSeeder::class);
        $this->call(ProjectTablesSeeder::class);
        $this->call(FundsTableSeeder::class);
        $this->call(ActivityTypeSeeder::class);
        $this->call(RoomTypesTableSeeder::class);

        // Passport Password Grant Client
        // DOCS: https://laravel.com/docs/5.4/passport#password-grant-tokens
        DB::table('oauth_clients')->insert([
            'id'        => 1,
            'name'      => 'Missions.Me Password Grant Client',
            'secret'    => 'x8NFHoYH0z5FP5P6TW9mBrOf8FbX2iekZIa0VAbs',
            'redirect'  => app()->environment('local') ? 'http://missions.dev' : 'https://missions.me',
            'personal_access_client'  => 0,
            'password_client'  => 1,
            'revoked'   => 0
        ]);

        config(['mail.driver' => 'smtp']);
        config(['queue.default' => 'sqs']);
    }
}
