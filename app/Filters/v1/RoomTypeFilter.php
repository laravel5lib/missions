<?php 

namespace App\Filters\v1;

class RoomTypeFilter extends Filter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    /**
     * Default sortable fields.
     *
     * @var array
     */
    public $sortable = ['created_at', 'updated_at', 'name'];

    /**
     * Default searchable fields.
     *
     * @var array
     */
    public $searchable = ['name'];

    public function plan($id)
    {
        return $this->whereHas('plans', function($query) use($id) {
            return $query->where('id', $id);
        });
    }

    public function accommodation($id)
    {
        return $this->whereHas('accommodations', function($query) use($id) {
            return $query->where('id', $id);
        });
    }
}
