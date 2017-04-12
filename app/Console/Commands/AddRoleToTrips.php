<?php

namespace App\Console\Commands;

use App\Models\v1\Trip;
use Illuminate\Console\Command;

class AddRoleToTrips extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trips:add-role 
                            {role : The team role code to add} 
                            {--T|type= : Whether it should be scoped to a trip type} 
                            {--C|campaignId= : Whether it should be scoped to a campaign ID} 
                            {--G|groupId= : Whether it should be scoped to a group ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a role to all trips';

    protected $trip;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Trip $trip)
    {
        parent::__construct();
        $this->trip = $trip;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $roleCode = $this->argument('role');
        $tripType = $this->option('type');
        $campaignId = $this->option('campaignId');
        $groupId = $this->option('groupId');

        $query = $this->trip;

        if($tripType) {
            $query = $query->where('type', $tripType);
        }

        if($campaignId) {
            $query = $query->where('campaign_id', $campaignId);
        }

        if($groupId) {
            $query = $query->where('group_id', $groupId);
        }

        $trips = $query->get();

        if (! $trips->count()) {
            $this->error('No trips found matching your request.');
            return;
        }

        collect($trips)->each(function ($trip) use($roleCode) {
            $trip->team_roles = array_values(collect($trip->team_roles)->push($roleCode)->unique()->toArray());
            $trip->save();
            $this->info($roleCode . ' was added to trip ' . $trip->id);
        });

    }
}
