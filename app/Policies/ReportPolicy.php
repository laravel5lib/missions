<?php

namespace App\Policies;

use App\Models\v1\User;
use App\Models\v1\Report;

class ReportPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the report.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Report  $report
     * @return mixed
     */
    public function view(User $user, Report $report = null)
    {
        if ($user->can('view_reports')) {
            return true;
        }

        return $report ? $report->user_id == $user->id : false;
    }

    /**
     * Determine whether the user can create reports.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('add_reports');
    }

    /**
     * Determine whether the user can update the report.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Report  $report
     * @return mixed
     */
    public function update(User $user, Report $report)
    {
        if ($user->can('edit_reports')) {
            return true;
        }

        return $report->user_id == $user->id;
    }

    /**
     * Determine whether the user can delete the report.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Report  $report
     * @return mixed
     */
    public function delete(User $user, Report $report)
    {
        if ($user->can('delete_reports')) {
            return true;
        }

        return $report ? $report->user_id == $user->id : false;
    }
}
