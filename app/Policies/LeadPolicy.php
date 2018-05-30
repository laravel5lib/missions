<?php

namespace App\Policies;

use App\Models\v1\Lead;
use App\Models\v1\User;

class LeadPolicy extends BasePolicy
{
    public function view(User $user, Lead $lead = null)
    {
        return $user->can('view_leads');
    }

}
