<?php

namespace App\Providers;

use App\Models\v1\FormSubmission;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class FormServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'form_submissions' => FormSubmission::class,
            // 'essays' => FormSubmission::class,
            // 'testimonies' => FormSubmission::class,
            // 'influencer_applications' => FormSubmission::class,
            // 'questionnaires' => FormSubmission::class,
            // 'arrival_designations' => FormSubmission::class,
            // 'airport_preferences' => FormSubmission::class,
            // 'minor_releases' => FormSubmission::class,
        ]);

        FormSubmission::deleting(function ($form) {
            $form->detachFromAllReservations();
        });
    }
}
