<?php

namespace App\Jobs;

use App\Models\v1\Campaign;
use Illuminate\Bus\Queueable;
use App\Models\v1\CampaignGroup;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\CoordinatorScoreboard;
use Illuminate\Support\Facades\Notification;

class NotifyCoordinatorsWithScores implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach(['1,25', '26,50', '51,100', '101,200', '200,999'] as $range) {

            Campaign::active()->get()->each(function ($campaign) use ($range) {

                $results = CampaignGroup::getPercentagesOfCommitments($range, $campaign->id);

                foreach($results as $index => $result) {

                    $rank = $index+1;

                    Notification::send($result['group']['managers'], new CoordinatorScoreboard($results, $rank, $result));
                }

            });
        }
    }
}
