<?php

namespace App\Services\Teams;

use App\Models\v1\Team;

class ValidatesSquads
{
    protected $team;
    protected $rules;

    function __construct(Team $team)
    {
        $this->team = $team;
        $this->rules = $team->type->rules();
    }

    public function validate()
    {
        $this->assertWithinSquadLimit()->done();
    }

    public function assertWithinSquadLimit()
    {
        if ( ! $this->rules->has('max_groups')) return $this;

        $count = $this->team->squads()->count() + 1;

        if ($count > $this->rules->max_groups) {
            throw new \Exception("The squad can only have a total of $this->rules->max_groups group(s).");
        }

        return $this;
    }

    public function assertMeetsMinimumSquads()
    {
        if ( ! $this->rules->has('min_groups')) return $this;

        $count = $this->team->squads()->count() - 1;

        if ($count < $this->rules->min_groups) {
            throw new \Exception("The squad must have a minimum of $this->rules->min_groups group(s).");
        }
    }

    public function done()
    {
        return true;
    }
}