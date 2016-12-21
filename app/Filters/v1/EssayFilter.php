<?php namespace App\Filters\v1;

class EssayFilter extends Filter
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
    public $sortable = ['author_name', 'subject'];

    /**
     * Default searchable fields.
     *
     * @var array
     */
    public $searchable = ['author_name', 'content'];

    /**
     * Filter by user id.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function user($id)
    {
        if ( ! key_exists('manager', $this->input)) {
            return $this->where('user_id', $id);
        }

        return null;
    }

    /**
     * Filter by subject.
     *
     * @param $subject
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function subject($subject)
    {
        return $this->where('subject', $subject);
    }
}
