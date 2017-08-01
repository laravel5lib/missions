<?php

namespace App\Console\Commands;

use App\Models\v1\Trip;
use Illuminate\Console\Command;
use App\Jobs\UpdateReservationRequirements;

class AddRequirementToTrips extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trips:add-requirement 
                            {--T|type= : Whether it should be scoped to a trip type} 
                            {--C|campaignId= : Whether it should be scoped to a campaign ID} 
                            {--G|groupId= : Whether it should be scoped to a group ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $tripType = $this->option('type');
        $campaignId = $this->option('campaignId');
        $groupId = $this->option('groupId');

        $name = $this->anticipate('What is the requirement\'s name?', [
            'Passport', 'Visa', 'Personal Testimony', 'Pastoral Recommendation',
            'Medical Release', 'Minor Release', 'Media Credentials', 'Medical Credentials',
            'Airport Preference', 'Arrival Designation', 'Influencer Application', 'Travel Itinerary'
        ]);
        $desc = $this->ask('Please provide a short description:');
        $docType = $this->choice('What type of document?', config('requirements.document_types'));
        $due = $this->ask('When is this requirement due? (format: YYYY-MM-DD HH::MM::SS)');
        $grace = $this->ask('How many days grace period?');

        // Conditions
        $conditionType = null;
        $conditionOperator = null;
        $conditionAppliesTo = null;

        if ($this->confirm('Do you want add any conditions to this requirement? [y|N]')) {
             $conditionType = $this->choice('What type of condition is this?', config('requirements.conditions.types'));
             $conditionOperator = $this->choice('Please select how the condition should apply:', config('requirements.conditions.operators'));
             $conditionAppliesTo = $this->ask('Please provide the '. $conditionType .' this condition applies to (if more than one please seperate with commas):');
        }

        $query = $this->trip;

        if ($tripType) {
            $query = $query->where('type', $tripType);
        }

        if ($campaignId) {
            $query = $query->where('campaign_id', $campaignId);
        }

        if ($groupId) {
            $query = $query->where('group_id', $groupId);
        }

        $trips = $query->get();

        if (! $trips->count()) {
            $this->error('No trips found matching your request.');
            return;
        }

        collect($trips)->each(function ($trip) use ($name, $desc, $docType, $due, $grace, $conditionType, $conditionOperator, $conditionAppliesTo) {
            $requirement = $trip->requirements()->create([
                'name' => $name,
                'short_desc' => $desc,
                'document_type' => $docType,
                'due_at' => $due,
                'grace_period' => $grace
            ]);

            if ($conditionType && $conditionOperator && $conditionAppliesTo) {
                $requirement->conditions()->create([
                    'type' => $conditionType,
                    'operator' => $conditionOperator,
                    'applies_to' => explode(',', $conditionAppliesTo)
                ]);
            }

            dispatch(new UpdateReservationRequirements($requirement));

            $this->info('New requirement was added to trip ' . $trip->id);
        });
    }
}
