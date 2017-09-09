<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\v1\Campaign' => 'App\Policies\CampaignPolicy',
        'App\Models\v1\Trip' => 'App\Policies\TripPolicy',
        'App\Models\v1\Reservation' => 'App\Policies\ReservationPolicy',
        'App\Models\v1\Group' => 'App\Policies\GroupPolicy',
        'App\Models\v1\ProjectCause' => 'App\Policies\ProjectCausePolicy',
        'App\Models\v1\ProjectInitiative' => 'App\Policies\ProjectInitiativePolicy',
        'App\Models\v1\Project' => 'App\Policies\ProjectPolicy',
        'App\CampaignTransport' => 'App\Policies\TransportPolicy',
        'App\Models\v1\Passenger' => 'App\Policies\PassengerPolicy',
        'App\Models\v1\Region' => 'App\Policies\RegionPolicy',
        'App\Models\v1\Accommodation' => 'App\Policies\AccommodationPolicy',
        'App\Models\v1\Team' => 'App\Policies\TeamPolicy',
        'App\Models\v1\User' => 'App\Policies\UserPolicy',
        'App\Models\v1\Transaction' => 'App\Policies\TransactionPolicy',
        'App\Models\v1\Fund' => 'App\Policies\FundPolicy',
        'App\Models\v1\Donor' => 'App\Policies\DonorPolicy',
        'App\Models\v1\Report' => 'App\Policies\ReportPolicy',
        'App\Models\v1\Passport' => 'App\Policies\PassportPolicy',
        'App\Models\v1\Visa' => 'App\Policies\VisaPolicy',
        'App\Models\v1\Essay' => 'App\Policies\EssayPolicy',
        'App\Models\v1\MedicalRelease' => 'App\Policies\MedicalReleasePolicy',
        'App\Models\v1\Referral' => 'App\Policies\ReferralPolicy',
        'App\Models\v1\Credential' => 'App\Policies\CredentialPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // register the routes necessary to issue access tokens
        // and revoke access tokens, clients,
        // and personal access tokens
        Passport::routes();

        Passport::tokensExpireIn(Carbon::now()->addDays(15));

        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
    }
}
