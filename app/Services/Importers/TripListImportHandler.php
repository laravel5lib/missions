<?php

namespace App\Services\Importers;

use App\Models\v1\User;
use App\Models\v1\Group;
use App\Utilities\v1\TeamRole;
use Illuminate\Support\Facades\Request;

class TripListImportHandler extends ImportHandler
{

    /**
     * The model class to use
     *
     * @var string
     */
    public $model = 'App\Models\v1\Trip';

    /**
     * The database columns and document
     * columns to find matches on.
     *
     * @var array
     */
    public $duplicates = ['group.name' => 'group_name', 'type' => 'type'];

    public function match_columns_to_properties($trip)
    {
        $trip = $trip->transform(function ($item) {
            return trim($item);
        });

        $group = Group::where('name', $trip->group_name)->first();
        $rep = User::where('email', $trip->rep_email)->first();

        return [
            'campaign_id' => Request::get('parent_id'),
            'group_id' => $group ? $group->id : null,
            'spots' => (int) $trip->spots,
            'companion_limit' => (int) $trip->companion_limit,
            'country_code' => strtolower($trip->country_code),
            'type' => $trip->type,
            'difficulty' => $trip->difficulty,
            'rep_id' => $rep ? $rep->id : null,
            'todos' => explode(',', $trip->todos),
            'prospects' => explode(',', $trip->prospects),
            'team_roles' => $this->get_team_roles($trip->team_roles),
            'description' => strip_tags($trip->description),
            'public' => (bool) $trip->visibility == 'public' ? true : false,
            'started_at' => $trip->started_at,
            'ended_at' => $trip->ended_at,
            'closed_at' => $trip->closed_at,
            'published_at' => $trip->published_at,
            'created_at' => $trip->created_at,
            'updated_at' => $trip->updated_at,
            'facilitators' => $this->get_facilitators_by_email($trip->facilitator_emails)
        ];
    }

    private function get_facilitators_by_email($emails)
    {
        $emails = is_array($emails) ? $emails : explode(',', $emails);

        $userIds = User::whereIn('email', $emails)->pluck('id')->all();

        return $userIds;
    }

    private function get_team_roles($roles)
    {
        $roles = is_array($roles) ? $roles: explode(',', $roles);

        $roleCodes = collect($roles)->map(function ($role) {
            return TeamRole::get_code($role);
        })->all();

        return $roleCodes;
    }
}
