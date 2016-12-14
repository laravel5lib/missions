<?php namespace App\Filters\v1;

class MedicalReleaseFilter extends Filter
{
    use Manageable;

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
    public $sortable = ['name', 'created_at', 'updated_at'];

    /**
     * Default searchable fields.
     *
     * @var array
     */
    public $searchable = ['name', 'ins_provider', 'ins_policy_no'];

    /**
     * By user id.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function user($id)
    {
        return $this->where('user_id', $id);
    }
}
