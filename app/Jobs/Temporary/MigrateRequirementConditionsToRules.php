<?php

namespace App\Jobs\Temporary;

use Illuminate\Bus\Queueable;
use App\Models\v1\Requirement;
use Illuminate\Queue\SerializesModels;
use App\Models\v1\RequirementCondition;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MigrateRequirementConditionsToRules implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        RequirementCondition::get(['requirement_id', 'type', 'applies_to'])
            ->each(function ($condition) {

                $requirement = Requirement::findOrFail($condition->requirement_id);
                $requirement->rules = [
                    ($condition->type == 'role' ? 'roles' : $condition->type) => 
                    ($condition->type == 'age' ? $condition->applies_to[0] : $condition->applies_to)
                ];
                $requirement->save();

            });
    }
}
