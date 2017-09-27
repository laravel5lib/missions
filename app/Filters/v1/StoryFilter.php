<?php namespace App\Filters\v1;

class StoryFilter extends Filter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    /**
     * Fields that can be sorted.
     *
     * @var array
     */
    public $sortable = ['title', 'content', 'created_at', 'updated_at'];

    /**
     * Fields that can be searched.
     *
     * @var array
     */
    public $searchable = ['title', 'content'];

    /**
     * By group.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function group($id)
    {
        return $this->whereHas('groups', function ($group) use ($id) {
            $group->where('id', $id);
        });
    }

    /**
     * By user.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function user($id)
    {
        return $this->whereHas('users', function ($user) use ($id) {
            $user->where('id', $id);
        });
    }

    /**
     * By fundraiser.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function fundraiser($id)
    {
        return $this->whereHas('fundraisers', function ($fundraiser) use ($id) {
            $fundraiser->where('id', $id);
        });
    }
}
