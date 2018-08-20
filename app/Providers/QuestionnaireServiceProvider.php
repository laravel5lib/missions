<?php

namespace App\Providers;

use App\Models\v1\Questionnaire;
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
            'arrival_designations' => Questionnaire::class,
            'airport_preferences' => Questionnaire::class,
            'minor_releases' => Questionnaire::class
        ]);

        Questionnaire::deleting(function ($questionnaire) {
            $questionnaire->detachFromAllReservations();
        });
    }
}
