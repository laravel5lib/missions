<?php

namespace App\Console\Commands\Utilities;

use App\Repositories\Rooming\Interfaces\Plan;
use Illuminate\Console\Command;

class UpdateRoomingPlansToMultiGroups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rooming-plans:update-multi-group';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates database to use multi-group support.';
    /**
     * @var Plan
     */
    private $plan;

    /**
     * Create a new command instance.
     *
     * @param Plan $plan
     */
    public function __construct(Plan $plan)
    {
        parent::__construct();
        $this->plan = $plan;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $plans = $this->plan->with('groups')->getAll();

        foreach ($plans as $plan) {
            $plan->groups()->sync(['group_id' => $plan->group_id]);
        }
    }
}
