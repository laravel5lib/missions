<?php

use Illuminate\Database\Seeder;
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

        $this->call(BouncerSeeder::class);
        $this->call(UploadSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(PassportTableSeeder::class);
        $this->call(VisaTableSeeder::class);
        $this->call(MedicalReleaseSeeder::class);
        $this->call(GroupTableSeeder::class);
        $this->call(CampaignTableSeeder::class);
        $this->call(TripTableSeeder::class);
        $this->call(DonorTableSeeder::class);
        $this->call(ReservationTableSeeder::class);
        $this->call(ProjectTablesSeeder::class);
        $this->call(FundsTableSeeder::class);
        $this->call(ContactTableSeeder::class);
        $this->call(RegionSeeder::class);
        $this->call(TeamMemberSeeder::class);
        $this->call(DecisionTableSeeder::class);
        $this->call(ExamTableSeeder::class);
        $this->call(SiteTableSeeder::class);
        $this->call(TransportSeeder::class);
        $this->call(AccommodationSeeder::class);
        $this->call(StoriesTableSeeder::class);

        config(['mail.driver' => 'smtp']);
        config(['queue.default' => 'sqs']);
    }
}
