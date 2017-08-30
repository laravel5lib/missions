<?php

namespace App\Console\Commands\Requirements;

use App\Models\v1\Requirement;
use Illuminate\Console\Command;

class Remove extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'requirement:remove {id : The requirement ID to remove}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove a requirement.';
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

            try {
                $requirement->reservations()
                    ->detach($reservationId);
            }
            catch (\Exception $e) {
                $this->error('Unable to remove requirement!');
            }

            $this->info('Requirement removed from reservation');
        }
    }
}
