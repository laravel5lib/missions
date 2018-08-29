<?php

namespace App\Providers;

use App\Models\v1\MinorRelease;
use App\Models\v1\Questionnaire;
use App\Models\v1\AirportPreference;
use App\Models\v1\ArrivalDesignation;
use Illuminate\Support\ServiceProvider;
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
    }
}
