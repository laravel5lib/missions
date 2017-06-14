<?php 
namespace App\Filters\v1;

class TeamTypeFilter extends Filter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function campaign($id)
    {
        return $this->where('campaign_id', $id);
    }
}
