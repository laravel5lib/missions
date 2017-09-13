<?php

namespace App\Http\ViewComposers;

use App\Models\v1\Cost;
use App\Models\v1\Note;
use App\Models\v1\Todo;
use App\Models\v1\Report;
use Illuminate\View\View;
use App\Models\v1\Requirement;
use App\Models\v1\TripInterest;

class JavascriptComposer
{

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $user = auth()->user();

        $permissions = [
            'view_notes' => $user->can('view', Note::class),
            'create_notes' => $user->can('create', Note::class),
            'update_notes' => $user->can('update', Note::class),
            'delete_notes' => $user->can('delete', Note::class),
            'create_costs' => $user->can('create', Cost::class),
            'update_costs' => $user->can('update', Cost::class),
            'delete_costs' => $user->can('delete', Cost::class),
            'create_requirements' => $user->can('create', Requirement::class),
            'update_requirements' => $user->can('update', Requirement::class),
            'delete_requirements' => $user->can('delete', Requirement::class),
            'create_todos' => $user->can('create', Todo::class),
            'update_todos' => $user->can('update', Todo::class),
            'delete_todos' => $user->can('delete', Todo::class),
            'create_reports' => $user->can('create', Report::class),
            'update_trip_interests' => $user->can('update', TripInterest::class)
        ];

        \JavaScript::put([
            'user' => ['can' => $permissions]
        ]);
    }
}