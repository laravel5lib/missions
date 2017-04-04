<?php

namespace App\Providers;

use App\Models\v1\Cost;
use App\Models\v1\Trip;
use App\Models\v1\User;
use App\Models\v1\Group;
use App\Models\v1\Upload;
use App\Models\v1\Payment;
use App\Models\v1\Project;
use App\Models\v1\Campaign;
use App\Models\v1\Referral;
use App\Models\v1\ProjectCause;
use App\Jobs\SendReferralRequestEmail;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\DonationWasMade' => [
            'App\Listeners\EmailReceipt',
            'App\Listeners\NotifyRecipient'
        ],
        'App\Events\ReservationWasProcessed' => [
            'App\Listeners\EmailReservationConfirmation',
            'App\Listeners\NotifyFacilitatorsOfNewReservation'
        ],
        'App\Events\TripInterestWasCreated' => [
            'App\Listeners\NotifyFacilitatorsOfNewTripInterest',
            'App\Listeners\NotifyTripRepOfNewTripInterest'
        ]
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        'App\Listeners\ReservationEventListener',
        'App\Listeners\TransactionEventListener',
        'App\Listeners\RequirementEventListener'
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        Trip::created(function ($trip) {
            $name = generateFundName($trip);
            $trip->fund()->create([
                'name' => $name,
                'slug' => generate_fund_slug($name),
                'balance' => 0,
                'class' => generateQbClassName($trip),
                'item' => 'Missionary Donation'
            ]);
        });

        Campaign::created(function ($campaign) {
            $name = generateFundName($campaign);
            $campaign->fund()->create([
                'name' => $name,
                'slug' => generate_fund_slug($name),
                'balance' => 0,
                'class' => generateQbClassName($campaign),
                'item' => 'General Donation'
            ]);
        });

        Project::created(function ($project) {
            $name = $project->name . ' Project';
            $fund = $project->fund()->create([
                'name' => $name,
                'slug' => generate_fund_slug($name),
                'balance' => 0,
                'class' => str_plural($project->initiative->cause->name),
                'item' => $project->name .' - '. $project->initiative->cause->name
            ]);
            $fund->fundraisers()->create([
                'name' => $name,
                'url' => generate_fundraiser_slug($name),
                'description' => file_get_contents(resource_path('assets/sample_fundraiser.md')),
                'sponsor_type' => $project->sponsor_type,
                'sponsor_id' => $project->sponsor_id,
                'goal_amount' => $project->goal ?: 0,
                'started_at' => $project->created_at,
                'ended_at' => $project->initiative->ended_at,
                'public' => false // private by default
            ]);
        });

        ProjectCause::created(function ($cause) {
            $name = $cause->name . ' Cause';
            $cause->fund()->create([
                'name' => $name,
                'slug' => generate_fund_slug($name),
                'balance' => 0,
                'class' => str_plural($cause->name),
                'item' => 'General Donation'
            ]);
        });

        User::created(function ($user) {
            $user->assign('member');

            $banner = Upload::randomBanner(['user'])->first();
            $user->banner_upload_id = $banner ? $banner->id : null;
            $user->save();
        });

        Group::created(function ($group) {
            $banner = Upload::randomBanner(['group'])->first();
            $group->banner_upload_id = $banner ? $banner->id : null;
            $group->save();
        });

        Cost::created(function ($cost) {
            if ($cost->costAssignable instanceof Project) {
                $cost->costAssignable
                     ->fund
                     ->fundraisers()
                     ->first()
                     ->update(['goal_amount' => $cost->costAssignable->goal/100]);

                $cost->costAssignable->payments()->sync();
            }
        });

        Cost::updated(function ($cost) {
            if ($cost->costAssignable instanceof Project) {
                $cost->costAssignable
                     ->fund
                     ->fundraisers()
                     ->first()
                     ->update(['goal_amount' => $cost->costAssignable->goal/100]);
                     
                $cost->costAssignable->payments()->sync();
            }
        });
    }
}
