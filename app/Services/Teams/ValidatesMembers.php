<?php

namespace App\Services\Teams;

use App\Models\v1\TeamSquad;
use App\Models\v1\Reservation;
use App\Utilities\v1\TeamRole;
use Illuminate\Support\Collection;

class ValidatesMembers
{
    protected $members;
    protected $squad;
    protected $rules;

    function __construct(Collection $members, TeamSquad $squad)
    {
        $this->members = $members;
        $this->squad = $squad;
        $this->rules = $squad->team->type->rules();
    }

    /**
     * Run all assertions.
     */
    public function validate()
    {
        $this->assertWithinTeamMemberLimit()
             ->assertWithinSquadMemberLimit()
             ->assertWithinSquadLeaderLimit()
             ->assertHasLeadershipRole()
             ->done();
    }

    public function assertWithinTeamMemberLimit()
    {
        if (! $this->rules->has('max_members')) {
            return $this;
        }

        $this->squad->load('team.squads.members');
        $members = $this->squad->team->squads->pluck('members')->count();

        $total = $this->members->count() + $members;

        if ($total > $this->rules->max_members) {
            throw new \Exception("The team can only have a total of $this->rules->max_members member(s).");
        }

        return $this;
    }

    /**
     * Assert that members to be added are within the squad's member limit.
     *
     * @return this
     */
    public function assertWithinSquadMemberLimit()
    {
        if (! $this->rules->has('max_group_members')) {
            return $this;
        }

        $count = $this->members->count() + $this->squad->members()->count();

        if ($count > $this->rules->max_group_members) {
            throw new \Exception("Not enough room. The group can only have a total of $this->rules->max_group_members member(s).");
        }

        return $this;
    }

    /**
     * Assert that leaders to be added are witin the squad's leader limit.
     *
     * @return this
     */
    public function assertWithinSquadLeaderLimit()
    {
        if (! $this->rules->has('max_group_leaders')) {
            return $this;
        }

        $count = $this->getSquadLeaderCount();

        if ($count > $this->rules->max_group_leaders) {
            $limit = $this->rules->max_group_leaders;
            throw new \Exception("The group can only have a total of $limit leader(s).");
        }

        return $this;
    }

    public function assertHasLeadershipRole()
    {
        $this->members->filter(function ($member) {
            return $member['leader'] == true;
        })->each(function ($member, $key) {
            $reservation = Reservation::findOrFail($key);

            if (! in_array($reservation->desired_role, array_keys(TeamRole::leadership()))) {
                throw new \Exception("$reservation->given_names $reservation->surname does not have an approved leadership role.");
            }
        });

        return $this;
    }

    /**
     * Done with assertions.
     *
     * @return boolean.
     */
    public function done()
    {
        return true;
    }

    private function getSquadLeaderCount()
    {
        $newLeaders = $this->members->filter(function ($member) {
            return $member['leader'] == true;
        })->count();

        $existingLeaders = $this->squad->members->filter(function ($member) {
            return $member->pivot->leader == true;
        })->count();

        $count = $newLeaders + $existingLeaders;

        return $count;
    }
}
