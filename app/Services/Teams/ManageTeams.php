<?php
namespace App\Services\Teams;

use App\Models\v1\Group;
use App\Models\v1\Region;
use App\Models\v1\Campaign;

class ManageTeams
{   
    protected $teamableType;
    protected $teamableId;

    function __construct($teamableType, $teamableId)
    {
        $this->teamableType = $teamableType;
        $this->teamableId = $teamableId;
    }

    public function add(array $teamIds)
    {
        return $this->getModel()
                    ->findOrFail($this->teamableId)
                    ->teams()
                    ->attach($teamIds);
    }

    public function remove($teamId)
    {
        return $this->getModel()
                    ->findOrFail($this->teamableId)
                    ->teams()
                    ->detach($teamId);
    }

    private function getModel()
    {
        $models = [
            'groups' => Group::class,
            'campaigns' => Campaign::class,
            'regions' => Region::class
        ];

        return app()->make($models[$this->teamableType]);
    }
}