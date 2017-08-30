<?php namespace App\Filters\v1;

class RoomingPlanFilter extends Filter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = ['rooms' => ['notInUse']];

    /**
     * Default sortable fields.
     *
     * @var array
     */
    public $sortable = ['name', 'created_at', 'updated_at'];

    /**
     * Default searchable fields.
     *
     * @var array
     */
    public $searchable = ['name'];

    public function campaign($id)
    {
        return $this->where('campaign_id', $id);
    }

    public function groups(array $ids)
    {
        return $this->whereHas('groups', function ($query) use ($ids) {
            $query->whereIn('id', $ids);
        });
    }
}
