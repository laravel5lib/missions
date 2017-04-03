<?php

namespace App\Console\Commands;

use App\Models\v1\Trip;
use Illuminate\Console\Command;
use App\Jobs\UpdateReservationTodos;

class AddTodoToTrips extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trips:add-todo 
                            {task : The task to add to the todos list} 
                            {--T|type= : Whether it should be scoped to a trip type} 
                            {--C|campaignId= : Whether it should be scoped to a campaign ID} 
                            {--G|groupId= : Whether it should be scoped to a group ID} 
                            {--R|repId= : Whether it should be scoped to a trip trip ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a task to all trip todo lists';

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
        $task = $this->argument('task');
        $tripType = $this->option('type');
        $campaignId = $this->option('campaignId');
        $groupId = $this->option('groupId');
        $repId = $this->option('repId');

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

        if($repId) {
            $query = $query->where('rep_id', $repId);
        }

        $trips = $query->get();

        if (! $trips->count()) {
            $this->error('No trips found matching your request.');
            return;
        }

        collect($trips)->each(function ($trip) use($task) {
            $trip->todos = array_values(collect($trip->todos)->push($task)->unique()->toArray());
            $trip->save();
            dispatch(new UpdateReservationTodos($trip));
            $this->info($task . ' was added to trip ' . $trip->id . ' todos');
        });
    }
}
