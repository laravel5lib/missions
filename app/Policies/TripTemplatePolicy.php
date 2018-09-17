<?php

namespace App\Policies;

use App\Models\v1\User;
use App\Models\v1\TripTemplate;

class TripTemplatePolicy extends BasePolicy
{
    public function view(User $user, TripTemplate $template = null)
    {
        return $user->can('view_trip_templates');
    }

    public function create(User $user)
    {
        return $user->can('create_trip_templates');
    }

    public function update(User $user, TripTemplate $template)
    {
        return $user->can('edit_trip_templates');
    }

    public function delete(User $user, TripTemplate $template)
    {
        return $user->can('delete_trip_templates');
    }
}
