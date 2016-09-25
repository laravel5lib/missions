<?php namespace App\Filters\v1;

use EloquentFilter\ModelFilter;

class StoryFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function group($id)
    {
        return $this->whereHas('groups', function($group) use($id) {
            $group->where('id', $id);
        });
    }

    public function user($id)
    {
        return $this->whereHas('users', function($user) use($id) {
            $user->where('id', $id);
        });
    }

    public function fundraiser($id)
    {
        return $this->whereHas('fundraisers', function($fundraiser) use($id) {
            $fundraiser->where('id', $id);
        });
    }

    /**
     * Find by search
     *
     * @param $search
     * @return mixed
     */
    public function search($search)
    {
        return $this->where(function($q) use ($search)
        {
            return $q->where('title', 'LIKE', "%$search%")
                ->orWhere('content', 'LIKE', "%$search%");
        });
    }
}
