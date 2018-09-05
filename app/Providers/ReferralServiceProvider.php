<?php

namespace App\Providers;

use App\Models\v1\Referral;
use App\Policies\ReferralPolicy;
use App\Observers\ReferralObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class ReferralServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap(['referrals' => Referral::class]);

        Gate::policy(Referral::class, ReferralPolicy::class);

        Referral::observe(ReferralObserver::class);
    }
}
