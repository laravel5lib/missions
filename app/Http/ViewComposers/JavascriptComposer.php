<?php

namespace App\Http\ViewComposers;

use App\Models\v1\Cost;
use App\Models\v1\Note;
use App\Models\v1\Todo;
use App\Models\v1\Trip;
use App\Models\v1\Fund;
use App\Models\v1\Donor;
use App\Models\v1\Report;
use Illuminate\View\View;
use App\Models\v1\Requirement;
use App\Models\v1\Transaction;
use App\Models\v1\TripInterest;
use App\Models\v1\Representative;
use App\Models\v1\MedicalRelease;

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
            'create_trips' => $user->can('create', Trip::class),
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
            'update_trip_interests' => $user->can('update', TripInterest::class),
            'update_funds' => $user->can('update', Fund::class),
            'delete_funds' => $user->can('delete', Fund::class),
            'view_transactions' => $user->can('view', Transaction::class),
            'add_credit_card_transactions' => $user->hasanyrole('super_admin|admin') || $user->can('add_credit_card_transactions'),
            'add_credit_transactions' => $user->hasanyrole('super_admin|admin') || $user->can('add_credit_transactions'),
            'add_check_transactions' => $user->hasanyrole('super_admin|admin') || $user->can('add_check_transactions'),
            'add_cash_transactions' => $user->hasanyrole('super_admin|admin') || $user->can('add_cash_transactions'),
            'add_transfer_transactions' => $user->hasanyrole('super_admin|admin') || $user->can('add_transfer_transactions'),
            'create_donors' => $user->can('create', Donor::class),
            'create_representatives' => $user->can('create', Representative::class),
            'update_representatives' => $user->can('update', Representative::class),
            'delete_representatives' => $user->can('delete', Representative::class),
            'delete_medical_releases' => $user->can('delete', MedicalRelease::class)
        ];

        \JavaScript::put([
            'user' => ['can' => $permissions]
        ]);
    }
}