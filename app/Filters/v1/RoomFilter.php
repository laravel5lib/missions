<?php namespace App\Filters\v1;

class RoomFilter extends Filter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [
        'plans' => ['campaign', 'group'],
        'accommodations' => ['region']
    ];

    /**
     * Default sortable fields.
     *
     * @var array
     */
    public $sortable = ['label', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * Default searchable fields.
     *
     * @var array
     */
    public $searchable = ['label'];

    /**
     * Filter rooms assigned to plans.
     * 
     * @param  array|integer $ids
     * @return query
     */
    public function plans($ids)
    {
        return $this->whereHas('plans', function ($plan) use ($ids) {

            $plan->whereRoomableType('plans');

            if (is_array($ids)) {
                return $plan->whereIn('roomable_id', $ids);
            }

            return $plan->whereRoomableId($ids);
        });
    }

    public function accommodations($ids)
    {
        return $this->whereHas('accommodations', function ($accommodation) use ($ids) {

            $accommodation->whereRoomableType('accommodations');

            if (is_array($ids)) {
                return $accommodation->whereIn('roomable_id', $ids);
            }

            return $accommodation->whereRoomableId($ids);
        });
    }

    /**
     * Filter by room type.
     * 
     * @param  string $type
     * @return query
     */
    public function type($type)
    {
        return $this->where('room_type_id', $type)
                    ->orWhereHas('type', function ($query) use ($type) {
                        return $query->where('name', strtolower($type));
                    });
    }
}
