<?php namespace App\Filters\v1;

class RepresentativeFilter extends Filter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    /**
     * Fields that can be sorted.
     *
     * @var array
     */
    public $sortable = ['name', 'email', 'phone', 'created_at', 'updated_at'];

    /**
     * Find by search terms.
     *
     * @param $terms
     * @return mixed
     */
    public function search($terms)
    {
        return $this->where('email', 'LIKE', "%$terms%");
    }

}
