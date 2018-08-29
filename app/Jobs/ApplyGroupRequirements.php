<?php

namespace App\Jobs;

use App\Models\v1\Trip;
use Illuminate\Bus\Queueable;
use App\Models\v1\CampaignGroup;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ApplyGroupRequirements implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $trip;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Trip $trip)
    {
        $this->trip = $trip;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $group = CampaignGroup::whereCampaignId($this->trip->campaign_id)
            ->whereGroupId($this->trip->group_id)
            ->firstOrFail();

        $group->requireables->each(function($requirement) {
            $this->trip->attachRequirementToModel($requirement->id);
        });
    }
}
