<?php namespace App\Filters\v1;

class RequirementFilter extends Filter
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
    public $sortable = ['name', 'document_type', 'due_at'];

    /**
     * Default searchable fields.
     *
     * @var array
     */
    public $searchable = ['name', 'short_desc'];

    /**
     * Filter by document type.
     *
     * @param $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function document($type)
    {
        return $this->where('document_type', $type);
    }

    /**
     * Filter by requester.
     *
     * @param $requester
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function requester($requester)
    {
        $param = preg_split('/\|+/', $requester);

        if (isset($param[1])) {
            $this->where('requester_id', $param[1]);
        }

        return $this->where('requester_type', $param[0]);
    }

    public function unique()
    {
        return $this->groupBy('name');
    }
}
