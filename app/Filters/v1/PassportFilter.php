<?php namespace App\Filters\v1;

use Carbon\Carbon;

class PassportFilter extends Filter
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
    public $sortable = ['given_names', 'surname'];

    /**
     * Default searchable fields.
     *
     * @var array
     */
    public $searchable = ['given_names', 'surname', 'number'];

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

    /**
     * By expired.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function expired()
    {
        return $this->where('expires_at', '<=', Carbon::now());
    }
}
