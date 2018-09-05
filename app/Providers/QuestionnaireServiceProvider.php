<?php

namespace App\Providers;

use App\Models\v1\MinorRelease;
use App\Models\v1\Questionnaire;
use App\Models\v1\AirportPreference;
use App\Policies\MinorReleasePolicy;
use Illuminate\Support\Facades\Gate;
use App\Models\v1\ArrivalDesignation;
use Illuminate\Support\ServiceProvider;
use App\Policies\AirportPreferencePolicy;
use App\Policies\ArrivalDesignationPolicy;
use Illuminate\Database\Eloquent\Relations\Relation;

class QuestionnaireServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'questionnaires' => Questionnaire::class,
            'arrival_designations' => ArrivalDesignation::class,
            'airport_preferences' => AirportPreference::class,
            'minor_releases' => MinorRelease::class
        ]);
        
        Gate::policy(ArrivalDesignation::class, ArrivalDesignationPolicy::class);
        Gate::policy(AirportPreference::class, AirportPreferencePolicy::class);
        Gate::policy(MinorRelease::class, MinorReleasePolicy::class);
    }
}
