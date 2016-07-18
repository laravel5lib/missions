<?php namespace App\Filters\v1;

use EloquentFilter\ModelFilter;

class StatFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [method1, method2]]
    *
    * @var array
    */
    public $relations = [
        'tags' => ['tags']
    ];

    public function region($id)
    {
        return $this->whereHas('team', function($team) use($id)
        {
            $team->whereHas('region', function($region) use($id)
            {
                $region->where('id', $id);
            });
        });
    }

    public function team($id)
    {
        return $this->where('team_id', $id);
    }
}