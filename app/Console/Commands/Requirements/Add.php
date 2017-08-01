<?php

namespace App\Console\Commands\Requirements;

use App\Models\v1\Requirement;
use Illuminate\Console\Command;
use Ramsey\Uuid\Uuid;

class Add extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'requirement:add {id : The requirement ID to add}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a requirement.';
    /**
     * @var Requirement
     */
    private $requirement;

    /**
     * Create a new command instance.
     *
     * @param Requirement $requirement
     */
    public function __construct(Requirement $requirement)
    {
        parent::__construct();
        $this->requirement = $requirement;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $requirement = $this->requirement->findOrFail($this->argument('id'));
        } catch (\Exception $e) {
            $this->error('Unable to find requirement!');
        }

        $requester = $this->choice('Choose a requester:', [
            'reservation'
        ]);

        if ($requester == 'reservation') {
            $reservationId = $this->ask('Please enter a reservation ID:');
            $days = $this->ask('How many days grace period?', $requirement->grace_period);

            try {
                $requirement->reservations()
                    ->sync([
                        $reservationId => [
                            'grace_period'  => $days,
                            'status'        => 'incomplete',
                            'document_type' => $requirement->document_type,
                            'id'            => Uuid::uuid4()
                        ]
                    ], false);
            } catch (\Exception $e) {
                $this->error('Unable to add requirement!');
            }

            $this->info('Requirement added to reservation');
        }
    }
}
